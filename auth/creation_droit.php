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
	<title>Création d'un Droit d'accès</title>
</head>
<body>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
?>
<div id="top-margin" class="top-margin">
<form method="POST">
<h1 class="title">Création d'un Droit d'accès</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$Nom = $_POST['Nom'];
		if(isset($_POST['vehicule']))
			$vehicule = 1;
		else
			$vehicule = 0;

		if(isset($_POST['proprietaire']))
			$proprietaire = 1;
		else
			$proprietaire = 0;

		if(isset($_POST['conducteur']))
			$conducteur = 1;
		else
			$conducteur = 0;

		if(isset($_POST['service']))
			$service = 1;
		else
			$service = 0;

		if(isset($_POST['typecontrole']))
			$typecontrole = 1;
		else
			$typecontrole = 0;

		if(isset($_POST['operation']))
			$operation = 1;
		else
			$operation = 0;

		if(isset($_POST['utilisateur']))
			$utilisateur = 1;
		else
			$utilisateur = 0;

		if(isset($_POST['reservation']))
			$reservation = 1;
		else
			$reservation = 0;
		
		$conectBDD = new BDD();
		$res = $conectBDD->Droit_Create($Nom, $vehicule, $proprietaire, $conducteur, $service, $typecontrole, $operation, $utilisateur, $reservation);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Droit à bien été ajouté.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_droit.php">
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce nom de droit est déjà utilisé
			</div>
			<?php
		}
	}
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nom</span>
  			<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
  		<br>
  		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="vehicule" id="customSwitch1">
				<label class="custom-control-label" for="customSwitch1"> Accès à la gestion des Véhicules</label>
		</div>
		<br>
  		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="reservation" id="customSwitch2">
				<label class="custom-control-label" for="customSwitch2"> Accès à la réservation des Véhicules uniquement</label>

		</div>
  		<br>
  		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="conducteur" id="customSwitch3">
				<label class="custom-control-label" for="customSwitch3"> Accès à la gestion des Conducteurs</label>

		</div>
		<br>
  		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="service" id="customSwitch4"> <label class="custom-control-label" for="customSwitch4"> Accès à la gestion des Services</label>
		</div>
		<br>
  		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="utilisateur" id="customSwitch5"> <label class="custom-control-label" for="customSwitch5"> Accès à la gestion des Utilisateurs</label>
		</div>

		<br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button>
		<br>
		<?php
		if(!isset($_POST["Valider"]))
			$_SESSION['nbEnvoi'] = 0;
		if(isset($_POST["Valider"]))
		{
			$_SESSION['nbEnvoi'] += 1;
			$back = 1 + $_SESSION['nbEnvoi'];
			echo '<button class="btn btn-primary" onclick="javascript:window.history.go(-' . $back . ')"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>';
		}
		else
			echo '<button class="btn btn-primary" onclick="javascript:history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>';
		?>

</form>

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>