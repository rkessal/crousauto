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
	<title>Création d'un Crous</title>
	
</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
?>
<body>
	<div id="top-margin" class="top-margin">
<form method="POST" enctype="multipart/form-data">


<h1>Création d'un Crous</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		if(isset($_FILES['document']))
			$document = basename($_FILES['document']['name']);
		$Nom = $_POST['Nom'];
		$Adresse = $_POST['Adresse'];
		$Ville = $_POST['Ville'];
		$CP = $_POST['CP'];
		$Telephone = $_POST['Telephone'];
		$Fax = $_POST['Fax'];

		if (!isset($_POST['Telephone']))
		{
			$Telephone = " ";
		}

		if (!isset($_POST['Fax']))
		{
			$Fax = " ";
		}

		$conectBDD = new BDD();
		$res = $conectBDD->Residence_Create($Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, date('YmdHi') . '_' . $document);
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'logo/' . date('YmdHi') . '_' . $document);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Crous à bien été ajouté.
			</div>
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce nom de Crous est déjà utilisé
			</div>
			<?php
		}

		if($upload)
		{
	  	?>
			<div class="alert alert-success" role="alert">
			  	Le Logo a bien été enregistré.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_residence.php">
		<?php
		}
		else
		{
	  	?>
			<div class="alert alert-danger" role="alert">
			  	Aucun Logo enregistré.
			</div>
		<?php
		}
	}
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Nom</span>
  			<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Adresse</span>
  			<input type="text" name="Adresse" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Code Postal</span>
  			<input type="text" name="CP" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Ville</span>
  			<input type="text" name="Ville" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Téléphone</span>
  			<input type="text" name="Telephone" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Fax</span>
  			<input type="text" name="Fax" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Logo</span>
			<input class="file-ajust input-text" type="file" name="document" style="width: 200px" aria-describedby="basic-addon1">
		</div>
		<br>

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