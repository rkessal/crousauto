<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Modification d'un Badge de Télépéage</title>

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
	if($liste['NoDroit'] == 1)
		$listeVehicule = $conectBDD->Vehicule_Liste_SiPasBadgeT($_SESSION['NoResidence']);
	else
		$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion_SiPasBadgeT($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	$id = $_SESSION['id'];

?>

<h1 class="title">Modification d'un Badge de Télépéage</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$nom = $_POST['nom'];
		$vehicule = $_POST['vehicule'];

		$conectBDD = new BDD();
		$res = $conectBDD->Telepeage_Modif($id, $nom, $vehicule, $_POST['fournisseur'], $_POST['abonnement']);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Badge de Télépéage a bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_telepeage.php"> 
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Cette Carte Essence existe déjà.
			</div>
			<?php
		}
	}
	$id = $_SESSION['id'];
	$infosTelepeage = $conectBDD->Telepeage_Infos_parId($id);
	$nom = $infosTelepeage['NomTelepeage'];
	$fournisseur = $infosTelepeage['FournisseurTelepeage'];
	$abonnement = $infosTelepeage['AbonnementTelepeage'];
	
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Numéro*</span>
  			<?php echo '<input type="text" name="nom" required style="width: 200px" class="input-text form-control" value="' . $nom . '" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Fournisseur*</span>
  			<?php echo '<input type="text" name="fournisseur" required style="width: 200px" class="input-text form-control" value="' . $fournisseur . '" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Abonnement*</span>
  			<?php echo '<input type="text" name="abonnement" required style="width: 200px" class="input-text form-control" value="' . $abonnement . '" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Vehicule*</span>
			<select class="custom-select" style="width: 500px" name="vehicule" required>
				<option value="0">Badge Innactif</option>
			<?php
			echo '<option selected value="'.$infosTelepeage['ImmatriculationVehicule'].'">'.$infosTelepeage['ImmatriculationVehicule']. ' - ' . $infosTelepeage['ConstructeurVehicule'] .' - ' . $infosTelepeage['ModeleVehicule'] . ' - ' . $infosTelepeage['CouleurVehicule'] . ' - ' . $infosTelepeage['CarburantVehicule'] . '</option>';
			foreach ($listeVehicule as $uneLigne) {
				echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . ' - ' . $uneLigne['CouleurVehicule'] . ' - ' . $uneLigne['CarburantVehicule'] . '</option>';
			}
			?>
			</select>
		</div>

		<br><br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button></form>
		<br>
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
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>