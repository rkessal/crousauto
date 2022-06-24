<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($_SESSION['NoResidence'] != 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des Droits d'Accès</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	$listeDroit = $conectBDD->Droit_Liste();
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES DROITS D'ACCES <a class="btn btn-primary" href="creation_droit.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Droit</p></a></h1>
<table class="table"><tr><th scope="col">Nom</th><th scope="col">Gestion Vehicule</th><th scope="col">Réservations Vehicule uniquement</th><th scope="col">Gestion des Types de Controle</th><th scope="col">Gestion des Sites</th><th scope="col">Gestion des Conducteurs</th><th scope="col">Gestion des Services</th><th scope="col">Gestion des Opérations</th><th scope="col">Gestion des Utilisateurs</th><th scope="col">Actions</th></tr>
<?php
	if($_SESSION['NoProprietaire'] == 0)
		echo '<tr><th scope="row">Administrateur Les Crous</th><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td><td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td></tr>';
	foreach ($listeDroit as $uneLigne) {
		echo '';
		echo '<tr><th scope="row">'.$uneLigne['NomDroit'].'</th>';
		if($uneLigne['DroitVehicule'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['ReserverVehicule'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['DroitTypeControle'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['DroitProprietaire'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2><c/enter></td>';
		if($uneLigne['DroitConducteur'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['DroitService'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['DroitOperation'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		if($uneLigne['DroitUtilisateur'])
			echo '<td><center><h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div></span></h2></center></td>';
		else
			echo '<td><center><h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div></span></h2></center></td>';
		$id = $uneLigne['NoDroit'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_droit.php">
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