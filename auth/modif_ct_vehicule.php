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
	<title>Modificaton du contrôle technique d'un Véhicule</title>

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
	if(isset($_POST['ControleModif']))
	{
		$_SESSION['ControleModif'] = $_POST['ControleModif'];
	}
	if(isset($_POST['DateModif']))
	{
		$_SESSION['DateModif'] = $_POST['DateModif'];
	}
	$date = $_SESSION['DateModif'];
	$id = $_SESSION['ImmatSaisi'];
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$controle = $_SESSION['ControleModif'];
	$InfosPassageCT = $conectBDD->Passer_Controle_Technique_Infos_parId($id, $controle, $date);
	$doc = $InfosPassageCT['DocumentControle'];
	$date = $InfosPassageCT['DatePassageControle'];
	$km = $InfosPassageCT['KilometrageControle'];

?>
<h1>Modification du contrôle technique d'un Véhicule</h1>
<br>

	<?php
	if(isset($_POST["Valider"]))
	{
		$ok = $_POST['statut'];
		$km = (int)$_POST['km'];
		if($_FILES['document']['error'] == 0)
			$document = date('YmdHi') . '_' . basename($_FILES['document']['name']);
		else
			$document = $InfosPassageCT['DocumentControle'];
		$controle = $_POST['controle'];

		$controle = (int)$controle;


		/*var_dump($id);
		var_dump($date);
		var_dump($km);
		var_dump($document);
		var_dump($controle);
		var_dump($ok);
		*/
		$res = $conectBDD->Passer_Controle_Technique_Modif($id, $date, $km, $document, $controle, $ok, $_POST['montant']);
		$upload = move_uploaded_file($_FILES['document']['tmp_name'],'doc/' . $document);

		
		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le contrôle technique a bien été modifié.
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
	}			

	$InfosPassageCT = $conectBDD->Passer_Controle_Technique_Infos_parId($id, $controle, $date);
	$doc = $InfosPassageCT['DocumentControle'];
	$date = $InfosPassageCT['DatePassageControle'];
	$km = $InfosPassageCT['KilometrageControle'];	
	$montant = $InfosPassageCT['MontantControle'];		
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

		$date = $InfosPassageCT['DatePassageControle'];
?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Date*</span>
  			<?php echo '<input type="date" disabled name="Date" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $date . '" >'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Kilométrage*</span>
			<?php echo '<input type="text" name="km" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $km . '">'; ?> 
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Document à joindre</span>
			<input type="file" name="document" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
		</div>
		<br>
	
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Type de contrôle*</span>
			<select class="custom-select" name="controle" required>
			<?php

			foreach ($listeControle as $uneLigne) {
				if($uneLigne['NoControle'] != 0 or $InfosVehicule['TypeVehicule'] == 'Vehicule frigorifique')
				{
					if($InfosPassageCT['NoControle'] == $uneLigne['NoControle'])
					{
							echo '<option selected value="'.$uneLigne['NoControle'].'">'. $uneLigne['TypeControle'] . '</option>';
					}
					else
					{
							echo '<option value="'.$uneLigne['NoControle'].'">'. $uneLigne['TypeControle'] . '</option>';
					}
				}
			}
			?>
			</select>
			
		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Statut*</span>
			<select class="custom-select" name="statut" required>
				<?php
				if($InfosPassageCT['OkControle'] == 1)
				{
						echo '<option selected value="1">Accepté</option>
							<option value="0">Ajourné</option>';
				}
				else
				{
						echo '<option value="1">Accepté</option>
							<option selected value="0">Ajourné</option>';
				}
			?>
			</select>
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Montant (en €)*</span>
			<?php echo '<input type="text" name="montant" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $montant . '">'; ?> 
		</div>
		<br>

		<br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button><br><br>

		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		<br>
		</form>
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