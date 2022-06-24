<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitUtilisateur'] == 0 and $_SESSION['NoResidence'] != 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification d'un Site</title>

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
<form method="POST">


<h1>Modification d'un Site</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$Nom = $_POST['Nom'];
		$InfosProprietaire = $conectBDD->Proprietaire_Infos_parId($id);

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

		$res = $conectBDD->Proprietaire_Modif($id, $Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, $_POST['residence']);

		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Site à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_proprietaire.php"> 
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce nom de Site est déjà utilisé
			</div>
			<?php
		}

	}

	
	$InfosProprietaire = $conectBDD->Proprietaire_Infos_parId($id);
	$nom = $InfosProprietaire['NomProprietaire'];
	$adresse = $InfosProprietaire['AdresseProprietaire'];
	$ville = $InfosProprietaire['VilleProprietaire'];
	$cp = $InfosProprietaire['CPProprietaire'];
	$telephone = $InfosProprietaire['TelephoneProprietaire'];
	$fax = $InfosProprietaire['FaxProprietaire'];

	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Residence_Liste();
	else
		$listeProprietaire = $conectBDD->Residence_Liste_Par_Residence($_SESSION['NoResidence']);
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nom</span>
  			<?php echo '<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $nom . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Adresse</span>
  			<?php echo '<input type="text" name="Adresse" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $adresse . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Code Postal</span>
  			<?php echo '<input type="text" name="CP" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $cp . '">'; ?>
  		</div>
		<br>
				<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Ville</span>
  			<?php echo '<input type="text" name="Ville" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $ville . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Téléphone</span>
  			<?php echo '<input type="text" name="Telephone" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $telephone . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Fax</span>
  			<?php echo '<input type="text" name="Fax" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $fax . '">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">

			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Résidence Administrative*</span>
			<select class="custom-select" name="residence" style="width: 510" required>
				<?php
				foreach ($listeProprietaire as $uneLigne) {
					if($InfosProprietaire['NoResidence'] == $uneLigne['NoResidence'])
						echo '<option selected value="'.$uneLigne['NoResidence'].'">'.$uneLigne['NomResidence'].'</option>';
					else
						echo '<option value="'.$uneLigne['NoResidence'].'">'.$uneLigne['NomResidence'].'</option>';
				}
				?>
			</select>
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