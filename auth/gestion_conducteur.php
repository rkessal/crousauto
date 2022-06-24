<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitConducteur'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des conducteurs</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeConducteur = $conectBDD->Conducteur_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeConducteur = $conectBDD->Conducteur_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeConducteur = $conectBDD->Conducteur_Liste_ParProprietaire($_SESSION['NoProprietaire']);
	}
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES CONDUCTEURS <a class="btn btn-primary" href="creation_conducteur.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Conducteur</p></a></h1>
<table class="table"><tr><th scope="col">Nom</th><th scope="col">Prénom</th><th scope="col">Permis</th><th scope="col">Adresse</th><th scope="col">Téléphone</th><th scope="col">Utilisateur</th><th scope="col">Résidence Administrative</th><th scope="col">Service</th><th scope="col">Actif</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeConducteur as $uneLigne) {
		echo '';
		$doc = $uneLigne['ScanPermis'];
		echo '<tr><th scope="row">'.$uneLigne['NomConducteur'].'</th>';
		echo '<th scope="row">'.$uneLigne['PrenomConducteur'].'</th>';
		echo '<td>'.$uneLigne['PermisConducteur'].'<br>';
		if($uneLigne['ScanPermis'] != null)
			echo '<a href="permis/' . $doc . '" target="_blank">Justificatif</a></td>';
		echo '<td>'.$uneLigne['AdresseConducteur'].'<br>';
		echo $uneLigne['CPConducteur']. ' ' . $uneLigne['VilleConducteur'] . '</td>';
		echo '<td>'.$uneLigne['TelephoneConducteur'].'<br>';
		echo $uneLigne['PortableConducteur'].'</td>';
		echo '<td>'.$uneLigne['PseudoUtilisateur'].'</td>';
		echo '<td>' . $uneLigne['NomResidence'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</td>';
		echo '<td>'.$uneLigne['NomService'].'</td>';

		if($uneLigne['ActifConducteur'])
			echo '<td><center><h2><span class="badge badge-success">Oui</span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger">Non</span></h2></center></td>';
		$id = $uneLigne['NoConducteur'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_conducteur.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
			</form>
		</td></tr>';
	}
	echo '</table>';

?>


</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>