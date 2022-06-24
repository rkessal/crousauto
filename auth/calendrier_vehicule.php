<?php session_start();
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);


//var_dump($liste['DroitVehicule']);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Calendrier</title>
  	<?php
	
	include("menu.php");

	require_once('gestionAlerte.php');
?>
</head>
<body>
	<div id="top-margin" class="top-margin">
	<h1>CALENDRIER DE L'UTILISATION DES VEHICULES</h1>

    <?php
	if($_SESSION['NoResidence'] == 0)
		$listeVehicule = $conectBDD->Vehicule_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
		else
			$listeVehicule = $conectBDD->Vehicule_Liste_ParUser_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
	}
	if (($liste['DroitVehicule'] != 0) or ($liste['ReserverVehicule'] != 0))
	{
	
	?>
	<div class="alert alert-primary" role="alert">
		<form method="post" action="reservation_vehicule.php">
			<div class="input-group">
			<h4 style="line-height: 1.5; margin-right: 10px; margin-bottom: 0;">Demander la réservation d'un Véhicule :</h4>
			<div class="input-group">
			<select class="custom-select" name="ImmatSaisi" required>
			<?php
			foreach ($listeVehicule as $uneLigne) {
				echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['TypeVehicule']. ' - ' . $uneLigne['ConstructeurVehicule'] .' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</option>';
			}
			?>
			</select>
			<button class="btn btn-primary" type="submit" name="Reserver" value="Valider"><i class="glyphicon glyphicon-ok"></i></button>
		</div>
	</div>
		</form>

	</div>
	
	<?php
}
?>

	<div class="card card-default">
	  <div class="card-body" style="overflow: auto; -webkit-overflow-scrolling: touch;border:none;">
	    <div class="calendrier-container">
	      <div id="calendar"></div>
	    </div>
	  </div>
	</div>


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