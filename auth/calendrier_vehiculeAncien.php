<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (($liste['DroitVehicule'] == 0) and ($liste['ReserverVehicule'] == 0))
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Calendrier de l'utilisation des Véhicules</title>
	<meta charset='utf-8' />
	<?php include('menu.php') ?>
</head>

<body>
<?php
require_once('bdd.php');
$conectBDD = new BDD();

if($liste['NoProprietaire'] == 0)
	$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation();
else
{
	if($liste['NoDroit'] == 1)
		$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParProprietaire($_SESSION['NoResidence']);
	else
		$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
}

?>




<div class="top-margin" >
    <h1 class="title" style="display: inline-block;">CALENDRIER DE L'UTILISATION DES VEHICULES</h1>


    <?php
	if($_SESSION['NoResidence'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
	?>

	<form method="post" action="reservation_vehicule.php">
		<div class="alert alert-primary" role="alert">
			<center>
				<h4 class="alert-heading">Demander la réservation d'un Véhicule :</h4>
				<select class="custom-select" name="ImmatSaisi" required>
				<?php
				foreach ($listeVehicule as $uneLigne) {
					echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['TypeVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</option>';
				}
				?>
				</select>
				<input class="btn btn-primary" type="submit" name="Reserver" value="Valider">
			</center>
		</div>
	</form>


   <div class="card card-default">
	  <div class="card-body" style="overflow: auto; -webkit-overflow-scrolling: touch;border:none;">
	    <div class="calendrier-container">
	      <div id="calendar"></div>
	    </div>
	  </div>
</div>

</div>


<?php
function ChangerTonCouleur($color, $changementTon)
{
	$color = substr($color,1,6);
	$cl=explode('x',wordwrap($color,2,'x',3));
	$color='';
	for($i=0;$i<=2;$i++){
		$cl[$i]=hexdec($cl[$i]);
		$cl[$i]=$cl[$i]+$changementTon;
		if($cl[$i]<0) $cl[$i]=0;
		if($cl[$i]>255) $cl[$i]=255;
		$color=$color.StrToUpper(substr('0'.dechex($cl[$i]),-2));
	}
	return ''.$color; 
}
function inverse_hexcolor($color)
{
	$color = '' . $color;
	$colorDec = hexdec($color);
	$decimal = str_split($colorDec, 3);
	if ((0.3*($decimal[0]) + 0.59*($decimal[1]) + 0.11*($decimal[2])) < 125)
		$couleur_de_texte = 'FFFFFF'; 
	else
		$couleur_de_texte = '000000';
/*eval('$color = 0x'.$color.';');

return sprintf('%x',(-(0xff000000 + $color) - 1));*/
return $couleur_de_texte;
}


?>
</body>
</html>
	<?php
		include("footer.php");
	?>











<?php

/*

if (isset($_REQUEST['oui']))
{
	$test_sel = $_REQUEST['oui'];

	if ($test_sel = 2)
	{
		header('Location: calendrier_semaine_vehicule.php');
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Calendrier de l'utilisation des Véhicules</title>
</head>
	<?php
	include("menu.php");
	?>
<body>
	<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	$liste = $connectBDD->User_Liste_parPseudo($_SESSION['pseudo']);

	if(isset($_POST['nbMoisPlus']))
	{
		$idMois =$_POST['idMois'] + 1;
		if($idMois > 12)
		{
			$idMois = 1;
			$idAnnee = (int)$_POST['idAnnee'] + 1;

		}
		else
			$idAnnee = $_POST['idAnnee'];
	}
	else
	{
		if(isset($_POST['nbMoisMoins']))
		{

			$idMois =$_POST['idMois'] - 1;
			if($idMois < 1)
			{
				$idMois = 12;
				$idAnnee = $_POST['idAnnee'] - 1;
			}
			else
				$idAnnee = $_POST['idAnnee'];
		}
		else
		{
			$idMois = (int)date('m');
		}

	}
	$mois = nomDuMois($idMois);

	if(!isset($_POST['idAnnee']))
		$idAnnee = (int)date('Y');
	?>
<div id="top-margin" class="top-margin">

<div>
	<h1 class="title" style="display: inline-block;">CALENDRIER DE L'UTILISATION DES VEHICULES</h1>
	<form action="calendrier_vehicule.php" style="display: inline-block;">
		<select   class="custom-select" id="sel_id" name="oui" onchange="this.form.submit();">
			<option selected="selected" value="1">Par mois</option>
			<option value="2">Par semaine</option>                    
		</select>
	</form>
</div>
<?php
	if($_SESSION['NoResidence'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
?>

	<form method="post" action="reservation_vehicule.php">
		<div class="alert alert-primary" role="alert">
			<center>
				<h4 class="alert-heading">Demander la réservation d'un Véhicule :</h4>
				<select class="custom-select" name="ImmatSaisi" required>
				<?php
				foreach ($listeVehicule as $uneLigne) {
					echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['TypeVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</option>';
				}
				?>
				</select>
				<input class="btn btn-primary" type="submit" name="Reserver" value="Valider">
			</center>
		</div>
	</form>

<table class="table calendrier">
	<form method="post">
	<tr>
		<th scope="col" style="text-align: center">
			<?php echo '<input type="hidden" name="idMois" value="' . $idMois .'">'; ?>
			<?php echo '<input type="hidden" name="idAnnee" value="' . $idAnnee .'">'; ?>
			<button type="submit" name="nbMoisMoins" class="btn btn-outline-primary" style="min-width: 50px;">
			  <i class="glyphicon glyphicon-arrow-left"></i>
			</button>
		</th>
		<th class="mois" colspan="5" scope="col" style="text-align: center">
			<h1><?php echo $mois . " " . $idAnnee; ?></h1>
		</th>
		<th scope="col" style="text-align: center">
			<button type="submit" name="nbMoisPlus" class="btn btn-outline-primary" style="min-width: 50px;">
	  		<i class="glyphicon glyphicon-arrow-right"></i>
			</button>
		</th>
	</tr>
	</form>
	<tr><th style="width: 12%; text-align: center" scope="col">Lundi</th><th style="width:12%; text-align: center" scope="col">Mardi</th><th style="width:12%; text-align: center" scope="col">Mercredi</th><th style="width:12%; text-align: center" scope="col">Jeudi</th><th style="width:12%; text-align: center" scope="col">Vendredi</th><th style="width:12%; text-align: center" scope="col">Samedi</th><th style="width:12%; text-align: center" scope="col">Dimanche</th></tr>
	<?php
	$NbJoursMois = date("t", mktime(0, 0, 0, $idMois, 1, 2016)); 
	$compteur = 1;
	$demmarage = (int)NbJoursDemmarreMois($idMois, $idAnnee);
	echo "<tr>";
	$debut = false;
	$noJours = 1;
	while($noJours<=$NbJoursMois)
	{
		if(!$debut)
		{
			if($demmarage != $compteur)
			{
				echo "<td> </td>";
				$compteur +=1;
			}
			else
			{
				$debut = true;
			}
		}
		else
		{
			if($idMois <10)
				$Mois = '0' . $idMois;
			else
				$Mois = '' . $idMois;
			if($noJours <10)
				$Jours = '0' . $noJours;
			else
				$Jours = '' . $noJours;
			$date = $idAnnee . '-' . $Mois . '-' . $Jours . ' %:%:%';
			if($liste['NoProprietaire'] == 0)
				$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDate($date);
			else
			{
				if($liste['NoDroit'] == 1)
					$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDateEtProprietaire($date, $_SESSION['NoResidence']);
				else
					$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDate_SiGestion($_SESSION['NoUtilisateur'], $date, $_SESSION['NoProprietaire']);
			}

			//var_dump($listeUtilisation);
			if(!isset($listeUtilisation))
				echo "<td><b><h4>" . $noJours . "</b></h4><br><br><br><br><br><br></td>";
			else
			{
				if($listeUtilisation == null)
				{
					if($mois=="FEVRIER" and $noJours==29)
					{
						if($idAnnee%400==0 or ($idAnnee%4==0 and $idAnnee%100!=0))
							echo "<td><b><h4>&nbsp&nbsp" . $noJours . "</b></h4><br><br><br><br><br><br></td>";
						else
							echo '<td></td>';
					}
					else
						echo "<td><b><h4>&nbsp&nbsp" . $noJours . "</b></h4><br><br><br><br><br><br></td>";
				}
				else
				{
					echo "<td ><b><h4>&nbsp&nbsp" . $noJours . "</b></h4><div style='text-align:center'>";
	
					foreach ($listeUtilisation as $UneLigne)
					 {
					 	$reservation = $UneLigne['DateHeureReservation'];
						$backcolor = $UneLigne['CouleurAffichageVehicule'];
						$hachurebackcolor = "#" . ChangerTonCouleur($backcolor, -20);
						if($reservation != null)
							$hachure = ' background: repeating-linear-gradient( 45deg, ' . $backcolor . ',  ' . $backcolor . ' 10px,  ' . $hachurebackcolor . ' 10px,  ' . $hachurebackcolor . ' 15px);';
						else
							$hachure = 'background-color: ' . $backcolor . ';';
						if($reservation != null)
							echo '<form style="display: inline-block;" method="post" action="modif_reservation_vehicule.php">';
						else
							echo '<form style="display: inline-block;" method="post" action="modif_deplacement_vehicule.php">';
						$idcond = $UneLigne['NoConducteur'];
						$iddate = $UneLigne['DateDebutUtilisation'];
						$DateDebutUtilisation = new DateTime($UneLigne['DateDebutUtilisation']);
						$DateFinUtilisation = new DateTime($UneLigne['DateFinUtilisation']);
						$id = $UneLigne['ImmatriculationVehicule'];
						
						$color = @inverse_hexcolor($hachurebackcolor);
						echo '<input type="hidden" name="NoConducteurModif" value="' . $idcond . '">
								  <input type="hidden" name="DateModif" value="' . $iddate . '">
								  <input type="hidden" name="ImmatSaisi" value="' . $id . '">';
						if($liste['DroitVehicule']) 
						{
							echo '<button type="submit" style="padding:0px;margin-bottom:5px;border:none" name="Modifier">';
							echo '<div title="Modifier cette réservation" style="' . $hachure . 'background-color: ' . $backcolor . '; color:#' . $color . '; padding:5px; margin:0px; border-radius:5px;width:200px;height:100% "><center>';
						}
						else
							echo '<div style="' . $hachure . ' color:#' . $color . '; padding:5px; margin-bottom:5px; border-radius:5px;width:200px;height:100% "><center>';
						$vehicule = $conectBDD->Vehicule_Liste_parImmatriculation($UneLigne["ImmatriculationVehicule"]);
						$vehicule['NbPlaceVehicule']-=1;
						echo "<h4 style='background-color: #" . $backcolor . "; color:#" . $color . "; border-bottom: solid 1px'><div class='glyphicon glyphicon-'></div> <span class='badge'>" . $vehicule['TypeVehicule'] . "</span> <b>" . $vehicule["ConstructeurVehicule"] . " " .$vehicule["ModeleVehicule"] . "</b></h4>"; 
						echo '<div class="glyphicon glyphicon-hourglass"></div> <b>' . $DateDebutUtilisation->format('H:i') . '</b> <div class="glyphicon glyphicon-chevron-right"></div> <b>' . $DateFinUtilisation->format('H:i') . '</b><br>'; 
						echo '<div class="glyphicon glyphicon-user"></div> <b>' . $UneLigne["NomConducteur"] . ' ' . $UneLigne['PrenomConducteur'] . '</b><br> <span class="label label-default">' . $UneLigne["NbPersonnes"] . '/' . $vehicule['NbPlaceVehicule'] . ' passagers</span><br><div class="glyphicon glyphicon-flag"></div>  <b>' . $UneLigne["Destination"] . '</b>';
						
						if($liste['DroitVehicule'])
						 	echo '</button>';
						else
						 	echo '</div>';
						if($reservation != null)
						{
							echo '<input type="hidden" name="reservation" value="' . $reservation . '">';
						}
						echo '</form>';

						echo '</center>';
					}
					echo"</div></td>";
				}	
			}
			if($compteur%7==0)
				echo "</tr><tr>";
			$noJours += 1;
			$compteur += 1;
		}
	}
	if($compteur%7!=0)
	{
		if($compteur%36==0)
			$continue = false;
		else
			$continue = true;
	}
	else
	{
		$continue = false;
		echo '<td></td>';
	}
	while($continue)
	{
		echo '<td></td>';
		if($compteur%7==0)
			$continue = false;
		$compteur += 1;
	}

	echo '</tr>';
?>

	</div>
	</table>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>

</html>

<?php
function inverse_hexcolor($color)
	{
		$color = '' . $color;
		$colorDec = hexdec($color);
		$decimal = str_split($colorDec, 3);
		if ((0.3*($decimal[0]) + 0.59*($decimal[1]) + 0.11*($decimal[2])) < 125)
			$couleur_de_texte = 'FFFFFF'; 
		else
			$couleur_de_texte = '000000';
	
	return $couleur_de_texte;
	}

function nomDuMois($nbMois)
{
	switch($nbMois)
	{
		case 1:
			$nomDuMois = "JANVIER";
			break;
		case 2:
			$nomDuMois = "FEVRIER";
			break;
		case 3:
			$nomDuMois = "MARS";
			break;
		case 4:
			$nomDuMois = "AVRIL";
			break;
		case 5:
			$nomDuMois = "MAI";
			break;
		case 6:
			$nomDuMois = "JUIN";
			break;
		case 7:
			$nomDuMois = "JUILLET";
			break;
		case 8:
			$nomDuMois = "AOUT";
			break;
		case 9:
			$nomDuMois = "SEPTEMBRE";
			break;
		case 10:
			$nomDuMois = "OCTOBRE";
			break;
		case 11:
			$nomDuMois = "NOVEMBRE";
			break;
		case 12:
			$nomDuMois = "DECEMBRE";
			break;
		default:
			$nomDuMois = "ERREUR";
			break;
	}
	return $nomDuMois;
}

function NbJoursDemmarreMois($nbmois, $annee)
{
	$nomMois = date("l", mktime(0, 0, 0, $nbmois, 1, $annee));
	switch($nomMois)
	{
		case "Monday":
			$nomDuMois = 1;
			break;
		case "Tuesday":
			$nomDuMois = 2;
			break;
		case "Wednesday":
			$nomDuMois = 3;
			break;
		case "Thursday":
			$nomDuMois = 4;
			break;
		case "Friday":
			$nomDuMois = 5;
			break;
		case "Saturday":
			$nomDuMois = 6;
			break;
		case "Sunday":
			$nomDuMois = 7;
			break;
		default:
			$nomDuMois = "0";
			break;
	}
	return $nomDuMois;
}

function ChangerTonCouleur($color, $changementTon)
{
	$color = substr($color,1,6);
	$cl=explode('x',wordwrap($color,2,'x',3));
	$color='';
	for($i=0;$i<=2;$i++){
		$cl[$i]=hexdec($cl[$i]);
		$cl[$i]=$cl[$i]+$changementTon;
		if($cl[$i]<0) $cl[$i]=0;
		if($cl[$i]>255) $cl[$i]=255;
		$color.=StrToUpper(substr('0'.dechex($cl[$i]),-2));
	}
	return ''.$color; 
}*/

?>
<script>


	  
	



</script>
