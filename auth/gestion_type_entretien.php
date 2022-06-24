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
	<title>Gestion des types d'entretien</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	$listeEntretien = $conectBDD->Entretien_Liste();
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES TYPES D'ENTRETIEN <a class="btn btn-primary" href="creation_type_entretien.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un type d'entretien</p></a></h1>
<table class="table"><tr><th scope="col">Libellé</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeEntretien as $uneLigne) {
		echo '';
		echo '<tr><th scope="row">'.$uneLigne['TypeEntretien'].'</th>';
		$id = $uneLigne['NoEntretien'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_type_entretien.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
			</form>
		</td></tr>';
	}
	echo '</table>';

?>
<br><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>