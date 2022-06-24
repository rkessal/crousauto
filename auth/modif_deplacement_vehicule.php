<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0 and $liste['ReserverVehicule'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification d'une Réservation</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	if(isset($_REQUEST['idDeplacement']))
	{
		$_SESSION['idDeplacement'] = $_REQUEST['idDeplacement'];
	}

	$InfosDeplacement = $conectBDD->Utilise_Infos_parId($_SESSION['idDeplacement']);
	$_SESSION['ImmatSaisi'] = $InfosDeplacement['ImmatriculationVehicule'];
	$id = $_SESSION['ImmatSaisi'];
	
	$idConducteur = $InfosDeplacement['NoConducteur'];
	$idDate = $InfosDeplacement['DateDebutUtilisation'];

	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);

	$idDeplacement = $InfosDeplacement['idDeplacement'];

	if($_SESSION['NoProprietaire'] == 0)
		$listeConducteur = $conectBDD->Conducteur_Liste();
	else
		$listeConducteur = $conectBDD->Conducteur_Liste_ParProprietaire($_SESSION['NoProprietaire']);
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST" enctype="multipart/form-data">



<h1 class="title" style="display:inline-block">Modification d'une Réservation</h1>
<br>
	<?php
	if(isset($_POST["Supprimer"]))
	{
		$res = $conectBDD->Utilise_Delete($idDeplacement);

		if($res)
		{
			?>
			<div class="alert alert-warning" role="alert">
			  	La Réservation du véhicule à bien été supprimé.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=calendrier_vehicule.php"> 
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Impossible de supprimer cette réservation.
			</div>
			<?php
		}
	}
	if(isset($_POST["Valider"]))
	{
		$_POST['date'] = new DateTime($_POST['date']);
		$_POST['date'] = $_POST['date']->format('Y-m-d');
		$datedebut = $_POST['date'] . ' ' . $_POST['heuredebut'];
		$datedebut = new DateTime($datedebut);
		$datedebut = $datedebut->format('Y-m-d H:i');
		$datefin = $_POST['date'] . ' ' . $_POST['heurefin'];
		$datefin = new DateTime($datefin);
		$datefin = $datefin->format('Y-m-d H:i');
		$destination = $_POST['destination'];
		$conducteur = $_POST['conducteur'];
		

		if ($destination == "" )
		{
			$destination = null; 
		}

		if($datefin <= $datedebut)
		{
			?>
				<div class="alert alert-danger" role="alert">
				  	La Réservation de ce véhicule n'a pas été modifié. <b>L'heure de fin est inférieur à l'heure de début</b>.
				</div>
			<?php
		}
		else
		{
			$DejaUtiliser = $conectBDD->Utilise_Verificaton_parImmatriculationEntre2Dates($id, $datedebut, $datefin, $idDeplacement);
			if(!$DejaUtiliser)
			{
				$res = $conectBDD->Utilise_Modif($id, $InfosDeplacement['DateDebutUtilisation'], $datedebut, $datefin, $destination, $conducteur, $_POST['passagers']);

				if($res)
				{
					?>
					<div class="alert alert-success" role="alert">
					  	La Réservation du véhicule à bien été modifiée.
					</div>
					<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=calendrier_vehicule.php">
					<?php
				}
				else
				{
					?>
					<div class="alert alert-danger" role="alert">
					  	Erreur
					</div>
					<?php
				}
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Ce véhicule est déjà utilisé à cette heure-ci.
				</div>
				<?php
			}
			
		}
								
	}
	$InfosDeplacement = $conectBDD->Utilise_Infos_parId($_SESSION['idDeplacement']);
	$date = new DateTime($InfosDeplacement['DateDebutUtilisation']);
	$heuredebutAv = new DateTime($InfosDeplacement['DateDebutUtilisation']);
	$heurefin = new DateTime($InfosDeplacement['DateFinUtilisation']);
	$destination = $InfosDeplacement['Destination'];
	$passagers = $InfosDeplacement['NbPersonnes'];



	$color = @inverse_hexcolor($InfosVehicule['CouleurAffichageVehicule']);
	echo '<table class="table" style="background-color: ' . $InfosVehicule['CouleurAffichageVehicule'] . ';color:#' . $color . ';"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Nombre de Place</th></tr></tr>';

		echo '';
		echo '<tr><th>'.$InfosVehicule['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$InfosVehicule['ConstructeurVehicule'].' - ';
		echo  $InfosVehicule['ModeleVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['CouleurVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $InfosVehicule['NbPlaceVehicule'].'</td></tr>';
		
		echo '<tr><td colspan="5"><b><u>Gestionnaire de ce véhicule :</b></u> ';
		echo ' ' . $InfosVehicule['LieuVehicule'] . ' (Site de ' . $InfosVehicule['NomProprietaire'] . ')';
		echo '</td></tr>';
		echo '</table>';

	$idateHeureDebut = new DateTime($InfosDeplacement['DateDebutUtilisation']);
	$idateHeureDebut = $idateHeureDebut->format('Y-m-d');
	if(($liste['DroitVehicule'] != 0))
		echo '<fieldset>';
	else
		echo'<fieldset disabled>';

?>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date d'utilisation*</span>
  			<?php echo '<input type="text" required name="date" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $date->format('Y-m-d') . '" id="datepicker" autocomplete="off">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Début d'utilisation*</span>
  			<?php echo '<input type="time" required name="heuredebut" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $heuredebutAv->format('H:i') . '">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Fin d'utilisation*</span>
  			<?php echo '<input type="time" required name="heurefin" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $heurefin->format('H:i') . '">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Destination</span>
  			<?php echo '<input type="text" name="destination" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $destination . '">'; ?>
  		</div>
		<br>
		
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Conducteur*</span>
			<select class="custom-select" name="conducteur" style="width: 310px" required>
			<?php
			foreach ($listeConducteur as $uneLigne) {
				if($InfosDeplacement['NoConducteur'] == $uneLigne['NoConducteur'])
				{
					if($uneLigne['ActifConducteur'])
						echo '<option selected value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
					else
						echo '<option disabled value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
				}
				else
				{
					if($uneLigne['ActifConducteur'])
						echo '<option value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
				}
			}
			?>
			</select>
		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Passagers*</span>
				<select class="custom-select" name="passagers" style="width: auto" required>
					<?php

					$immatVoiture = $InfosVehicule['ImmatriculationVehicule'];
					$req = $conectBDD->Vehicule_Liste_parImmatriculation($immatVoiture);
					$nombrePlace = $req['NbPlaceVehicule'];
					if ($nombrePlace !=   0)
					{
						$iNombreDePlace = 0;
						while ($iNombreDePlace <= $nombrePlace -1 ) {
						if($passagers == $iNombreDePlace)
							echo '<option selected value="'. $iNombreDePlace . '">'. $iNombreDePlace . '</option>';
						else
							echo '<option value="'. $iNombreDePlace . '">'. $iNombreDePlace . '</option>'; 
						$iNombreDePlace++;
						}	
					}
					


					
					?>
				</select>
				</div>
		<br>

	
		<br>
		<h4><i class="glyphicon glyphicon-ok"></i> En réservant ce véhicule, j'accepte les <a href="conditions.php">conditions d'utilisations</a>.<br></h4>
		<?php
		if(($liste['DroitVehicule'] != 0))
			echo '<br><button type="submit" name="Valider" class="btn btn-primary">
				<i class="glyphicon glyphicon-ok-sign"></i> Modifier
			</button> <button class="btn btn-danger" type="submit" name="Supprimer"><i class="glyphicon glyphicon-remove"></i> Supprimer (irreversible)</button>';
		?><br><br>

		</form>
		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		<br>

		<!--BOUTON RETOUR
		<?php
		if(!isset($_POST["Valider"]) and !isset($_POST["Supprimer"]))
			$_SESSION['nbEnvoi'] = 1;
		if(isset($_POST["Valider"]) or isset($_POST["Supprimer"]))
		{
			$_SESSION['nbEnvoi'] += 1;
			$back = 2 + $_SESSION['nbEnvoi'];
			echo '<button class="btn btn-primary" onclick="javascript:window.history.go(-' . $back . ')"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour2</button>';
			var_dump( $_SESSION['nbEnvoi']);
		}
		else
		{
		?>
			<button class="btn btn-primary" onclick="javascript:history.back(-1)"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
			<?php var_dump( $_SESSION['nbEnvoi']); 
		}
		?>-->
</fieldset>

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
	/*eval('$color = 0x'.$color.';');

	return sprintf('%x',(-(0xff000000 + $color) - 1));*/
	return $couleur_de_texte;
	}
?>