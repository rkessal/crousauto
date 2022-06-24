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
	<title>Création d'un Badge de Télépéage</title>

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
	if($_SESSION['NoProprietaire'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeVehicule = $conectBDD->Vehicule_Liste_SiPasBadgeT($_SESSION['NoResidence']);
		else
			$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion_SiPasBadgeT($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
?>

<h1 class="title">Création d'un Badge de Télépéage</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$nom = $_POST['nom'];
		$vehicule = $_POST['vehicule'];

		$conectBDD = new BDD();
		$res = $conectBDD->Telepeage_Create($nom, $vehicule, $_POST['fournisseur'], $_POST['abonnement']);
		$infosCarte = $conectBDD->Telepeage_Infos_parNom($nom);
		$res2 = $conectBDD->Utiliser_Telepeage_Create($infosCarte['NoTelepeage'], '1900-01-01', 0);
		if($res2!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Badge Télépéage à bien été ajouté.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_telepeage.php">
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce Badge existe déjà.
			</div>
			<?php
		}
	}
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Numero*</span>
  			<input type="text" name="nom" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Fournisseur*</span>
  			<input type="text" name="fournisseur" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Formule d'Abonnement*</span>
  			<input type="text" name="abonnement" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Vehicule*</span>
			<select class="custom-select" style="width: 500px" name="vehicule" required>
				<option value="0">Badge Innactif</option>
			<?php 
			foreach ($listeVehicule as $uneLigne) {
				if($_SESSION['ImmatSaisi'] == $uneLigne['ImmatriculationVehicule'])
				echo '<option selected value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . ' - ' . $uneLigne['CouleurVehicule'] . ' - ' . $uneLigne['CarburantVehicule'] . '</option>';
				else
					echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['ImmatriculationVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' - ' . $uneLigne['ModeleVehicule'] . ' - ' . $uneLigne['CouleurVehicule'] . ' - ' . $uneLigne['CarburantVehicule'] . '</option>';
			}
			?>
			</select>
		</div>

		<br><br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
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