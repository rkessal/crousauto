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
	<title>Modification d'un Crous</title>

</head>
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
<body>
	<div id="top-margin" class="top-margin">
<form method="POST" enctype="multipart/form-data">


<h1>Modification d'un Crous</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$Nom = $_POST['Nom'];
		$InfosProprietaire = $conectBDD->Residence_Infos_parId($id);
		if($_FILES['document']['error'] == 0)
			$document = date('YmdHi') . '_' . basename($_FILES['document']['name']);
		else
			$document = $InfosProprietaire['LogoResidence'];
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

		$res = $conectBDD->Residence_Modif($id, $Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, $document);
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'logo/' . $document);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Crous à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_residence.php"> 
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
			  	Le Logo a bien été ajouté ou modifié.
			</div>
		<?php
		}
	}

	
	$InfosProprietaire = $conectBDD->Residence_Infos_parId($id);
	$nom = $InfosProprietaire['NomResidence'];
	$adresse = $InfosProprietaire['AdresseResidence'];
	$ville = $InfosProprietaire['VilleResidence'];
	$cp = $InfosProprietaire['CPResidence'];
	$telephone = $InfosProprietaire['TelephoneResidence'];
	$fax = $InfosProprietaire['FaxResidence'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Nom</span>
  			<?php echo '<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $nom . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Adresse</span>
  			<?php echo '<input type="text" name="Adresse" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $adresse . '">'; ?>
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Code Postal</span>
  			<?php echo '<input type="text" name="CP" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $cp . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Ville</span>
  			<?php echo '<input type="text" name="Ville" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $ville . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Téléphone</span>
  			<?php echo '<input type="text" name="Telephone" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $telephone . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Fax</span>
  			<?php echo '<input type="text" name="Fax" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $fax . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Logo</span>
			<input class="file-ajust input-text" type="file" name="document" style="width: 200px" aria-describedby="basic-addon1">
		</div>
		<br>


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

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>