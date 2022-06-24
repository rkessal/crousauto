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
	<title>Saisie de l'entretien d'un Véhicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeEntretien = $conectBDD->Entretien_Liste();
	$listeElement = $conectBDD->Element_Liste();
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
<h1>Saisie de l'entretien d'un Véhicule</h1>
<br>

	<?php
	if(isset($_POST["Valider"]))
	{
		$ok = 0;
		$okupload = 0;
		$verif = 0;
		foreach ($listeElement as $uneLigne)
		{				
			if(isset($_POST[$uneLigne['NoElement']]))
			{
				$verif += 1;

				$date = $_POST['date'];
				$km = (int)$_POST['km'];
				$observations = $_POST['observations'];
				if(isset($_FILES['document']['name']))
					$document = basename($_FILES['document']['name']);
				
				$entretien = $_POST['entretien'];
				$element = $uneLigne['NoElement'];
				
				$upload = move_uploaded_file($_FILES['document']['tmp_name'],'doc/' . date('YmdHi') . '_' . $document);
				if(!$upload)
					$document = null;
				 
				if($upload)
				{
					$okupload += 1;
				}

				$res = $conectBDD->Passer_Entretien_Create($id, $date, $km, $observations, date('YmdHi') . '_' . $document, $entretien, $element, $_POST['montant']);

				if($res)
				{
					$ok += 1;
				}
			}
		}
		/*var_dump($id);
		var_dump($date);
		var_dump($km);
		var_dump($observations);
		var_dump($document);
		var_dump($entretien);
		var_dump($element);
		var_dump($ok);
		var_dump($verif);*/

		if($ok == $verif)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	L'entretien a bien été enregistré</div>
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

		if($okupload == $verif)
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
  			<input class="input-text form-control" type="text" name="date" required style="width: 200px" aria-describedby="basic-addon1"  id="datepicker" autocomplete="off" placeholder="Sélectionnez une date">
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Kilométrage*</span>
			<input class="input-text form-control" type="text" name="km" required style="width: 200px" aria-describedby="basic-addon1">
		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Observations</span>
			<input class="input-text form-control" type="text" name="observations" style="width: 200px" aria-describedby="basic-addon1">
		</div>
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
			<input class="input-text form-control" required="" type="text" name="montant" style="width: 200px" aria-describedby="basic-addon1">
		</div>
		<br>
		<br>


		<div class="input-group" style="text-align: center">
			<h1>Opérations effectués : </h1>
		</div>
		<br>

				<?php
				$compteur = 0;
				foreach ($listeElement as $uneLigne) {
					$compteur += 1;
					echo ' 
   							<div class="custom-control custom-checkbox"> <input type="checkbox" class="custom-control-input" name="'.$uneLigne['NoElement'].'" id="customSwitch' . $compteur . '"><label class="custom-control-label" for="customSwitch' . $compteur . '">'.$uneLigne['LibelleElement'] .'</label>
   							
			  			</div>';
				}
				?>	
				
		
		<br><br>

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