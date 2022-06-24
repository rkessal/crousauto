<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
	<title>CROUS AUTO/VELO</title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
  	<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  	<meta charset="utf-8">
  	<link rel="shortcut icon" href="logo.ico" type="image/x-icon"/>
	<link rel="icon" href="logo.ico" type="image/x-icon"/>
  	<link rel="stylesheet" href="accueil_sans_connexion.css" />
  	<?php
	require_once('auth/bdd.php');
	$conectBDD = new BDD();

	if(isset($_POST['nbMoisPlus']))
	{
		$idSemaine = $_POST['idSemaine'] + 1;
		if($idSemaine > 52)
		{
			$idSemaine = 1;
			$idAnnee = (int)$_POST['idAnnee'] + 1;

		}
		else
			$idAnnee = $_POST['idAnnee'];
	}
	else
	{
		if(isset($_POST['nbMoisMoins']))
		{

			$idSemaine =$_POST['idSemaine'] - 1;
			if($idSemaine < 1)
			{
				$idSemaine = 52;
				$idAnnee = $_POST['idAnnee'] - 1;
			}
			else
				$idAnnee = $_POST['idAnnee'];
		}
		else
		{
			$idSemaine = date('W');
		}

	}


	if(isset($_POST['idAnnee']))
	{
		
	}
	else
		$idAnnee = (int)date('Y');
	?>
</head>
<body>
	<header>
	
	<div class="ruban">
		<div id="logo">
			<?php
			echo ' <img style="width:60px" src="logo.png">';
			?>
		</div>
		<div class="titre-header">
			<p class="titre"><b>CROUS AUTO/VELO</p><p class="proprietaire"></b></p>
		</div>
		<div class="informations-user"><br><br>
			<a class="btn btn-success" href="auth/index.php"><p><div class="glyphicon glyphicon-lock"></div> Se Connecter</p></a></h1><br><br><br>
		</div>
		<div id="clear"></div>
	</div>
		

	</header>

<div class="top-margin">

	
	<center><h1 class="title" style="display: inline-block;"> BIENVENUE ...

<br>


<?php
	if(isset($_POST['chgmtcrous']))
	{
		//setcookie($_COOKIE['crous'], '', time() - 3600, '/');
		unset($_COOKIE['crous']);
	}

		if(isset($_COOKIE['crous']))
		{
			$proprietaire = $_COOKIE['crous'];
			$ProprietaireInfos = $conectBDD->Proprietaire_Infos_parId($_COOKIE['crous']);
		}
		else
		{
			if(isset($_POST['Valider']))
			{
				// on définit une durée de vie de notre cookie (en secondes), donc un an dans notre cas
				$temps = 365*24*3600;

				$proprietaire = $_POST['proprietaire'];

				// on envoie un cookie de nom pseudo portant la valeur LA GLOBULE
				setcookie ("crous", $_POST['proprietaire'], time() + $temps);
				$_COOKIE['crous'] = $proprietaire;
				
			}
			else
			{
				if(!isset($_COOKIE['crous']))
				{
					$listeProprietaire = $conectBDD->Proprietaire_Liste();
					?>
					<br>
						<form method="post">
							<div class="alert alert-warning" role="alert">
								<div class="input-group" style="text-align: center">
									 <h4 class="alert-heading">Sélection de votre Site :</h4>
									<select class="btn btn-default dropdown-toggle" name="proprietaire" required>
									<?php
								foreach ($listeProprietaire as $uneLigne) {
									echo '<option value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
								}
								?>
								</select>
								<input class="btn btn-primary" type="submit" name="Valider" value="Valider">
							</div>
						</div>
					</form>
					<br>
				<?php
				}
			}
		}
	if(isset($_COOKIE['crous']))
	{
		$proprietaire = $_COOKIE['crous'];
		$ProprietaireInfos = $conectBDD->Proprietaire_Infos_parId($_COOKIE['crous']);
		?></center><br>

		<table class="table"><tr><th scope="col">
			<center><form method="post">
				<?php echo '<input type="hidden" name="idSemaine" value="' . $idSemaine .'">'; ?>
				<?php echo '<input type="hidden" name="idAnnee" value="' . $idAnnee .'">'; ?>
				<button type="submit" name="nbMoisMoins" class="btn btn-outline-primary">
				  <i class="glyphicon glyphicon-arrow-left"></i>
				</button>
			</center></th>
			<th colspan="5" scope="col"><center><h1 style="margin: 0; margin-bottom: 5px;"><?php echo 'Semaine ' . $idSemaine; ?></h1></center></th>
			<th scope="col"><center>
				<button type="submit" name="nbMoisPlus" class="btn btn-outline-primary">
				  <i class="glyphicon glyphicon-arrow-right"></i>
				</button>

		<?php
			$debut_fin_semaine = get_lundi_vendredi_par_semaine($idSemaine, $idAnnee);
			echo "<tr>";
			$debut = $debut_fin_semaine[0];
			$mardi = $debut_fin_semaine[1];
			$mercredi = $debut_fin_semaine[2];
			$jeudi = $debut_fin_semaine[3];
			$vendredi = $debut_fin_semaine[4];
			$samedi = $debut_fin_semaine[5];
			$fin = $debut_fin_semaine[6];
			?>
			</form></center></tr>
			<tr><th style="width: 12%; text-align: center" scope="col">Lundi <?php echo $debut; ?></th><th style="width:12%; text-align: center" scope="col">Mardi <?php echo $mardi; ?></th><th style="width:12%; text-align: center" scope="col">Mercredi <?php echo $mercredi; ?></th><th style="width:12%; text-align: center" scope="col">Jeudi <?php echo $jeudi; ?></th><th style="width:12%; text-align: center" scope="col">Vendredi <?php echo $vendredi; ?></th><th style="width:12%; text-align: center" scope="col">Samedi <?php echo $samedi; ?></th><th style="width:12%; text-align: center" scope="col">Dimanche <?php echo $fin; ?></th></tr></tr>
			<?php
			$compteurfin = 7;
			$compteur = 0;
			while($compteur<$compteurfin)
				{
					$dateStr = explode("/", $debut_fin_semaine[$compteur]);
					$date = $dateStr[2] . '-' . $dateStr[1] . '-' . $dateStr[0] .  ' %';
					if(isset($proprietaire))
						$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDateEtProprietaire($date, $proprietaire);

					//var_dump($listeUtilisation);
					if(!isset($listeUtilisation))
						echo "<td><b><h4></b></h4><br><br><br><br><br><br></td>";
					else
					{
						if($listeUtilisation == null)
							echo "<td><b><h4></b></h4><br><br><br><br><br><br></td>";
						else
						{
							echo "<td><b><h4></b></h4>";

						foreach ($listeUtilisation as $UneLigne) {
								$reservation = $conectBDD->Reserver_Liste_ParIdCondDateDebutUtilDestNbPers($UneLigne['ImmatriculationVehicule'], $UneLigne['NoConducteur'], $UneLigne['DateDebutUtilisation'], $UneLigne['Destination'], $UneLigne['NbPersonnes']);

							$backcolor = $UneLigne['CouleurAffichageVehicule'];
							$hachurebackcolor = "#" . ChangerTonCouleur($backcolor, -20);

							if($reservation != null)
								$hachure = ' background: repeating-linear-gradient( 45deg, ' . $backcolor . ',  ' . $backcolor . ' 10px,  ' . $hachurebackcolor . ' 10px,  ' . $hachurebackcolor . ' 15px);';
							else
								$hachure = 'background-color: ' . $backcolor . ';';
								$DateDebutUtilisation = new DateTime($UneLigne['DateDebutUtilisation']);
								$DateFinUtilisation = new DateTime($UneLigne['DateFinUtilisation']);
								$id = $UneLigne['ImmatriculationVehicule'];
								$backcolor = $UneLigne['CouleurAffichageVehicule'];
								$color = @inverse_hexcolor($backcolor);
								echo '<div style="' . $hachure . ' background-color: #' . $backcolor . '; color:#' . $color . '; padding:5px; margin-bottom:5px; border-radius:5px;width:200px;height:100% "><center><h4>';
								$vehicule = $conectBDD->Vehicule_Liste_parImmatriculation($UneLigne["ImmatriculationVehicule"]);
								echo "<h4 style='background-color: #" . $backcolor . "; color:#" . $color . "; border-bottom: solid 1px'><div class='glyphicon glyphicon-'></div> <span class='badge'>" . $vehicule['TypeVehicule'] . "</span> <b>" . $vehicule['ConstructeurVehicule'] . ' ' . $vehicule['ModeleVehicule'] . "</b></h4>"; 
								echo '<div class="glyphicon glyphicon-hourglass"></div> <b>' . $DateDebutUtilisation->format('H:i') . '</b> <div class="glyphicon glyphicon-chevron-right"></div> <b>' . $DateFinUtilisation->format('H:i') . '</b><br>'; 
								echo '<div class="glyphicon glyphicon-user"></div> <b>' . $UneLigne["NomConducteur"] . ' ' . $UneLigne['PrenomConducteur'] . '</b> <span class="label label-default">' . $UneLigne["NbPersonnes"] . '/' .  $vehicule['NbPlaceVehicule'] . ' passagers</span><br><div class="glyphicon glyphicon-flag"></div>  <b>' . $UneLigne["Destination"] . '</b><br>';
								echo '</center></h4></div>';
							}
						}
						echo"</td>";
					}
					$compteur += 1;
				}
			echo '</tr></table>';

			echo '<i>Site Actuel : ' . $ProprietaireInfos['NomProprietaire'] . ' (' .$ProprietaireInfos['NomResidence'].')';
			//echo' <form style="display:inline-block" method="post"> <input type="hidden" value="1" name="chgmtcrous"><input style="font-size:15px;display:inline-block" class="btn btn-link" type="submit" value="Changer de crous">	</form></i>';
		
	}
	
?>
</div>
</body>
</html>

<?php
function inverse_hexcolor($color)
	{
		$color = '' . $color;
		$colorDec = hexdec($color);
		$decimal = str_split($colorDec, 3);
		if ((0.3*($decimal[0]) + 0.59*($decimal[1]) + 0.11*($decimal[2])) < 125)
			$couleur_de_texte = 'FFFFFF'; 
		else
			$couleur_de_texte = '000000';
	/*eval('$color = 0x'.$color.';');

	return sprintf('%x',(-(0xff000000 + $color) - 1));*/
	return $couleur_de_texte;
	}

function NbJoursDemmarreMois($nbmois, $annee)
{
	$nomMois = date("l", mktime(0, 0, 0, $nbmois, 1, $annee));
	switch($nomMois)
	{
		case "Monday":
			$nomDuMois = 1;
			break;
		case "Tuesday":
			$nomDuMois = 2;
			break;
		case "Wednesday":
			$nomDuMois = 3;
			break;
		case "Thursday":
			$nomDuMois = 4;
			break;
		case "Friday":
			$nomDuMois = 5;
			break;
		case "Saturday":
			$nomDuMois = 6;
			break;
		case "Sunday":
			$nomDuMois = 7;
			break;
		default:
			$nomDuMois = "0";
			break;
	}
	return $nomDuMois;
}

function get_lundi_vendredi_par_semaine($week,$year) 
{
	$format="d/m/Y";
	$firstDayInYear=date("N",mktime(0,0,0,1,1,$year));
	if ($firstDayInYear<5)
	$shift=-($firstDayInYear-1)*86400;
	else
	$shift=(8-$firstDayInYear)*86400;
	if ($week>1) $weekInSeconds=($week-1)*604800; else $weekInSeconds=0;
	$timestamp=mktime(0,0,0,1,1,$year)+$weekInSeconds+$shift;
	$timestamp_mardi=mktime(0,0,0,1,2,$year)+$weekInSeconds+$shift;
	$timestamp_mercredi=mktime(0,0,0,1,3,$year)+$weekInSeconds+$shift;
	$timestamp_jeudi=mktime(0,0,0,1,4,$year)+$weekInSeconds+$shift;
	$timestamp_vendredi=mktime(0,0,0,1,5,$year)+$weekInSeconds+$shift;
	$timestamp_samedi=mktime(0,0,0,1,6,$year)+$weekInSeconds+$shift;
	$timestamp_dimanche=mktime(0,0,0,1,7,$year)+$weekInSeconds+$shift;

	return array(date($format,$timestamp),date($format,$timestamp_mardi),date($format,$timestamp_mercredi),date($format,$timestamp_jeudi),date($format,$timestamp_vendredi),date($format,$timestamp_samedi),date($format,$timestamp_dimanche));

}

function ChangerTonCouleur($color, $changementTon)
{
	$color = substr($color,1,6);
	$cl=explode('x',wordwrap($color,2,'x',3));
	$color='';
	for($i=0;$i<=2;$i++){
		$cl[$i]=hexdec($cl[$i]);
		$cl[$i]=$cl[$i]+$changementTon;
		if($cl[$i]<0) $cl[$i]=0;
		if($cl[$i]>255) $cl[$i]=255;
		$color.=StrToUpper(substr('0'.dechex($cl[$i]),-2));
	}
	return ''.$color; 
}

?>
