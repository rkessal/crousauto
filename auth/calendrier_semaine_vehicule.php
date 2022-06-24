<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (($liste['DroitVehicule'] == 0) and ($liste['ReserverVehicule'] == 0))
{
	header('Location: index.php');
}

if (isset($_REQUEST['oui']))
{
	$test_sel = $_REQUEST['oui'];

	if ($test_sel = 1)
	{
		header('Location: calendrier_vehicule.php');
	}

	else
	{
		echo 'Vous êtes déjà en vue par semaine';
	}
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Calendrier de l'utilisation des Véhicules</title>

</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$liste = $connectBDD->User_Liste_parPseudo($_SESSION['pseudo']);

	if(isset($_POST['nbMoisPlus']))
	{
		$idSemaine =$_POST['idSemaine'] + 1;
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
<body>
<div id="top-margin" class="top-margin">
	<div>
		<h1 class="title" style="display: inline-block;">CALENDRIER DE L'UTILISATION DES VEHICULES</h1>
		<form action="calendrier_semaine_vehicule.php" style="display: inline-block;">
			<select   class="custom-select" id="sel_id" name="oui"  onchange="this.form.submit();">
				<option value="1">Par mois</option>
				<option selected="selected" value="2">Par semaine</option>                    
			</select>
		</form>
	</div>


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
	?>
	
	<form method="post" action="reservation_vehicule.php">
		<div class="alert alert-primary" role="alert">
			<center>
				<h4 class="alert-heading">Demander la réservation d'un Véhicule :</h4>
				<div class="reserver-vehicule">
					<select class="custom-select" name="ImmatSaisi" required>
					<?php
					foreach ($listeVehicule as $uneLigne) {
					echo '<option value="'.$uneLigne['ImmatriculationVehicule'].'">'.$uneLigne['TypeVehicule'].  ' - ' . $uneLigne['ConstructeurVehicule'] .' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</option>';
					}
					?>
					</select>
					<input class="btn btn-primary" style="margin: 5px;"type="submit" name="Reserver" value="Valider">
				</div>
			
			</center>
		</div>
	</form>

	<div style="border: 2px solid #8c8c8c;text-align:center">
		<form method="post">
			<?php echo '<input type="hidden" name="idSemaine" value="' . $idSemaine .'">'; ?>
			<?php echo '<input type="hidden" name="idAnnee" value="' . $idAnnee .'">'; ?>
			<button type="submit" name="nbMoisMoins" class="btn btn-outline-primary bouton-gauche" style='display: inline-block; min-width: 50px;'>
			  <i class="glyphicon glyphicon-arrow-left"></i>
			</button>
			<h1 id="numero-semaine"><?php echo 'Semaine ' . $idSemaine; ?></h1>
			<button type="submit" name="nbMoisPlus" class="btn btn-outline-primary bouton-droite" style='display: inline-block; min-width: 50px;'>
			  <i class="glyphicon glyphicon-arrow-right"></i>
			</button>
		</form>
	</div>
	<div style="border: 2px solid #8c8c8c;text-align:center">
	<?php
	$debut_fin_semaine = get_lundi_vendredi_par_semaine($idSemaine, $idAnnee);
	$debut = $debut_fin_semaine[0];
	$mardi = $debut_fin_semaine[1];
	$mercredi = $debut_fin_semaine[2];
	$jeudi = $debut_fin_semaine[3];
	$vendredi = $debut_fin_semaine[4];
	$samedi = $debut_fin_semaine[5];
	$fin = $debut_fin_semaine[6];
	?>

	<?php
	$compteurfin = 7;
	$compteur = 0;
	while($compteur<$compteurfin)
	{
		$dateStr = explode("/", $debut_fin_semaine[$compteur]);
		$date = $dateStr[2] . '-' . $dateStr[1] . '-' . $dateStr[0] .  ' %';
		if($liste['NoProprietaire'] == 0)
			$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDate($date);
		else
		{
			if($liste['NoDroit'] == 1)
				$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDateEtProprietaire($date, $_SESSION['NoProprietaire']);
			else
				$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParDate_SiGestion($_SESSION['NoUtilisateur'], $date, $_SESSION['NoProprietaire']);
		}
		//var_dump($listeUtilisation);
		echo '<div class="colonne">';
		$LibelleJour = DonneLibelleJour($compteur);
		echo '<div class=""><h4><b>' . $LibelleJour . ' '. $debut_fin_semaine[$compteur] . '</b></h4></div>';
		if($listeUtilisation == null)
		{
			echo '<div class="cellule"></div>';
		}
		else
		{
			echo '<div class="cellule"  style="text-align:center;">';

			foreach ($listeUtilisation as $UneLigne) {
				$reservation = $UneLigne['DateHeureReservation'];

				$backcolor = $UneLigne['CouleurAffichageVehicule'];
				$hachurebackcolor = "#" . ChangerTonCouleur($backcolor, -20);

				if($reservation != null)
					$hachure = ' background: repeating-linear-gradient( 45deg, ' . $backcolor . ',  ' . $backcolor . ' 10px,  ' . $hachurebackcolor . ' 10px,  ' . $hachurebackcolor . ' 15px);';
				else
					$hachure = 'background-color: ' . $backcolor . ';';
				if($reservation != null)
					echo '<form style="display: inline-block;" method="post" action="modif_reservation_vehicule.php">';
				else
					echo '<form style="display: inline-block;" method="post" action="modif_deplacement_vehicule.php">';
				$idcond = $UneLigne['NoConducteur'];
				$iddate = $UneLigne['DateDebutUtilisation'];
				$DateDebutUtilisation = new DateTime($UneLigne['DateDebutUtilisation']);
				$DateFinUtilisation = new DateTime($UneLigne['DateFinUtilisation']);
				$id = $UneLigne['ImmatriculationVehicule'];
				$backcolor = $UneLigne['CouleurAffichageVehicule'];
				$color = @inverse_hexcolor($backcolor);
				if($liste['DroitVehicule']) 
				{
					echo '<button type="submit" style="padding:0px;margin-bottom:5px;border:none;width:200px;" name="Modifier">';
					echo '<div title="Modifier cette réservation" style="' . $hachure . 'background-color: ' . $backcolor . '; color:#' . $color . '; padding:5px; margin:0px; border-radius:5px;width:200px;height:100% "><center><h4>';
				}
				else
					echo '<div style="' . $hachure . ' color:#' . $color . ';width:200px; padding:5px; margin-bottom:5px; border-radius:5px;height:100% "><center><h4>';

				$vehicule = $conectBDD->Vehicule_Liste_parImmatriculation($UneLigne["ImmatriculationVehicule"]);
				echo "<h4 style='background-color: #" . $backcolor . "; color:#" . $color . "; border-bottom: solid 1px'><div class='glyphicon glyphicon-'></div> <span class='badge'>" . $vehicule['TypeVehicule'] . "</span> <b>" . $vehicule["ConstructeurVehicule"] . " " .$vehicule["ModeleVehicule"] . "</b></h4><h4>"; 
				echo '<div class="glyphicon glyphicon-hourglass"></div> <b>' . $DateDebutUtilisation->format('H:i') . '</b> <div class="glyphicon glyphicon-chevron-right"></div> <b>' . $DateFinUtilisation->format('H:i') . '</b><br>';  
				$vehicule['NbPlaceVehicule']-=1;
				echo '<div class="glyphicon glyphicon-user"></div> <b>' . $UneLigne["NomConducteur"] . ' ' . $UneLigne['PrenomConducteur'] . '</b> <br><span class="label label-default">' . $UneLigne["NbPersonnes"] . '/' .  $vehicule['NbPlaceVehicule'] . ' passagers</span><br><div class="glyphicon glyphicon-flag"></div>  <b>' . $UneLigne["Destination"] . '</b><br>';
				echo '<input type="hidden" name="NoConducteurModif" value="' . $idcond . '">
					  <input type="hidden" name="DateModif" value="' . $iddate . '">
						  <input type="hidden" name="ImmatSaisi" value="' . $id . '">
						  <input type="hidden" name="ImmatSaisi" value="' . $id . '">';
				if($liste['DroitVehicule']) 
					echo '</button>';
				else
					echo '</div>';
				if($reservation != null)
				{
					echo '<input type="hidden" name="reservation" value="' . $reservation . '">';
				}
					//echo '<input class="btn btn-primary" type="submit" value="Modifier" name="Modifier">';
				echo '</form>';
				echo '</center></h4><br>';
			}
			echo '</div>';

		}
		echo'</div>';
		$compteur += 1;

	}

	
?>
</div>
</div>

</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
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
?>

<script>


<?php

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

function DonneLibelleJour($date)
{
	$date += 1;
	switch ($date) {
		case 0:
			return 'Dimanche';
			break;
		case 1:
			return 'Lundi';
			break;
		case 2:
			return 'Mardi';
			break;
		case 3:
			return 'Mercredi';
			break;
		case 4:
			return 'Jeudi';
			break;
		case 5:
			return 'Vendredi';
			break;
		case 6:
			return 'Samedi';
			break;
		
		default:
			return 'Dimanche';
			break;
	}
}

?>

