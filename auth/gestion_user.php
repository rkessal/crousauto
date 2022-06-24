<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitUtilisateur'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des utilisateurs</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeUser = $conectBDD->User_Liste();
	else
		$listeUser = $conectBDD->User_Liste_parResidence($_SESSION['NoResidence']);
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES UTILISATEURS<a class="btn btn-primary" href="creation_user.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Utilisateur</p></a> </h1>
<table class="table"><tr><th scope="col">Pseudo</th><th scope="col">Résidence Administrative</th><th scope="col">Actif</th><th scope="col">Droit</th><th scope="col">Actions</th></tr>
<?php
	foreach ($listeUser as $uneLigne) {
		echo '';
		echo '<tr><th scope="row">'.$uneLigne['PseudoUtilisateur'].'</th>';
		echo '<td>' . $uneLigne['NomResidence'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</td>';
		if($uneLigne['ActifUtilisateur'])
			echo '<td><h2><span class="badge badge-success">Oui</span></h2></td>';
		else
			echo '<td><h2><span class="badge badge-danger">Non</span></h2></td>';
		echo '<td>'.$uneLigne['NomDroit'] . ' de ' .  $uneLigne['NomResidence'] . '</td>';
		$id = $uneLigne['NoUtilisateur'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_user.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
			</form>';
		echo '&nbsp
			<form style="display: inline-block;" method="post" action="vehicule_user.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<button type="submit" name="Gestion_vehicule" class="btn btn-primary">
				<i class="glyphicon glyphicon-check"></i> Gérer l\'utilisation des Véhicules
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