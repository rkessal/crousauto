<?php
	session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Se connecter</title>
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
  	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  	<meta charset="utf-8">
  	<link rel="stylesheet" href="css/main.css" />
</head>
<body class="col-md-6 col-md-offset-3"><center>
<form method="post" class="col-md-6 col-md-offset-3">
<?php
require_once('bdd.php');
require_once('f_ldap.php');
$conectBDD = new BDD();
$conectLDAP = new ladp();
$listeProprietaire = $conectBDD->Proprietaire_Liste();
if(isset($_SESSION['pseudo']))
	header("Location: index.php");
else
{
	if(isset($_POST["Valider"]))
	{
		$pseudo = $_POST['pseudo'];
		$pass = 0;

		$conectBDD = new BDD();
		$conectLDAP = new ladp();

		$fonctionnement = true;

		$res = $conectBDD->Profil_Connexion($pseudo, $pass);
		if($res)
		{
			$_SESSION['pseudo'] = $_POST['pseudo'];
			$_SESSION['displayName'] = $_SESSION['pseudo'];
			$_SESSION['connected'] = true;
			header("Location: index.php");
		}
		else
			$fonctionnement = false;
		
		
		if(!$fonctionnement)
		{
		?>
		<center>
			<img src="ressources/logo.png"></center>
			<h1 class="title">Connexion</h1>
			<div class="alert alert-danger" role="alert"> 
				Identifiant Incorrect</div>
		<br>
			<div class="input-group" style="text-align: center">
	  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Utilisateur</span>
	  			<input type="text" name="pseudo" required style="width: 200px" class="form-control" aria-describedby="basic-addon1">
			</div>
			<br>
			<input class="btn btn-success" style="width:220px" value="Se connecter" type="submit" name="Valider">
			<br><a class="btn btn-primary" href="../calendrier.php"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</a>
		<?php
		}
	}
	else
	{
	?>
	<center>
		<img src="ressources/logo.png"></center>
		<h1 class="title">Connexion</h1>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Utilisateur</span>
  			<input type="text" name="pseudo" required style="width: 200px" class="form-control" aria-describedby="basic-addon1">
		</div>
		<br>
		<input class="btn btn-success" style="width:220px" value="Se connecter" type="submit" name="Valider">
			<br><a class="btn btn-primary" href="../calendrier.php"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</a>
		<?php
	}
}
	?>
