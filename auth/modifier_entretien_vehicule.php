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
<form method="POST">
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
		$verif = 0;
		foreach ($listeElement as $uneLigne)
		{				
			if(isset($_POST[$uneLigne['NoElement']]))
			{
				$verif += 1;

				$date = $_POST['date'];
				$km = (int)$_POST['km'];
				$observations = $_POST['observations'];
				$document = $_POST['document'];
				$entretien = $_POST['entretien'];
				$element = $uneLigne['NoElement'];


				$res = $conectBDD->Passer_Entretien_Create($id, $date, $km, $observations, $document, $entretien, $element);

				if($res)
				{
					$ok += 1;
				}
			}
		}
		var_dump($id);
		var_dump($date);
		var_dump($km);
		var_dump($observations);
		var_dump($document);
		var_dump($entretien);
		var_dump($element);
		var_dump($ok);
		var_dump($verif);
		if($ok == $verif)
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
  			<input class="input-text form-control" type="date" name="date" required style="width: 200px" aria-describedby="basic-addon1">
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Kilométrage*</span>
			<input class="input-text form-control" type="text" name="km" required style="width: 200px" aria-describedby="basic-addon1">  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Observations</span>
			<input class="input-text form-control" type="text" name="observations" style="width: 200px" aria-describedby="basic-addon1">  		</div>
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
		<br>


		<div class="input-group" style="text-align: center">
			<h1>Opérations effectués : </h1>
		</div>
		<br>

				<?php
				foreach ($listeElement as $uneLigne) {
					echo ' <div class="input-group-prepend">
   							<div class="input-group-text checkbox-ajust"> <input type="checkbox" class="operations" name="'.$uneLigne['NoElement'].'"><span style="width: 150px" id="basic-addon1">'.$uneLigne['LibelleElement'] .'</span>
   							</div>
			  			</div>';
				}
				?>

				
		
		<br><br>

		<br><button type="submit" name="Valider" class="btn btn-primary">
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