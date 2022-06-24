<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (!$liste['DroitVehicule'])
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des Cartes Essences</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeCartes = $conectBDD->CarteEssence_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeCartes = $conectBDD->CarteEssence_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeCartes = $conectBDD->CarteEssence_Liste_ParUser_SiUtiliser($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
	
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES CARTES ESSENCES <a class="btn btn-primary" href="creation_carte_essence.php"><p><div class="glyphicon glyphicon-plus"></div> Créer une Carte Essence</p></a></h1>
<table class="table"><tr><th scope="col">Numéro</th><th scope="col">Véhicule</th><th scope="col">Fournisseur</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeCartes as $uneLigne) {
		echo '<tr><th scope="row">'.$uneLigne['NomCarte'].'</th>';
		echo '<th scope="row">'.$uneLigne['ImmatriculationVehicule'].'</th>';
		echo '<th scope="row">'.$uneLigne['FournisseurCarte'].' - expire le '.$uneLigne['DateRenouvellementCarte'].'</th>';
		$idCarte = $uneLigne['NoCarte'];
		$immat = $uneLigne['ImmatriculationVehicule'];
		echo '<td> 
			<form style="display: inline-block;" method="post" action="modif_carte_essence.php">
				<button type="submit" name="idModif" value="' . $idCarte . '" class="btn btn-primary">
					<i class="glyphicon glyphicon-pencil"></i> Modifier la Carte Essence
				</button>
			</form>
			<form style="display: inline-block;" method="post" action="liste_carte_essence.php">
				<button type="submit" value="' . $idCarte . '" name="idListe" class="btn btn-primary">
					<i class="glyphicon glyphicon-th-list"></i> Liste des Utilisations
				</button>
			</form>
			<form style="display: inline-block;" method="post" action="creation_utilisation_carte_essence.php">
				<button type="submit" name="ImmatSaisi"  value="' . $immat . '"class="btn btn-primary">
					<i class="glyphicon glyphicon-plus"></i> Saisir une Utilisation
				</button>
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