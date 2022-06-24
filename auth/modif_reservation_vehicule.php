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
	<title>Modification d'une demande de réservation d'un Véhicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
		
	if(isset($_REQUEST['idReservation']))
		$_SESSION['idReservation'] = $_REQUEST['idReservation'];
	//var_dump($_REQUEST['idReservation']);
	$InfosReservation = $conectBDD->Reserver_Liste_parId($_SESSION['idReservation']);
	$_SESSION['ImmatSaisi'] = $InfosReservation['ImmatriculationVehicule'];
	$id = $_SESSION['ImmatSaisi'];
	$dateReservation = $InfosReservation['DateHeureReservation'];
	$conectBDD = new BDD();
	if($_SESSION['NoProprietaire'] == 0)
	{
		$listeConducteur = $conectBDD->Conducteur_Liste();
		$listeVehicule = $conectBDD->Vehicule_Liste();
	}
	else
	{
		if($liste['NoDroit'] == 1)
		{
			$listeConducteur = $conectBDD->Conducteur_Liste_ParResidence($_SESSION['NoResidence']);
			$listeVehicule = $conectBDD->Vehicule_Liste_ParResidence($_SESSION['NoResidence']);
		}
		else
		{
			if($liste['NoDroit'] == 2)
			{
				$listeConducteur = $conectBDD->Conducteur_Liste_ParProprietaire($_SESSION['NoProprietaire']);
				$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
			}
			else
			{
				$listeConducteur = $conectBDD->Conducteur_Liste_ParUser($_SESSION['NoUtilisateur']);
				$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
			}
		}
	}
	$InfosReservation = $conectBDD->Reserver_Liste_parId($_SESSION['idReservation']);
	?>
</head>
<body><div id="top-margin" class="top-margin">




	<?php
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
		$conducteur = (int)$_POST['conducteur'];
		$NouvelleImmat = $_POST['vehicule'];


		if ($destination == "" )
		{
			$destination = null; 
		}
		if($datefin <= $datedebut)
		{
			?>
				<div class="alert alert-danger" role="alert">
				  	La Demande de Réservation de ce véhicule n'a pas été modifié. <b>L'heure de fin est inférieur à l'heure de début</b>.
				</div>
			<?php
		}
		else
		{
			$conectBDD = new BDD();
			$res = $conectBDD->Reserver_Modif($id, $datedebut, $datefin, $destination, $conducteur, $InfosReservation['DateHeureReservation'], $NouvelleImmat, $_POST['passagers']);

			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	La Demande de Réservation de ce véhicule à bien été modifiée.
				</div>
				<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=calendrier_vehicule.php"> 
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Impossible de modifier cette demande de réservation.
				</div>
				<?php
			}
		}								
	}
	$VerifReservationAppartientUserConnect = $conectBDD->Conducteur_Liste_ParUserEtConducteur($_SESSION['NoUtilisateur'], $InfosReservation['NoConducteur']);
	
	$idateHeureDebut = new DateTime($InfosReservation['DateDebutUtilisation']);
	$idateHeureDebut = $idateHeureDebut->format('Y-m-d');
	if(($liste['DroitVehicule'] != 0) or ($VerifReservationAppartientUserConnect and date('Y-m-d') <= $idateHeureDebut))
		echo '<fieldset>';
	else
		echo'<fieldset disabled>';

	$InfosReservation = $conectBDD->Reserver_Liste_parId($_SESSION['idReservation']);
	$date = new DateTime($InfosReservation['DateDebutUtilisation']);
	$heuredebut = new DateTime($InfosReservation['DateDebutUtilisation']);
	$heurefin = new DateTime($InfosReservation['DateFinUtilisation']);
	$destination = $InfosReservation['Destination'];
	$reserve = new DateTime($InfosReservation['DateHeureReservation']);
	$passagers = $InfosReservation['NbPersonnes'];


if ($liste['DroitVehicule'] != 0 and !isset($_POST["Valider"]))
{
			echo'<form method="POST" style="display:inline-block" action="gestion_reservation_vehicule.php">
			<input type="hidden" name="Immat" value="'.$InfosReservation['ImmatriculationVehicule']. '">
			<input type="hidden" name="conducteur" value="'.$InfosReservation['NoConducteur']. '">
			<input type="hidden" name="DateHeureReservation" value="'.$InfosReservation['DateHeureReservation']. '">
			<button type="submit" name="Accepter" class="btn btn-success">
				<i class="glyphicon glyphicon-ok"></i> Accepter
			</button> &nbsp
			<button type="submit" name="Refuser" class="btn btn-danger">
				<i class="glyphicon glyphicon-remove"></i> Refuser
			</button> &nbsp
			</form>';
?>	<br><br>
<h1> Ou modifier une demande de Réserveration d'un Véhicule</h1>
<?php
}
else
{
	echo '<h1> Modifier une demande de Réserveration d\'un Véhicule</h1>';
}
?>

<br>
<form method="POST">
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date et heure de Reservation</span>
  			<?php echo'<input type="text" disabled required name="datedebut" value="'. $reserve->format('d/m/Y H:i') . '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Véhicule*</span>
				<select id="liste-vehicule" class="custom-select" style="width: 500px" name="vehicule" style="width: auto" required onchange="this.form.submit();">
					<?php

					if (isset($_REQUEST['vehicule']))
					{
						$vehicule = $_REQUEST['vehicule'];
					}

					if (!isset($_REQUEST['vehicule']))
					{
						foreach ($listeVehicule as $uneLigne) {
						if($uneLigne['ImmatriculationVehicule'] == $InfosReservation['ImmatriculationVehicule']){
							$selectedVehicule = $uneLigne['ImmatriculationVehicule'];
  							$req = $conectBDD->Vehicule_Liste_parImmatriculation($selectedVehicule);
  							$nombrePlace = $req['NbPlaceVehicule'];
							echo '<option selected value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . ' (' . $uneLigne['TypeVehicule'] . ')</option>';}
						else
							echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule']  . ' (' . $uneLigne['TypeVehicule'] . ')</option>';
					}




					}
					else
					{
						foreach ($listeVehicule as $uneLigne) {
						if($uneLigne['ImmatriculationVehicule'] == $vehicule){
							$selectedVehicule = $_POST['vehicule'];
							$req = $conectBDD->Vehicule_Liste_parImmatriculation($_POST['vehicule']);
							var_dump($req);
							$nombrePlace = $req['NbPlaceVehicule'];
							echo '<option selected value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . ' (' . $uneLigne['TypeVehicule'] . ')</option>';}
						else
							echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . '</option>';
					}
					}

				

					
					
					?>
				</select>
			
				<?php
				if($liste['DroitVehicule']) 
					echo '<a class="btn btn-primary" href="creation_vehicule.php"><p>Créer un Véhicule</p></a>';
				?>
		</div><br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Conducteur*</span>
				<select class="custom-select" style="width: 500px" name="conducteur" style="width: auto" required>
					<?php
					foreach ($listeConducteur as $uneLigne) {
						if($uneLigne['ActifConducteur'])
							if($uneLigne['ActifConducteur'])
						{
							if($uneLigne['NoConducteur'] == $InfosReservation['NoConducteur'])
								echo '<option selected value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
							else
								echo '<option value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
						}
							
					}
					?>
				</select>
				<?php
				if($liste['DroitVehicule']) 
					echo '<a class="btn btn-primary" href="creation_conducteur.php"><p>Créer un Conducteur</p></a>';
				?>
		</div><br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date d'utilisation*</span>
  			<?php echo'<input type="text" required name="date" value="'. $date->format('Y-m-d'). '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1"  class="datepicker" id="datepickerAvecDateMin" autocomplete="off" readonly>'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Début*</span>
  			<?php echo'<input type="time" required name="heuredebut" value="'. $heuredebut->format('H:i'). '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Fin*</span>
  			<?php echo'<input type="time" required name="heurefin" value="'. $heurefin->format('H:i'). '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Destination</span>
  			<?php echo'<input type="text" name="destination" value="'. $destination. '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Passagers*</span>
				<select class="custom-select" name="passagers" style="width: auto" required>
					<?php
					$iNombreDePlace = 0;
					while ($iNombreDePlace <= $nombrePlace -1 ) {
						if($passagers == $iNombreDePlace)
							echo '<option selected value="'. $iNombreDePlace . '">'. $iNombreDePlace . '</option>';
						else
							echo '<option value="'. $iNombreDePlace . '">'. $iNombreDePlace . '</option>'; 
						$iNombreDePlace++;
					}


					
					?>
				</select>
				
		</div>
		<br>
		<h4><i class="glyphicon glyphicon-ok"></i> En réservant ce véhicule, j'accepte les <a href="conditions.php">conditions d'utilisations</a>.<br></h4>
		<?php
		if(($liste['DroitVehicule'] != 0) or ($VerifReservationAppartientUserConnect and date('Y-m-d') <= $idateHeureDebut))
			echo'<br><button type="submit" name="Valider" class="btn btn-primary">
				<i class="glyphicon glyphicon-ok-sign"></i> Modifier
			</button>';?>
		<br><br>
		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		</form>
		<br>

		<!--BOUTON RETOUR
		<?php
		if(!isset($_POST["Valider"]))
			$_SESSION['nbEnvoi'] = 1;
		if(isset($_POST["Valider"]))
		{
			$_SESSION['nbEnvoi'] += 1;
			$back = $_SESSION['nbEnvoi'];
			echo '<button class="btn btn-primary" onclick="javascript:window.history.go(-' . $back . ')"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>';
		}
		else
			echo '<button class="btn btn-primary" onclick="javascript:history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>';
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