<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: index.php');
}

	if(isset($_POST['ExportExcel']))
	{
		require_once 'PHPExcel-1.8/Classes/PHPExcel.php';

		require_once 'PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
		//ce fichier montre un exemple permettant de generer un fichier excel (on peut remplacer le .csv par .xls)

		//parametres de connexion a la bdd
		//Premiere ligne = nom des champs (si on en a besoin)
		//$csv_output = "p_nom,p_email";
		//$csv_output .= "\n";

		//Requete SQL
		$infosCouts = $conectBDD->Cout_Liste_parImmatriculation_Entre2Dates($_SESSION['ImmatSaisi'], $_SESSION['Date1'], $_SESSION['Date2']);
		$result = $infosCouts
		or die('Erreur SQL !<br />' . $query . '<br />' . mysql_error());

		//Boucle sur les resultats
		$classeur = new PHPExcel;

	    $classeur->getProperties()->setCreator("CROUS AUTO/VELO");

	    //$classeur->setActiveSheetIndex(0);

	    $feuille=$classeur->createSheet();

	    // ajout des données dans la feuille de calcul
		$Date1= new DateTime($_SESSION['Date1']);
		$d1  = $Date1->format('d/m/Y') . ' au ';
		$Date2= new DateTime($_SESSION['Date2']);
		$d2 = $Date2->format('d/m/Y');

	    $feuille->setTitle('Coûts du véhicule ' . $_SESSION['ImmatSaisi']);
	    $feuille->SetCellValue('A1', 'Coûts du véhicule ' . $_SESSION['ImmatSaisi'] . ' du ' . $d1 . ' au ' . $d2);
	    
		$feuille->SetCellValue('A2', 'Date');
	    $feuille->SetCellValue('B2', 'Type');
	    $feuille->SetCellValue('C2', 'Informations');
	    $feuille->SetCellValue('D2', 'Kilometrage');
	    $feuille->SetCellValue('E2', 'Document');
	    $feuille->SetCellValue('F2', 'Montant');

	    $feuille->mergeCells('A1:F1');

		$montantTotal = 0;
		$compteur = 3;

		foreach($infosCouts as $unCout) 
		{
			if($unCout['DatePassage'] != '1900-01-01')
			{
				
				$Date= new DateTime($unCout['DatePassage']);
				if($unCout['Type'] == "Péage")
					$date = $Date->format('m/Y');
				else
					$date = $Date->format('d/m/Y');

				switch($unCout['Informations'])
				{
					case '0':
					$unCout['Informations'] = 'Ajourné';
					break;
					case '1':
					$unCout['Informations'] = 'Accepté';
					break;
					default:
					$unCout['Informations'] = $unCout['Informations'];
					break;
				}

				$Date= new DateTime($unCout['DatePassage']);
				if($unCout['Type'] == "Péage")
					$date = $Date->format('m/Y');
				else
					$date = $Date->format('d/m/Y');
				switch($unCout['Informations'])
				{
					case '0':
					$unCout['Informations'] = '<span class="badge badge-danger">Ajourné</span>';
					break;
					case '1':
					$unCout['Informations'] = '<span class="badge badge-success">Accepté</span>';
					break;
					default:
					$unCout['Informations'] = $unCout['Informations'];
					break;

				}
				if($unCout['Montant'] != '')
					$unCout['Montant'] = $unCout['Montant'] . ' €';
				$feuille->SetCellValue('A' . $compteur . '', $date );
			    $feuille->SetCellValue('B' . $compteur . '', $unCout['Type']);
			    $feuille->SetCellValue('C' . $compteur . '', $unCout['Informations']);
			    $feuille->SetCellValue('D' . $compteur . '', $unCout['Kilometrage']);
			    $feuille->SetCellValue('E' . $compteur . '', $unCout['Document']);
			    $feuille->SetCellValue('F' . $compteur . '', $unCout['Montant']);
				$montantTotal += (float) $unCout['Montant'];
				$compteur += 1;
			}
		}
		$feuille->SetCellValue('A' . $compteur . '', 'Montant Total pour cette période ' );
		$styleMontantTotal = $feuille-> getStyle('A' . $compteur . '');
		$styleMontantTotal-> applyFromArray(array(
        'font'=> array('bold'=> true),
        'fond'=> array(
                'type'=>PHPExcel_Style_Fill::FILL_SOLID,
                'color'=>array('argb'=> 'FFD897')
	        )
	    )
		);
		$styleMontantTotal = $feuille-> getStyle('F' . $compteur . '');
		$styleMontantTotal-> applyFromArray(array(
        'font'=> array('bold'=> true),
        'fond'=> array(
                'type'=>PHPExcel_Style_Fill::FILL_SOLID,
                'color'=>array('argb'=> 'FFD897')
	        )
	    )
		);
		 $feuille->mergeCells('A' . $compteur . ':E' . $compteur . '');
		$feuille->SetCellValue('F' . $compteur . '', $montantTotal . '€' );

		$compteur-=3;

		
		$styleA = $feuille-> getStyle('A1:G2');
		$styleA-> applyFromArray(array(
        'font'=> array('bold'=> true),
        'fond'=> array(
                'type'=>PHPExcel_Style_Fill::FILL_SOLID,
                'color'=>array('argb'=> 'FFD897')
	        )
	    )
		);
		$classeur->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$classeur->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $classeur->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $classeur->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $classeur->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $classeur->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        //$classeur->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);




		$objWriter = new PHPExcel_Writer_Excel2007($classeur); 
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
		header('Content-Disposition:inline;filename=Coûts_vehicule' . $_SESSION['ImmatSaisi'] . '.xlsx '); 
		$objWriter->save('php://output');
		exit ;
	}
//var_dump($_SESSION['Immat']);?>
<!DOCTYPE html>
<html>
<head>
	<title>Coût de fonctionnement du Véhicule</title>
	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeUser = $conectBDD->User_Liste();
	
	$infosVehicule = $conectBDD->Vehicule_Liste_parImmatriculation($_SESSION['ImmatSaisi']);	

	?>
</head>
<body>
	<div id="top-margin" class="top-margin">
		<button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button><h1 class="title" style="display: inline-block;">Coût de fonctionnement du Véhicule</h1>&nbsp&nbsp

		<div class="tr-ajust">
			<table class="table">

				<tr>
					<th scope="row">Type</th>
					<th scope="col">Immatriculation</th>
					<td scope="row">Date de 1ère Imatriculation</td>
					<td scope="row">Constructeur - Modèle</td>
					<td scope="row">Carburant</td> 
					<?php
					if($infosVehicule['TypeVehicule'] != "Velo")
						echo '<td scope="row">Puissance Fiscal</td>';
					?>
					<td scope="row">Propriétaire</td>
				</tr>
				<tr>
					<th> <?php echo $infosVehicule['TypeVehicule']; ?></th>
					<th scope="col"> <?php echo $infosVehicule['ImmatriculationVehicule']; ?> </th>
					<td><?php echo $infosVehicule['DatePremiereImmatriculationVehicule']; ?> </td>
					<td> <?php echo $infosVehicule['ConstructeurVehicule'] . ' - ' . $infosVehicule['ModeleVehicule']; ?> </td>
					<td> <?php echo $infosVehicule['TypeCarburantVehicule']; ?></td>
					<?php
					if($infosVehicule['TypeVehicule'] != "Velo")
						echo' <td> ' . $infosVehicule['PuissanceVehicule'] . '</td>';
					?>
					<td><?php echo $infosVehicule['NomProprietaire']; ?> </td>
				</tr>
			</table>
		</div>


		<br>
		<form method="post">
			<?php
			/*
			var_dump(!isset($_POST['Date1']));
			var_dump(!isset($_POST['Date2']));
			var_dump(!isset($_SESSION['Date1']));
			var_dump(!isset($_SESSION['Date2']));
			*/
			if(((!isset($_POST['Date1'])) and (!isset($_POST['Date2']))) and ((!isset($_SESSION['Date1'])) and (!isset($_SESSION['Date2']))))
			{
			  ?>
			  <h3>Voir les Coûts du
			  <input type="text" required name="Date1" style="width: 200px; display: inline-block;" class="input-text form-control" aria-describedby="basic-addon1" id="datepicker2" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value = "' . date('Y') . '-01-01"'; ?>>
			  au
			  <input type="text" required name="Date2" style="width: 200px; display: inline-block;"  class="input-text form-control" aria-describedby="basic-addon1" id="datepicker" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value = "' . date('Y') . '-12-31"'; ?>>
			  <button class="btn btn-primary" ><p><div class="glyphicon glyphicon-ok"></div> Valider</p></button></h3>
			</form> 
			  <?php
			}
			else
			{
			  if(isset($_POST['Date1']))
			    $_SESSION['Date1'] = $_POST['Date1'];
			  if(isset($_POST['Date2']))
			    @$_SESSION['Date2'] = $_POST['Date2'];
			  ?>
			  <h3>Voir les Coûts du
			  <input type="text" required name="Date1" style="width: 200px; display: inline-block;" class="input-text form-control" aria-describedby="basic-addon1" id="datepicker2" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value = "' . $_SESSION['Date1'] . '"'; ?>>
			  au
			  <input type="text" required name="Date2" style="width: 200px; display: inline-block;"  class="input-text form-control" aria-describedby="basic-addon1" id="datepicker" autocomplete="off" placeholder="Sélectionnez une date"<?php echo 'value = "' . $_SESSION['Date2'] . '"'; ?>>
			  <button class="btn btn-primary" ><p><div class="glyphicon glyphicon-ok"></div> Valider</p></button></h3>
			</form> 
			<?php
				$infosEntretien = $conectBDD->Cout_Liste_parImmatriculation_Entre2Dates($_SESSION['ImmatSaisi'], $_SESSION['Date1'], $_SESSION['Date2']);

				if($infosVehicule['TypeVehicule'] != "Velo")
				{
					?>


					<h1 style="display: inline-block;" class="title">
						Coût de fonctionnement du véhicule du
						<?php
							$Date1= new DateTime($_SESSION['Date1']);
							echo $Date1->format('d/m/Y') . ' au ';
							$Date2= new DateTime($_SESSION['Date2']);
							echo $Date2->format('d/m/Y');
						?>
					</h1>

						<table class="table">
							<tr>
								<th scope="col">Date</th>
								<th scope="col">Type</th>
								<th scope="col">Informations</th>
								<th scope="col">Kilometrage</th>
								<th scope="col">Document</th>
								<th scope="col">Montant</th>
							</tr>

							<?php


							if($infosEntretien==null)
							{
								echo '<td colspan="7"><center><b>Aucun coûts enregistré</b></center></td>';
							}
							else
							{
								echo'';
								$montantTotal = 0;
								foreach ($infosEntretien as $uneLigne) 
								{
									if($uneLigne['DatePassage'] != '1900-01-01')
									{
										echo '';
										$Date= new DateTime($uneLigne['DatePassage']);
										if($uneLigne['Type'] == "Péage")
											$date = $Date->format('m/Y');
										else
											$date = $Date->format('d/m/Y');
										echo '<tr><th scope="row">'. $date .'</th>';
										echo '<th scope="row">'.$uneLigne['Type'].'</th>';

										switch($uneLigne['Informations'])
										{
											case '0':
											$uneLigne['Informations'] = 'Ajourné';
											break;
											case '1':
											$uneLigne['Informations'] = 'Accepté';
											break;
											default:
											$uneLigne['Informations'] = $uneLigne['Informations'];
											break;

										}
										echo '<th scope="row">' . $uneLigne['Informations'] . '</th>';
										echo '<td>'.$uneLigne['Kilometrage'].'</td>';

										$doc = $uneLigne['Document'];
										echo '<td> <a href="doc/' . $doc . '">'. $doc .'</td>';
										if($uneLigne['Montant'] != '')
											$uneLigne['Montant'] = $uneLigne['Montant'] . ' €';
										echo '<td>' .  $uneLigne['Montant'] . '</td></tr>';
										$montantTotal += (float) $uneLigne['Montant'];
									}
								}
								echo '<tr><td colspan="5"><b><center>Montant Total pour cette période </center></b></td><td><b>' .  $montantTotal . ' €</b></td></tr>';
							}
							
							?>
							</table>

					<?php
					
				}

				?>

				<br>
				<form method="post">
					<button class="btn btn-default" name='ExportExcel' value="ExportExcel"><div class="glyphicon glyphicon-th"></div> Export Excel</button>
				</form>

				<div id="graphDetailCoutKm" class="graph-row"></div>

				<br><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
				<?php
			}
			?>
			
			</div>
		</body>
	<footer>
		<?php
		include("footer.php");
		?>
	</footer>
	</html>
<script type="text/javascript">
	var chartCoutKmVehicule = Highcharts.chart('graphDetailCoutKm', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Détail du Cout Kilométrique entre les 2 derniers relevés'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} €/km</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} €/km',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
  <?php
  $premier = true;

    
    if($infosVehicule['TypeVehicule'] != 'Velo')
    {
      $infosKmVehicule = $conectBDD->Kilometrage_Liste_parImmatriculationASC($infosVehicule['ImmatriculationVehicule']);
      

      if($infosKmVehicule == null)
      {
        $diffKm = 0;
      }
      else
      {
        $premierKm = true;
        $km1 = 0;
        $dateReleve1 = '1900-01-01';
        $dateReleve2 = '1900-01-01';
        $km2 = 0;
        foreach ($infosKmVehicule as $unKm)
        {
          if($premierKm)
          {
            $km1 = $unKm['KilometrageReleve'];
            $dateReleve1 = $unKm['DateKilometrage'];
            $premierKm = false;
          }
          else
          {
            $km2 = $unKm['KilometrageReleve'];
            $dateReleve2 = $unKm['DateKilometrage'];
            break;
          }
        }
        if($km2 == 0)
        {
          $dateReleve2 = $dateReleve1;
          $dateReleve1 = $infosVehicule['DatePremiereImmatriculationVehicule'];
          $diffKm = $km1 - $infosVehicule['KilometrageVehicule'];
        }
        else
        {
          $diffKm = $km2 - $km1;
        }
      }

    
   $listeCoutVehicule = $conectBDD->Cout_Liste_Entre2Dates_ParType_EtVehicule($infosVehicule['ImmatriculationVehicule'], $dateReleve1, $dateReleve2);
  
  foreach ($listeCoutVehicule as $unCout) 
  {

  	if($diffKm == 0)
   	  $coutKilometrique = 0;
 	else
   	  $coutKilometrique = $unCout['Montant'] / $diffKm;
    if($coutKilometrique != 0)
    {
      if(!$premier)
      {
        echo ',';
      }
      $premier = false;
      ?>
    
      {
      name: <?php echo "'".$unCout["NomCout"] . "'";?>,
      y: <?php echo $coutKilometrique;?>,
      sliced: true,
      selected: true
      }
      <?php
    }
  }
}
    ?>
    ]
  }]
});

	function redrawHighcharts() {
    for (var i = 0; i < Highcharts.charts.length; i++) {
        Highcharts.charts[i].reflow();
    }
}

$(document).ready(function(){
             redrawHighcharts();
            });

var observer = new MutationObserver(function(mutations) {
  redrawHighcharts();
  });
  var target = document.querySelector('.top-margin');
  observer.observe(target, {
    attributes: true
  });

</script>