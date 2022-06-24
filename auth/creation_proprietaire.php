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
	<title>Création d'un Site</title>
	
</head>
<?php
	include("menu.php");
?>
<body>
	<div id="top-margin" class="top-margin">
<form method="POST" >


<h1>Création d'un Site</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{

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

		$res = $conectBDD->Proprietaire_Create($Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, $_POST['residence']);

		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Site à bien été ajouté.
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

	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Residence_Liste();
	else
		$listeProprietaire = $conectBDD->Residence_Liste_Par_Residence($_SESSION['NoResidence']);
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nom</span>
  			<input type="text" name="Nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Adresse</span>
  			<input type="text" name="Adresse" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Code Postal</span>
  			<input type="text" name="CP" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Ville</span>
  			<input type="text" name="Ville" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Téléphone</span>
  			<input type="text" name="Telephone" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Fax</span>
  			<input type="text" name="Fax" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>

		<div class="input-group" style="text-align: center">

			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Résidence Administrative*</span>
			<select style="width: 510" class="custom-select" name="residence" required>
				<?php
				foreach ($listeProprietaire as $uneLigne) {
					echo '<option value="'.$uneLigne['NoResidence'].'">'.$uneLigne['NomResidence'].'</option>';
				}
				?>
			</select>
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