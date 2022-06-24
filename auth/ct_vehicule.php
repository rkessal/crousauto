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
	<title>Saisie du contrôle technique d'un Véhicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeControle = $conectBDD->Controle_Technique_Liste();
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

?>
<h1>Saisie du contrôle technique d'un Véhicule</h1>
<br>

	<?php
	if(isset($_POST["Valider"]))
	{
		$ok = $_POST['statut'];
		$date = $_POST['date'];
		$km = (int)$_POST['km'];
		if(isset($_FILES['document']))
			$document = date('YmdHi') . '_' . basename($_FILES['document']['name']);
		$controle = $_POST['controle'];

		$controle = (int)$controle;



		/*var_dump($id);
		var_dump($date);
		var_dump($km);
		var_dump($document);
		var_dump($controle);
		var_dump($ok);
		*/
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'doc/' . $document);
		if(!$upload)
			$document = null;
		$res = $conectBDD->Passer_Controle_Technique_Create($id, $date, $km, $document, $controle, $ok, $_POST['montant']);
		
		//var_dump($upload);
		//var_dump($document);

		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le contrôle technique a bien été enregistré.
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

		if($upload)
		{
	  	?>
			<div class="alert alert-success" role="alert">
			  	Le Document a bien été enregistré.
			</div>
		<?php
		}
		else
		{
	  	?>
			<div class="alert alert-danger" role="alert">
			  	Aucun Document enregistré.
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

?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Date*</span>
  			<input type="text" name="date" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1"  id="datepicker" autocomplete="off" readonly placeholder="Sélectionnez une date">
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Kilométrage*</span>
			<input class="input-text form-control" type="text" name="km" required style="width: 200px" aria-describedby="basic-addon1">  
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Document à joindre</span>
			<input class="file-ajust input-text" type="file" name="document" style="width: 200px" aria-describedby="basic-addon1">
		</div>
		<br>
	
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Type de contrôle</span>
				<select class="custom-select" name="controle" style="width: auto" required>
				<?php
				foreach ($listeControle as $uneLigne) {
					if($uneLigne['NoControle'] != 0 or $InfosVehicule['TypeVehicule'] == 'Vehicule frigorifique')
					echo '<option value="'.$uneLigne['NoControle'].'">'.$uneLigne['TypeControle'].'</option>';
				}
				?>
			</select>
		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Statut</span>
				<select class="custom-select" name="statut" style="width: auto" required>
				<option value="1">Accepté</option>
				<option value="0">Ajourné</option>
			</select>
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Montant (en €)*</span>
			<input class="input-text form-control" required="" type="text" name="montant" required style="width: 200px" aria-describedby="basic-addon1">  
		</div>
		<br>

		<br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button><br><br>

		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
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