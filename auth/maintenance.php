<?php session_start(); 
require_once('bdd.php');
	$connectBDD = new BDD();
	$liste = $connectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Maintenance</title>
	<link rel="stylesheet" href="bootstrap-4.3.1-dist/css/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="crous_reservation_salle.css" crossorigin="anonymous">
</head>
<header>
		<?php 
		
		include("menu.php");
		
		?>
		
	</header>
<body>
<div class="top-margin">


<?php
if(isset($_POST['ActiverMaintenance']))
{
	$Maintenance = $connectBDD->Maintenance_modif(1);
}
if(isset($_POST['DesactiverMaintenance']))
{
	$Maintenance = $connectBDD->Maintenance_modif(0);
}

	$Maintenance = $connectBDD->Maintenance_infos();
	if ($Maintenance['EtatMaintenance'] == 1)
	{
		echo'
    <center>
        <div class="maintenance-img">
            <img src="ressources/maintenance-screen.png">
        </div>
        <div class="maintenance-text">
            <h2>L\'application est actuellement en maintenance. Merci pour votre patience</h2>
        </div>
    </center>
';
		if ($liste['NoDroit'] == 1)
			echo '
				<div>
					<center>
						<form method="post">
							<button name="DesactiverMaintenance" class="btn btn-danger">Désactiver la Maintenance</button>
						  </form>
						<br><a class="btn btn-primary" href="index.php">Retour sur l\'application</a>
					  </center>
				</div>';
		}
	else
	{
		echo'<center>
        <div class="maintenance-img">
            <img src="ressources/aucune-maintenance.png">
        </div>
        <div class="maintenance-text">
            <h2>Aucune maintenance en cours</h2>
        </div>
    </center>';
		if ($liste['NoDroit'] == 1)
			echo '
				<div>
					<center>
						<form method="post">
							<button name="ActiverMaintenance" class="btn btn-danger">Activer la Maintenance</button>
						</form>
						<br><a class="btn btn-primary" href="index.php">Retour sur l\'application</a>
					</center>
				</div>
						   ';
	}

?>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>


