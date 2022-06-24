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
	<title>Modification d'un Service</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	$id = $_SESSION['id'];
?>
</head>
<body>

<div id="top-margin" class="top-margin">
<form method="POST">
<h1 class="title">Modification d'un Service</h1>
<br>
	<?php
	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeProprietaire = $conectBDD->Proprietaire_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeProprietaire = $conectBDD->Proprietaire_Liste_Par_Proprietaire($_SESSION['NoProprietaire']);
	}

	if(isset($_POST["Valider"]))
	{
		$Nom = $_POST['Nom'];
		$res = $conectBDD->Service_Modif($id, $Nom, $_POST['proprietaire']);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Service à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_service.php"> 
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce nom de service est déjà utilisé
			</div>
			<?php
		}
	}
	$InfosService = $conectBDD->Service_Infos_parId($id);
	$nom = $InfosService['NomService'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Nom</span>
  			<?php echo '<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $nom . '">'; ?>
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Site*</span>
			<select class="custom-select" name="proprietaire" required>
				<?php
				foreach ($listeProprietaire as $uneLigne) {
					if($uneLigne['NoProprietaire'] == $InfosService['NoProp'])
						echo '<option selected value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
					else
						echo '<option value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
				}
				?>
			</select>
		</div>
		<br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button></form>
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