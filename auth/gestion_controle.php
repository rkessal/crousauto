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
	<title>Gestion des Types de contrôles</title>

  	<?php
	require_once('bdd.php');
	include("menu.php")
?>
</head>
<body><div id="top-margin" class="top-margin">

<h1>GESTION DES TYPES DE CONTRÔLE</h1>
<br>
<a class="btn btn-primary" href="gestion_type_entretien.php"><div class="glyphicon glyphicon-wrench"></div> Types d'entretien</a>
<a class="btn btn-primary" href="gestion_type_ct.php"><div class="glyphicon glyphicon-cog"></div> Types de contrôle technique</a>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>