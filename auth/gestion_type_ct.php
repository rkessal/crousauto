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
	<title>Gestion des types de Contrôle Technique</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	$listeCT = $conectBDD->Controle_Technique_Liste();
?>
<div id="top-margin" class="top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES TYPES DE CONTRÔLE TECHNIQUE <a class="btn btn-primary" href="creation_type_ct.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un type de contrôle technique</p></a></h1>
<table class="table"><tr><th scope="col">Libellé</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeCT as $uneLigne) {
		echo '';
		echo '<tr><th scope="row">'.$uneLigne['TypeControle'].'</th>';
		$id = $uneLigne['NoControle'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_type_ct.php">
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