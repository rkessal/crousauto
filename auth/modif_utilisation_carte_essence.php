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
	<title>Modifier l'utilisation d'une Carte Essence</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	if(isset($_POST['immatSaisie']))
		$_SESSION['ImmatSaisi'] = $_POST['immatSaisie'];
	$id = $_SESSION['ImmatSaisi'];
	$conectBDD = new BDD();
	if(isset($_POST['idModif']))
		$_SESSION['idModif'] = $_POST['idModif'];
	$noBadge = (int)$_SESSION['idModif'];
	if(isset($_POST['idDate']))
		$_SESSION['idDate'] = $_POST['idDate'];
	$noBadge = (int)$_SESSION['idModif'];
	if(isset($_POST['idCond']))
		$_SESSION['idCond'] = $_POST['idCond'];
	$noBadge = (int)$_SESSION['idModif'];
	$listeConducteur = $conectBDD->Conducteur_Liste();
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$InfosBadge = $conectBDD->Carte_Essence_Infos_parImmat($id);
	$InfosUtilisationBadge = $conectBDD->Utiliser_Carte_Essence_Infos_parIdEtCondEtDate($noBadge, $_SESSION['idDate'], $_SESSION['idCond']);
		$date = $InfosUtilisationBadge['DateUtilisation'];
		$litre = $InfosUtilisationBadge['Litre'];
		$prix = $InfosUtilisationBadge['Montant'];
		$km = $InfosUtilisationBadge['KilometrageCarte'];
		$conducteur = $InfosUtilisationBadge['NoConducteur'];
		$station = $InfosUtilisationBadge['Station'];
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST">


<h1>Modifier l'utilisation d'une Carte Essence</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$date = $_POST['date'];
		if(isset($_POST['litre']))
			$litre = (int)$_POST['litre'];
		else
			$litre = 0;
		$prix = (int)$_POST['prix'];
		if(isset($_POST['km']))
			$km = (int)$_POST['km'];
		else
			$km = 0;
		$conducteur = (int)$_POST['conducteur'];
		$station = $_POST['station'];
		$nobadge = (int)$InfosBadge['NoCarte'];


		$conectBDD = new BDD();
		$res = $conectBDD->Utiliser_Carte_Essence_Modif($nobadge, $conducteur, $date, $km, $litre, $prix, $station);

			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	L'Utilisation de la carte essence du v??hicule ?? bien ??t?? modifi??.
				</div>
				<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=liste_carte_essence.php"> 
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Cette carte est d??j?? utilis?? ?? ce moment l??.
				</div>
				<?php
			}
								
	}

	/*var_dump($id);
	var_dump($date);
	var_dump($entree);
	var_dump($sortie);
	var_dump($montant);
	var_dump($conducteur);
	var_dump($autoroute);
	var_dump($InfosBadge['NoTelepeage']);*/

	?>

	<table class="table"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Mod??le</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Propri??taire</th><th scope="col">Carte Essence</th></tr></tr>
	<?php
		echo '';
		echo '<tr><th>'.$InfosVehicule['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$InfosVehicule['ConstructeurVehicule'].' - ';
		echo  $InfosVehicule['ModeleVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['CouleurVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $InfosVehicule['NomProprietaire'].'</td>';
		echo '<td>'. $InfosBadge['NomCarte'].'</td>';
		echo '</table>';

?>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date d'utilisation*</span>
  			<?php echo '<input type="text" required name="date" style="width: 200px" value="'. $date . '" class="input-text form-control" aria-describedby="basic-addon1"  id="datepicker" autocomplete="off">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Station Service</span>
  			<?php echo '<input type="text" name="station" style="width: 200px" value="'. $station . '" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Kilometrage</span>
  			<?php echo '<input type="text" name="km" style="width: 200px" value="'. $km . '" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Litre</span>
  			<?php echo '<input type="text" name="litre" style="width: 200px" value="'. $litre . '" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Montant (en ???)*</span>
  			<?php echo '<input type="text" required name="prix" style="width: 200px" value="'. $prix . '" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>
		
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Conducteur*</span>
				<select style="width: 500px" class="custom-select" name="conducteur" style="width: auto" required>
					<?php
					foreach ($listeConducteur as $uneLigne) {
						if($uneLigne['ActifConducteur'])
							echo '<option value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
					}
					?>
				</select>
		</div>

		<br><br>

		<br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button><br><br></form>

		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>
		<br><?php
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