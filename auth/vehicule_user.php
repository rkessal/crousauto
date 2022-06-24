<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitUtilisateur'] == 0)
{
	header('Location: index.php');
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Véhicules utilisables par un Utilisateur</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	
	$id = $_SESSION['id'];
	$listeGerer = $conectBDD->Gerer_Infos_parId($id);
	$InfosUser = $conectBDD->User_Infos_parId($id);
	/*if($_SESSION['NoProprietaire'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else*/
		$listeVehicule = $conectBDD->Vehicule_Liste_parProprietaire($InfosUser['NoProprietaire']);

?>
</head>
<body>
<div id="top-margin" class="top-margin">

<h1 class="title">Véhicules utilisables par <?php echo $InfosUser['PseudoUtilisateur']; ?></h1>
<br>
	<?php
	
	if(isset($_POST["Valider"]))
	{
		$idVehicule = $_POST['idVehicule'];
		if(!$_POST['gestionActuel'])
		{
			$res = $conectBDD->Gerer_Create($_SESSION['id'], $idVehicule);
			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	L'utilisateur peut maintenant gérer ce Véhicule.
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
		else
		{
			$res = $conectBDD->Gerer_Delete($_SESSION['id'], $idVehicule);
			if($res)
			{
				?>
				<div class="alert alert-warning" role="alert">
				  	L'utilisateur ne peut plus gérer ce Véhicule.
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

	}

	$InfosUser = $conectBDD->User_Infos_parId($id);
	$pseudo = $InfosUser['PseudoUtilisateur'];
	?>

<table class="table"><tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Type</th><th scope="col">Site</th><th scope="col">Utilisation</th><th scope="col">Actions</th></tr></tr>
	<?php
	foreach ($listeVehicule as $uneLigne) {
		echo '';
		echo '<tr><th>'.$uneLigne['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$uneLigne['ConstructeurVehicule'].' - ';
		echo  $uneLigne['ModeleVehicule'].'</td>';
		echo '<td>'.$uneLigne['CouleurVehicule'].'</td>';
		echo '<td>'.$uneLigne['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $uneLigne['TypeVehicule'].'</td>';
		echo '<td>'. $uneLigne['NomProprietaire'].'</td>';
		echo '<td>';
		$gere = $conectBDD->Gerer_Infos_parIdEtImmat($id, $uneLigne['ImmatriculationVehicule']);
		if(isset($gere['Gere']))
			$gere = true;
		else
			$gere = false;
		if($gere or $uneLigne['LibreServiceVehicule'] == 1 or $InfosUser['NoDroit'] == 1)
			echo '<h2><span class="badge badge-success">utilisable</span></h2></td>';
		else
					echo '<h2><span class="badge badge-danger">non utilisable</span></h2></td>';
		$idVehicule = $uneLigne['ImmatriculationVehicule'];
		if($InfosUser['NoDroit'] != 1 and $uneLigne['LibreServiceVehicule'] != 1)
			echo '<td> &nbsp
				<form style="display: inline-block;" method="post">
					<input type="hidden" name="idVehicule" value="' . $idVehicule . '">
					<input type="hidden" name="gestionActuel" value="' . $gere . '">
					<button type="submit" name="Valider" class="btn btn-primary">
						<i class="glyphicon glyphicon-refresh"></i> Changer
					</button> &nbsp
					</form>
				</td></tr>';
		else
			echo '<td></td></tr>';
		
	}
	echo '</table>';
?>
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

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>