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
	<title>Gestion des Véhicules</title>
</head>
	<?php
	include("menu.php");	
	?>
<body>
<div id="top-margin" class="top-margin">
	<?php 
	require_once('bdd.php');
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
	if($liste['DroitVehicule']) 
	{
		
		echo '<h1 class="title" style="display: inline-block;">GESTION DES VEHICULES';
		
		if($liste['NoDroit'] == 1)
			echo '<a class="btn btn-primary" href="creation_vehicule.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Véhicule</p></a>';

		echo '<a class="btn btn-primary" href="gestion_reservation_vehicule.php"><p><div class="glyphicon glyphicon-check"></div> Gérer les Demandes de Réservations</p></a> </h1>';
		
	}
	else
		echo '<h1 class="title">Demander la Réservation d\'un Véhicule</h1>';
	?>

<table class="table"><tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th>Gestionnaire</th><?php 
	if($liste['DroitVehicule']) 
	{
		echo'<th scope="col">Actif</th>';
		echo'<th scope="col">Réservable</th>';
	}
?><th scope="col">Etat actuel</th><th scope="col">Action</th></tr></tr>
	<?php
	foreach ($listeVehicule as $uneLigne) {
		echo '<tr><td>'.$uneLigne['ImmatriculationVehicule'].' <span class="badge">' . $uneLigne['TypeVehicule'] . '</span></td>';
		$id = $uneLigne['ImmatriculationVehicule'];
		echo '<th>'.$uneLigne['ConstructeurVehicule'].' - ';
		echo  $uneLigne['ModeleVehicule'].' </th>';
		echo '<td>'.$uneLigne['CouleurVehicule'].'</td>';
		echo '<td>'.$uneLigne['TypeCarburantVehicule'].'</td>';
		echo '<td>' . $uneLigne['LieuVehicule'] . ' (Site de ' . $uneLigne['NomProprietaire'] . ')';
		echo '</td>'; 
		if($liste['DroitVehicule']) 
		{
			if($uneLigne['ActifVehicule'] == 1)
				echo '<td><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div> Oui</span></h2></td>';
			else
				echo '<td><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div> Non</span></h2></td>';

			if($uneLigne['LibreServiceVehicule'] == 1)
				echo '<td><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div> Oui</span></h2></td>';
			else
				echo '<td><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div> Non</span></h2></td>';
			
		}
		echo '<td>';
		$UtilisationVehicule = $conectBDD->Utilisation_Infos_parId($uneLigne['ImmatriculationVehicule']);
		$dispo = true;
		$dateNow = date('Y-m-d') . " " . date('H') . ":" . date('i') . ":" . date('s');
		foreach ($UtilisationVehicule as $dispoLigne) {
			if($dispoLigne['DateDebutUtilisation'] <= $dateNow and $dateNow <= $dispoLigne['DateFinUtilisation'])
			{
				$dispo = false;
				break;
			}
		}

		if($dispo)
			echo '<h2><span class="badge badge-success">Disponible</span></h2></td>';
		else
			echo '<h2><span class="badge badge-danger">En Déplacement</span></h2></td>';
		echo '<td>';

		if ($liste['DroitVehicule'])
		{
			echo '<form action="affichage_vehicule.php" method="POST">
			<button type="submit" name="Immat" class="btn btn-primary" value="'.$uneLigne['ImmatriculationVehicule']. '">
				<i class="glyphicon glyphicon-folder-open"></i>&nbsp Dossier
			</button> &nbsp';
		}
		else
		{
			echo '<form action="reservation_vehicule.php" method="POST">
			<button type="submit" name="Immat" class="btn btn-primary" value="'.$uneLigne['ImmatriculationVehicule']. '">
					<i class="glyphicon glyphicon-arrow-right"></i> Réserver ce Véhicule
				</button> &nbsp';
		}

	}
	echo '</td></table></form>';
?>

</div>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>