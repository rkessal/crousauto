<!DOCTYPE html>
<?php
	@session_start();
	date_default_timezone_set('Europe/Paris');
	require_once('bdd.php');
	$connectBDD = new BDD();


	
	$Maintenance = $connectBDD->Maintenance_infos();
	$path = $_SERVER['PHP_SELF'];
	$file = basename ($path);
	if($Maintenance['EtatMaintenance'] == 1 and $liste['NoDroit'] != 1 and $file != "maintenance.php")
	{
		header("Location: maintenance.php");
	}

	$liste = $connectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
	//var_dump($_SESSION['pseudo']);	
	//var_dump($liste);
		$_SESSION['NomDroit'] = $liste['NomDroit'];
		$_SESSION['NoDroit'] = $liste['NoDroit'];
		$_SESSION['NoUtilisateur'] = $liste['NoUtilisateur'];
		$_SESSION['NoResidence'] = $liste['NoResidence'];
		$_SESSION['NoProprietaire'] = $liste['NoProprietaire'];
		$_SESSION['NomProprietaire'] = $liste['NomProprietaire'];
		$_SESSION['LogoResidence'] = $liste['LogoResidence'];
		$_SESSION['NomResidence'] = $liste['NomResidence'];
		$_SESSION['Loading'] = 2;

	if (!isset($liste['NoUtilisateur']))
	{
		header('Location: index.php');
	}

	//var_dump($liste['NomDroit']);

	empecherLaMiseEnCache();
	$path = $_SERVER['PHP_SELF'];
	$file = basename ($path);
	if(@$_SESSION['pageRecharge'] != $file and $file != "gestion_reservation_vehicule.php")
	{
		$_SESSION['pageRecharge'] = $file;
		echo '<meta http-equiv="refresh" content="0;URL=' . $file . '">';

	}
	else
		$_SESSION['pageRecharge'] = $file;
	function empecherLaMiseEnCache()
  	{
	    header('Pragma: no-cache');
	    header('Expires: 0');
	    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	    header('Cache-Control: no-cache, must-revalidate');
  	}
	$moderation = $_SESSION['NomDroit'];
?> 


<html>
<head>
	
	<meta charset="utf-8">
	
	
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-glyphicon.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	
	<link href='fullcalendar-3.10.0/fullcalendar.min.css' rel='stylesheet' />
	<link href='fullcalendar-3.10.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />

	<link rel="shortcut icon" href="ressources/logo.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="icon" href="ressources/logo.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	  
</head>

<header>
	
	<div class="ruban">
		<?php
		if($Maintenance['EtatMaintenance'] == 1 and $file != "maintenance.php")
			echo'<div class="maintenance-overlay">
					<div class="maintenance-overlay-text">MAINTENANCE EN COURS</div>
				</div>';
		?>
		<div id="logo">

			<?php
			echo ' <img style="width:60px" src="logo/'.$_SESSION['LogoResidence'].'">';
			?>
		</div>
		<div class="titre-header">
			<p class="titre"><b>CROUS AUTO/VELO</b></p>
		</div>
		<div class="informations-user">
			<p><span class="glyphicon glyphicon-off"></span>
				<span id="info-user-deconnexion"><a href="deconnexion.php">Quitter</a></span></p>
			<p><span class="glyphicon glyphicon-user"></span><span id="info-user">Utilisateur connecté : </span><strong><?php echo $_SESSION['displayName'] ?> </strong></p>
			<p><span class="glyphicon glyphicon-wrench"></span><span id="info-user">Modération : </span><strong><?php echo $moderation ?> </strong></p>
			<p><span class="glyphicon glyphicon-home"></span><span id="info-user">Résidence : </span><strong><?php echo $_SESSION['NomResidence'] ?> </strong></p>
			<p><span class="glyphicon glyphicon-briefcase"></span><span id="info-user">Site : </span><strong><?php echo $_SESSION['NomProprietaire'] ?> </strong></p>
		</div>
		<div id="clear"></div>
	</div>
		

</header>
<body>
	<div id="menu-container" class="hide scroll-width-thin">
			<div id="cote-gauche">

				<div id="left-menu">
				<img id="side-img">
				
				<nav>
					<ul>
						<li id="menu-button"><img id="menu-icon" class="menu-item" src="ressources/responsive/menu-item.png" id="cote-gauche-bouton" style="height: 31px; width: 37px;"></li></a><div id="clear"></div>
						<?php 
						if ($liste['DroitVehicule'])
						{ ?>
						<li><a href="accueil.php" class="text">ACCUEIL</a><a href="accueil.php"><img src="ressources/responsive/home-item.png"></li></a><div id="clear"></div>
						<?php 
						}
						?>
						<li><a href="calendrier_vehicule.php" class="text">CALENDRIER</a><a href="calendrier_vehicule.php"><img src="ressources/responsive/calendar-item.png"></a></li><div id="clear"></div>
						<?php 
						if ($liste['ReserverVehicule'] or $liste['DroitVehicule'])
						{ ?>
						<li><a href="gestion_reservation_vehicule.php" class="text">DEMANDES EN COURS</a><a href="gestion_reservation_vehicule.php"><img src="ressources/responsive/demande-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitVehicule'])
						{ ?>
						<li><a href="gestion_vehicule.php" class="text">VEHICULES</a><a href="gestion_vehicule.php"><img src="ressources/responsive/car-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['ReserverVehicule'])
						{ ?>
						<li><a href="gestion_vehicule.php" class="text">RESERVATION</a><a href="gestion_vehicule.php"><img src="ressources/responsive/car-item.png"></a></li><div id="clear"></div>
						<?php 
						}
						if ($liste['DroitVehicule'])
						{ ?>
						<li><a href="gestion_carte_essence.php" class="text">CARTES ESSENCE</a><a href="gestion_carte_essence.php"><img src="ressources/responsive/carte-essence-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitVehicule'])
						{ ?>
						<li><a href="gestion_telepeage.php" class="text">TELEPEAGES</a><a href="gestion_telepeage.php"><img src="ressources/responsive/telepeage-item.png"></a></li><div id="clear"></div>
						<?php 
						}  
						if ($liste['DroitConducteur'])
						{ ?>
						<li><a href="gestion_conducteur.php" class="text">CONDUCTEURS</a><a href="gestion_conducteur.php"><img src="ressources/responsive/volant-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitService'])
						{ ?>
						<li><a href="gestion_service.php" class="text">SERVICES</a><a href="gestion_service.php"><img src="ressources/responsive/services-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitUtilisateur'])
						{ ?>
						<li><a href="gestion_statistiques.php" class="text">STATISTIQUES</a><a href="gestion_statistiques.php"><img src="ressources/responsive/stats-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitUtilisateur'])
						{ ?>	
						<li><a href="gestion_user.php" class="text">UTILISATEURS</a><a href="gestion_user.php"><img src="ressources/responsive/user-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($liste['DroitUtilisateur'])
						{ ?>
						<li><a href="gestion_proprietaire.php" class="text">LES SITES</a><a href="gestion_proprietaire.php"><img src="ressources/responsive/proprietaire-item.svg" style="width: 39px; height: 39px; color: white;"></a></li><div id="clear"></div>
						<?php 
						}
						if ($_SESSION['NoResidence'] == 0)
						{ ?>
						<li><a href="gestion_residence.php" class="text">LES CROUS</a><a href="gestion_residence.php"><img src="ressources/responsive/residence-item.png" style="width: 39px; height: 39px; color: white;"></a></li><div id="clear"></div>
						<?php 
						}
						if ($_SESSION['NoResidence'] == 0)
						{ ?>	
						<li><a href="gestion_droit.php" class="text">DROITS</a><a href="gestion_droit.php"><img src="ressources/responsive/droit-item.png"></a></li><div id="clear"></div>
						<?php
						}
						if ($_SESSION['NoResidence'] == 0)
						{ ?>
						<li><a href="gestion_controle.php" class="text">TYPES DE CONTROLE</a><a href="
							gestion_controle.php"><img src="ressources/responsive/type-controle-item.png" style="width: 39px; height: 39px; color: white;"></a></li><div id="clear"></div>
						<?php 
						} 
						if ($_SESSION['NoResidence'] == 0)
						{ ?>
						<li><a href="gestion_operation.php" class="text">OPERATIONS</a><a href="gestion_operation.php"><img src="ressources/responsive/moteur-item.png"></a></li><div id="clear"></div>
						<?php 
						} 
						?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</body>

</html>
