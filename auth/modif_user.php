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
	<title>Modification d'un Utilisateur</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	
	$id = $_SESSION['id'];
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
	$InfosUser = $conectBDD->User_Infos_parId($id);

?>
</head>
<body>
<form method="POST">

<div id="top-margin" class="top-margin">
<h1 class="title">Modification d'un Utilisateur</h1>
<br>
	<?php
	
	if(isset($_POST["Valider"]))
	{
		$pseudo = $_POST['pseudo'];
		if(isset($_POST['actif']))
			$actif = true;
		else
			$actif = false;
		$droit = $_POST['droit'];
		$proprietaire = $_POST['proprietaire'];

		$conectBDD = new BDD();
		$res = $conectBDD->User_Modif($InfosUser['NoUtilisateur'], $pseudo, $droit, $actif, $proprietaire);
		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	L'utilisateur à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_user.php"> 
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Erreur
			</div>
			<?php
		}
	}
	if(isset($_POST["ReiniMdP"]))
	{
		$pass = password_hash("secret",PASSWORD_DEFAULT);
		$Reini = $conectBDD->User_ReiniMdp_parId($id, $pass);
		if($Reini!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le mot de passe a bien été réinitialisé.
			</div>
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Erreur
			</div>
			<?php
		}
	}
	$InfosUser = $conectBDD->User_Infos_parId($id);
	$pseudo = $InfosUser['PseudoUtilisateur'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Pseudo</span>
  			<?php echo '<input type="email" name="pseudo" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $pseudo . '">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Droit de l'utilisateur</span>
			<select class="custom-select" name="droit" required style="width: 310px">
			<?php
			foreach ($listeDroit as $uneLigne) {
				if($InfosUser['NoDroit'] == $uneLigne['NoDroit'])
				{
					echo '<option selected value="'.$uneLigne['NoDroit'].'">'.$uneLigne['NomDroit'].'</option>';
				}
				else
				{
					echo '<option value="'.$uneLigne['NoDroit'].'">'.$uneLigne['NomDroit'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 200px" id="basic-addon1">Résidence Administrative</span>
			<select class="custom-select" name="proprietaire" required style="width: 310px">
			<?php
			foreach ($listeProprietaire as $uneLigne) {
				if($InfosUser['NoProprietaire'] == $uneLigne['NoProprietaire'])
				{
					echo '<option selected value="'.$uneLigne['NoProprietaire'].'">'. $uneLigne['NomResidence'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</option>';
				}
				else
				{
					echo '<option value="'.$uneLigne['NoProprietaire'].'">'. $uneLigne['NomResidence'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</option>';
				}
			}
			?>
			</select>
		</div>

		<br>
		<?php
		if($InfosUser['ActifUtilisateur'])
		{
		?>
			<div class="custom-control custom-checkbox">
	      	  	<input  class="custom-control-input" name="actif" checked type="checkbox" id="customSwitch1">
	      	  	<label class="custom-control-label" for="customSwitch1"> Utilisateur actif</label>
			</div>
		<?php
		}
		else
		{
		?>
			<div class="custom-control custom-checkbox">
	      	  	<input  class="custom-control-input" name="actif" type="checkbox" id="customSwitch1">
	      	  	<label class="custom-control-label" for="customSwitch1"> Utilisateur actif</label>
			</div>
		<?php
		}
		?>

		<br>
		<button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button>
		</form>
		
		<?php
		/*if($_SESSION['NoProprietaire'] == 0)
		{
			?>
			<br><br>
			<form method="post">
				<input class="btn btn-warning" style="width:220px" type="submit" value="Réinitaliser mot de passe" name="ReiniMdP">
			</form>
			<?php
		}*/
		?>
		
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

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>