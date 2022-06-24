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
	<title>Modification d'une Fréquence d'entretien</title>

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
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}

	if(isset($_POST['EntretienModif']))
	{
		$_SESSION['EntretienModif'] = $_POST['EntretienModif'];
	}
	
	$id = $_SESSION['id'];
	$entretien = (int)$_SESSION['EntretienModif'];
	$InfosOccurence = $conectBDD->Occurence_Element_Infos_parId($entretien, $id);
?>

<h1 class="title">Modification d'une Fréquence d'entretien</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$km = (int)$_POST['km'];
		$mois = (int)$_POST['mois'];
		

		$conectBDD = new BDD();
		$res = $conectBDD->Occurence_Element_Modif($id, $entretien, $km, $mois);
		if($res!=false)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	La Fréquence à bien été modifié.
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
	$id = $_SESSION['id'];
	$entretien = (int)$_SESSION['EntretienModif'];
	$InfosOccurence = $conectBDD->Occurence_Element_Infos_parId($entretien, $id);
	?>

	<?php
				echo '<br><h3>Pour ' . $InfosOccurence["LibelleElement"] . ' :</h3>';
				$NoElement = $InfosOccurence['NoElement'];
				?>
				<br>
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 300px" id="basic-addon1">Nombre de kilomètres avant entretien</span>
					<?php $nomkm = 'km ' . $NoElement;
						echo '<select class="custom-select" name="km" required>' ?>
							<?php
							//var_dump('km_' . $NoElement);
							$compteur = 10000;
							while($compteur <= 300000)
							{
								if($InfosOccurence['OccurenceKmElement']== $compteur)
									echo '<option seleted value="'.$compteur.'">'.$compteur.' kilomètres</option>';
								else
									echo '<option value="'.$compteur.'">'.$compteur.' kilomètres</option>';
								$compteur += 10000;
							}
					?>
				</select>
				</div>
				<br>
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 300px" id="basic-addon1">Nombre de mois avant entretien</span>
					<?php $nommois = 'mois_' . $NoElement;
						echo '<select class="custom-select" name="mois" required>' ?>
							<?php
							$compteur = 1;
							while($compteur <= 60)
							{
								if($InfosOccurence['OccurenceMoisElement']== $compteur)
									echo '<option selected value="'.$compteur.'">'.$compteur.' mois</option>';
								else
									echo '<option value="'.$compteur.'">'.$compteur.' mois</option>';
								$compteur += 1;
							}
							?>
						</select>
					</div>
			  	<?php

		?>
		<br><br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button></form>
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