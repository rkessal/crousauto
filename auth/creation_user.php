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
	<title>Création d'un Utilisateur</title>

</head>

<?php
	include("menu.php");
?>
<body>
<div id="top-margin" class="top-margin">
<form method="POST">
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	$listeDroit = $conectBDD->Droit_Liste();
	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeProprietaire = $conectBDD->Proprietaire_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeProprietaire = $conectBDD->Proprietaire_Liste_Par_Proprietaire($_SESSION['NoProprietaire']);
	}
?>

<h1 class="title">Création d'un Utilisateur</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$pseudo = $_POST['pseudo'];
		$pass = "secret";
		if(isset($_POST['actif']))
			$actif = true;
		else
			$actif = false;
		$droit = $_POST['droit'];
		$proprietaire = $_POST['proprietaire'];

		$conectBDD = new BDD();
		$pass = 0;
		$res = $conectBDD->User_Create($pseudo, $pass, $droit, $actif, $proprietaire);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	L'utilisateur à bien été ajouté.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_user.php">
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce pseudo est déjà utilisé
			</div>
			<?php
		}
	}
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Pseudo</span>
  			<input type="email" name="pseudo" required class=" form-control input-text" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Droit de l'utilisateur</span>
			<select class="custom-select" name="droit" required style="width: 310px" >
			<?php
			foreach ($listeDroit as $uneLigne) {
				echo '<option value="'.$uneLigne['NoDroit'].'">'.$uneLigne['NomDroit'].'</option>';
			}
			?>
			</select>
		</div>
		<br><br>
		
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Résidence Administrative</span>
			<select class="dropdown" name="proprietaire" required style="width: 310px" >
			<?php
			foreach ($listeProprietaire as $uneLigne) {
				echo '<option value="'.$uneLigne['NoProprietaire'].'">'. $uneLigne['NomResidence'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</option>';
			}
			?>
			</select>
		</div>
		<br>
		<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="actif" id="customSwitch1" checked>
				<label class="custom-control-label" for="customSwitch1"> Utilisateur actif</label>
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