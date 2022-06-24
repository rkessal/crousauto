<?php session_start();
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: calendrier_vehicule.php');
}

//var_dump($liste['DroitVehicule']);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Accueil</title>
  	<?php
	
	include("menu.php");

	require_once('gestionAlerte.php');
?>
</head>
<body>
	<div id="top-margin" class="top-margin">
	<h1>Informations</h1><br>

	<ul class="list-group">
	<?php

		$compteurAlerte = 0;
		if($_SESSION['NoProprietaire'] == 0)
			$listeVehicule = $conectBDD->Vehicule_Liste();
		else
		{
			if($liste['NoDroit'] == 1)
				$listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
			else
				$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
		}
		
		$listeAlerte = null;
		foreach ($listeVehicule as $vehicule) {
			$Alerte = VerifAlerte($vehicule['ImmatriculationVehicule'], 0);

			if($Alerte != null)
			{
				$listeAlerte .= '<li class="list-group-item list-group-item-danger"><h4><span class="glyphicon glyphicon-exclamation-sign"></span> &nbsp'. $Alerte . '</h4></li>';	
			}
		}
		if($listeAlerte == null)
		{
		?>
			<li class="list-group-item list-group-item-success"><h4>
				<div class="glyphicon glyphicon-ok"> </div>
				Aucune action n'est requise actuellement
			</h4></li>
		<?php
		}
		else
			echo $listeAlerte;
	?>
	</ul>
	</div>
</div>

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>