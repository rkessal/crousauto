<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitService'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des Services</title>

</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeService = $conectBDD->Service_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeService = $conectBDD->Service_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeService = $conectBDD->Service_Liste_ParProprietaire($_SESSION['NoProprietaire']);
	}
?>
<body>
<div id="top-margin" class = "top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES SERVICES <a class="btn btn-primary" href="creation_service.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Service</p></a></h1>
<table class="table vehicules"><tr><th scope="col">Nom</th><th scope="col">Résidence Administrative</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeService as $uneLigne) {
		echo '';
		echo '<tr><th scope="row">'.$uneLigne['NomService'].'</th>';
		echo '<td>' . $uneLigne['NomResidence'] . ' (Site de '.$uneLigne['NomProprietaire'].')</td>';
		$id = $uneLigne['NoService'];
		echo '<td> &nbsp
		<form style="display: inline-block;" method="post" action="modif_service.php">
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