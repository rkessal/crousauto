<!--<?php @session_start();
if(isset($_SESSION["connected"]))
{

		header("Location: accueil.php");

 	
}
/*else
	header("Location: connexion.php");*/
?>
-->
<!-- SHIBOULET -->

<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
  	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  	<meta charset="utf-8">


<?php
require_once('bdd.php');
/*echo 'provider';
var_dump($_SERVER['provider']);
echo '<br>eppn';
var_dump($_SERVER['eppn']);
echo '<br>displayName';
var_dump($_SERVER['displayName'] );
echo '<br>mail';
var_dump($_SERVER["mail"]);
echo '<br>uid';
var_dump($_SERVER['uid'] );
echo '<br>supannEtablissement';
var_dump($_SERVER['supannEtablissement']);*/

	// IDENTIFICATION SHIBOULET
	$mail = @$_SERVER["eppn"]; // récupération du mail de l'utilisateur
	$conectBDD = new BDD();
	$resRecherchePseudo = $conectBDD->Profil_Recherche($mail);

	if($resRecherchePseudo)
	{
		$_SESSION['provider'] = $_SERVER["Shib-Identity-Provider"]; // récupération de l'idp de l'utilisateur
		$_SESSION['eppn'] = $_SERVER["eppn"]; // récupération de l'eppn de l'utilisateur
		$_SESSION['displayName'] = $_SERVER["displayName"]; // récupération du nom/prénom de l'utilisateur
		$_SESSION['mail'] = $_SERVER["mail"]; // récupération du mail de l'utilisateur
		$_SESSION['uid'] = $_SERVER["uid"]; // récupération de l'uid de l'utilisateur
		$_SESSION['supannEtablissement'] = $_SERVER["supannEtablissement"]; // récupération de l'uid de l'etablissement
		$_SESSION['pseudo'] = $mail;
		$_SESSION['connected'] = true;
		/*echo '<br>';
		var_dump($_SESSION['pseudo']);
		echo '<br>';
		var_dump($_SESSION['connected']);*/
		header("Location: accueil.php");
	}
	else
	{
		echo '<br><div class="alert alert-danger" role="alert"> 
				<center><h1><b>Accès Refusé</b></h1><br>
				<a class="btn btn-primary" href="../calendrier.php"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</a></center></div>';
		if($_SERVER['SERVER_ADDR'] == '10.245.196.35')
			echo '<center><a class="btn btn-link" href="connexion.php">Connexion locale</a></center>';
	}

?>