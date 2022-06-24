<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: index.php');
}

	/*var_dump($id);
	var_dump($date);
	var_dump($km);
	var_dump($observations);
	var_dump($document);
	var_dump($entretien);
	var_dump($element);
	var_dump($ok);
	var_dump($verif);
	*/
?>
<!DOCTYPE html>
<html>
<head>
	<title>Saisie de l'entretien d'un Véhicule</title>

  	<?php
  	if (isset($_POST['EntretienModif'])) 
	{
		$_SESSION['EntretienModif'] = $_POST['EntretienModif'];		
	}
	$entretienModif = $_SESSION['EntretienModif'];

	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();

	$listeEntretien = $conectBDD->Entretien_Liste();
	$infoEntretien = $conectBDD->Entretien_Liste_parIdEntretien($entretienModif);
	$datePasserEntretien = $infoEntretien['DatePassageEntretien'];
	$kilometrageEntretien = $infoEntretien['Kilometrage'];
	$observationsEntretien = $infoEntretien['Observations'];
	$documentEntretien = $infoEntretien['Document'];
	$typeEntretien = $infoEntretien['NoEntretien'];
	
	/*var_dump($infoEntretien);
	var_dump($datePasserEntretien);
	var_dump($infoEntretien['ImmatriculationVehicule']);*/
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST" enctype="multipart/form-data">
	<?php
	$id = $_SESSION['ImmatSaisi'];
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$immatriculation = $InfosVehicule['ImmatriculationVehicule'];
	$date1Immat = $InfosVehicule['DatePremiereImmatriculationVehicule'];
	$constructeur = $InfosVehicule['ConstructeurVehicule'];
	$modele = $InfosVehicule['ModeleVehicule'];
	$couleur = $InfosVehicule['CouleurVehicule'];
	$carburant = $InfosVehicule['TypeCarburantVehicule'];
	$proprietaire = $InfosVehicule['NomProprietaire'];



	$id = $_SESSION['ImmatSaisi'];

?>
<h1>Modification de l'entretien d'un Véhicule</h1>
<br>

	<?php
	if(isset($_POST["Valider"]))
	{
		$date = $_POST['date'];
		$km = (int)$_POST['km'];
		var_dump($_FILES['document']['name']);
		$observations = $_POST['observations'];
		$infoEntretien = $conectBDD->Entretien_Liste_parIdEntretien($entretienModif);
		if($_FILES['document']['error'] == 0)
			$document = date('YmdHi') . '_' .  basename($_FILES['document']['name']);
		else
			$document = $infoEntretien['Document'];
		$entretien = (int)$_POST['entretien'];
		$element = $infoEntretien['NoElement'];


		$res = $conectBDD->Passer_Entretien_Modifier($date, $km, $observations, $document, $entretien, $element, $entretienModif, $_POST['montant']);
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'doc/' . $document);
			 
		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	L'entretien a bien été enregistré'.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_vehicule.php"> 
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
			  	Le Document a bien été ajouté ou modifié.
			</div>
		<?php
		}

		/*var_dump($res);
		var_dump($id);
		var_dump($date);
		var_dump($km);
		var_dump($observations);
		var_dump($document);
		var_dump($entretien);
		var_dump($element);
		var_dump($ok);
		var_dump($verif);*/
		
		
	}	
	if(isset($_POST["Effacer"]))
	{

		$res = $conectBDD->Passer_Entretien_Delete($entretienModif);

		if($res)
		{
			?>
			<div class="alert alert-warning" role="alert">
			  	L'opération a bien été supprimé'.
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
	?>

<table class="table"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Propriétaire</th></tr></tr>
	<?php
		echo '';
		echo '<tr><th>'.$InfosVehicule['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$InfosVehicule['ConstructeurVehicule'].' - ';
		echo  $InfosVehicule['ModeleVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['CouleurVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $InfosVehicule['NomProprietaire'].'</td>';
		echo '</table>';

	$infoEntretien = $conectBDD->Entretien_Liste_parIdEntretien($entretienModif);
	$datePasserEntretien = $infoEntretien['DatePassageEntretien'];
	$kilometrageEntretien = $infoEntretien['Kilometrage'];
	$observationsEntretien = $infoEntretien['Observations'];
	$documentEntretien = $infoEntretien['Document'];
	$typeEntretien = $infoEntretien['NoEntretien'];
	$montantEntretien = $infoEntretien['MontantEntretien'];
?>

	<form method="post" action="modif_entretien_vehicule.php">
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Date*</span>
  			<?php echo '<input type="text" name="date" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $datePasserEntretien . '" id="datepicker" autocomplete="off">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Kilométrage*</span>
			<input class="input-text form-control" type="text" name="km" required style="width: 200px" aria-describedby="basic-addon1"
			<?php echo 'value = "'. $kilometrageEntretien.'"' ?>>  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Observations</span>
			<input class="input-text form-control" type="text" name="observations" style="width: 200px" aria-describedby="basic-addon1" <?php echo 'value = "'. $observationsEntretien.'"' ?>>  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Document à joindre</span>
				<input class="file-ajust input-text" type="file" name="document" style="width: 200px" aria-describedby="basic-addon1">
	 		
		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Type d'entretien*</span>
			<select class="custom-select" name="entretien" required>
				<?php
				foreach ($listeEntretien as $uneLigne) {
					echo '<option value="'.$uneLigne['NoEntretien'].'">'.$uneLigne['TypeEntretien'].'</option>';
				}
				?>
			</select>
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Montant (en €)*</span>
			<input class="input-text form-control" type="text" name="montant" style="width: 200px" required="" aria-describedby="basic-addon1" <?php echo 'value = "'. $montantEntretien.'"' ?>>  		</div>
		<br>
		<br>


		<div class="input-group" style="text-align: center">
			<h1>Opération effectué : </h1>
		</div>
		<br>
		<?php
		if($infoEntretien['LibelleElement'] != null)
			echo '<h3 style="display:inline-block">' . $infoEntretien['LibelleElement'] . '</h3><button type="submit" name="Effacer" class="btn btn-danger">
			Supprimer (irreversible)</button>
			<br><br>
			<button type="submit" name="Valider" class="btn btn-primary">
				<i class="glyphicon glyphicon-ok-sign"></i> Valider
			</button><br><br>';
		else
			echo'<h3 style="display:inline-block"><b>Aucune</b></h3>';
		?>

	</form>
		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		<br>
		<?php
		if(!isset($_POST["Valider"]) and !isset($_POST["Effacer"]))
			$_SESSION['nbEnvoi'] = 0;
		if(isset($_POST))
		{
			$_SESSION['nbEnvoi'] += 1;
			$back = $_SESSION['nbEnvoi'];
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