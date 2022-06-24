<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitConducteur'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification d'un Conducteur</title>

</head>

<?php
	include("menu.php");
?>
<body>
<div id="top-margin" class="top-margin">
<form method="POST" enctype="multipart/form-data">
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeService = $conectBDD->Service_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeService = $conectBDD->Service_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeService = $conectBDD->Service_Liste_ParProprietaire($_SESSION['NoProprietaire']);
	}
	if($_SESSION['NoProprietaire'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeProprietaire = $conectBDD->Proprietaire_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeProprietaire = $conectBDD->Proprietaire_Liste_Par_Proprietaire($_SESSION['NoProprietaire']);
	}

	if($_SESSION['NoProprietaire'] == 0)
		$listeUtilisateur = $conectBDD->User_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeUtilisateur = $conectBDD->User_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeUtilisateur = $conectBDD->User_Liste_parProprietaire($_SESSION['NoProprietaire']);
	}
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}

	
	$id = $_SESSION['id'];
	$InfosConducteur = $conectBDD->Conducteur_Infos_parId($id);
	$nom = $InfosConducteur['NomConducteur'];
	$prenom = $InfosConducteur['PrenomConducteur'];
	$adresse = $InfosConducteur['AdresseConducteur'];
	$cp = $InfosConducteur['CPConducteur'];
	$ville = $InfosConducteur['VilleConducteur'];
	$telephone = $InfosConducteur['TelephoneConducteur'];
	$portable = $InfosConducteur['PortableConducteur'];
	$service = $InfosConducteur['NomService'];
	$proprietaire = $InfosConducteur['NomProprietaire'];
?>

<h1 class="title">Modification d'un Conducteur</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		if($_FILES['document']['error'] == 0)
			$document = date('YmdHi') . '_' . basename($_FILES['document']['name']);
		else
			$document = $InfosConducteur['ScanPermis'];
		if($_POST['adresse'] == null)
			$adresse = "";
		else
			$adresse = $_POST['adresse'];
		if($_POST['cp'] == null)
			$cp = "";
		else
			$cp = $_POST['cp'];
		if($_POST['ville'] == null)
			$ville = "";
		else
			$ville = $_POST['ville'];
		if($_POST['permis'] == null)
			$permis = "";
		else
			$permis = $_POST['permis'];
		if($_POST['telephone'] == null)
			$telephone = "";
		else
			$telephone = $_POST['telephone'];
		if($_POST['portable'] == null)
			$portable = "";
		else
			$portable = $_POST['portable'];
		$service = $_POST['service'];
		if(isset($_POST['actif']))
			$actif = true;
		else
			$actif = false;

		$conectBDD = new BDD();
		$res = $conectBDD->Conducteur_Modif($id, $nom, $prenom, $actif, $adresse, $cp, $ville, $telephone, $portable, $service, $permis, $document, $_POST['utilisateur']);
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'permis/' . $document);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Conducteur à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_conducteur.php"> 
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

		if($upload)
		{
	  	?>
			<div class="alert alert-success" role="alert">
			  	Le Document a bien été enregistré.
			</div>
		<?php
		}

	}
	$id = $_SESSION['id'];
	$InfosConducteur = $conectBDD->Conducteur_Infos_parId($id);
	$nom = $InfosConducteur['NomConducteur'];
	$prenom = $InfosConducteur['PrenomConducteur'];
	$adresse = $InfosConducteur['AdresseConducteur'];
	$cp = $InfosConducteur['CPConducteur'];
	$ville = $InfosConducteur['VilleConducteur'];
	$telephone = $InfosConducteur['TelephoneConducteur'];
	$portable = $InfosConducteur['PortableConducteur'];
	$service = $InfosConducteur['NomService'];
	$permis = $InfosConducteur['PermisConducteur'];
	$proprietaire = $InfosConducteur['NomProprietaire'];
	$file = $InfosConducteur['ScanPermis'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nom*</span>
  			<?php echo '<input type="text" name="nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $nom . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Prenom*</span>
  			<?php echo '<input type="text" name="prenom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $prenom . '">'; ?>
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Numéro de Permis</span>
  			<?php echo '<input type="text" name="permis" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $permis . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Justificatif</span>
			<input type="file" name="document" style="width: 200px" class="file-ajust input-text" aria-describedby="basic-addon1">
		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Adresse</span>
  			<?php echo '<input type="text" name="adresse" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $adresse . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Code Postal</span>
  			<?php echo '<input type="text" name="cp" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $cp . '">'; ?>
  		</div>
  		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Ville</span>
  			<?php echo '<input type="text" name="ville" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $ville . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Telephone</span>
  			<?php echo '<input type="text" name="telephone" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $telephone . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Portable</span>
  			<?php echo '<input type="text" name="portable" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $portable . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Service</span>
			<select class="custom-select" name="service" required style="width: 510px">
			<?php
			foreach ($listeService as $uneLigne) {
				if($InfosConducteur['NoService'] == $uneLigne['NoService'])
				{
					echo '<option selected value="'.$uneLigne['NoService'].'">'.$uneLigne['NomService'].'</option>';
				}
				else
				{
					echo '<option value="'.$uneLigne['NoService'].'">'.$uneLigne['NomService'].'</option>';
				}
			}
			?>
			</select>
		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Utilisateur*</span>
			<select class="custom-select" name="utilisateur" required style="width: 510px">
			<?php
			foreach ($listeUtilisateur as $uneLigne) {
				if($uneLigne['ActifUtilisateur'] != 0)
				{
					if($uneLigne['NoUtilisateur'] == $InfosConducteur['NoUtilisateur'])
					{
						echo '<option selected value="'.$uneLigne['NoUtilisateur'].'">'. $uneLigne['PseudoUtilisateur'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</option>';
					}
					else
					{
						echo '<option value="'.$uneLigne['NoUtilisateur'].'">'. $uneLigne['PseudoUtilisateur'] .' (Site de ' . $uneLigne['NomProprietaire'] . ')</option>';
					}
				}
			}
			?>
			</select>
		</div>
		<br>

		<?php
		if($InfosConducteur['ActifConducteur'])
		{
			?>
			<div class="custom-control custom-checkbox">
	      	  	<input  class="custom-control-input" name="actif" checked type="checkbox" id="customSwitch1">
	      	  	<label class="custom-control-label" for="customSwitch1"> Conducteur actif</label>
			</div>
		        
			<?php
		}
		else
		{
			?>
			<div class="custom-control custom-checkbox">
	      	  	<input  class="custom-control-input" name="actif" type="checkbox" id="customSwitch1">
	      	  	<label class="custom-control-label" for="customSwitch1"> Conducteur actif</label>
			</div>

			<?php
		}
		?>
		<br><br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button>
		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		<br>
		</form>
		<?php
		if(!isset($_POST["Valider"]))
			$_SESSION['nbEnvoi'] = 1;
		if(isset($_POST["Valider"]))
		{
			$_SESSION['nbEnvoi'] += 1;
			$back = $_SESSION['nbEnvoi'];
			echo '<button class="btn btn-primary" onclick="javascript:window.history.go(-' . $back . ')"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>';
			//var_dump( $_SESSION['nbEnvoi']);
		}
		else
		{
		?>
			<button class="btn btn-primary" onclick="javascript:window.history.go(-1)"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
			<?php //var_dump( $_SESSION['nbEnvoi']); 
		}
		?>
</div>
</div>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>