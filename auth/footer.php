<?php 
require_once('bdd.php');
	$connectBDD = new BDD();
	$liste = $connectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
	//var_dump($_SESSION['pseudo']);
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<div id='button-top-parent'><img class="back-to-top" src="ressources/responsive/back-to-top.png"></div>
</body>
<footer>
	<div id="footer">
		<div class="footer1">
					<ul>
						<li><h3>CONTACT</h3></li>
						<li><?php echo $liste['NomResidence']; ?></li>
						<li><?php echo $liste['AdresseResidence']; ?></li>
						<li><?php echo $liste['CPResidence'] . ' ' . $liste['VilleResidence']; ?> </li>
						<li><?php echo $liste['TelephoneResidence']; ?></li>
					</ul>
				</div>
		
		<div class="footer2">
			<ul>
				<li><h3>MON COMPTE</h3></li>
				<li>Pseudo : <?php echo $_SESSION['pseudo'] ?></li>
				<li>Modération : <?php echo $_SESSION['NomDroit'] ?> </li>
				<li>Résidence administrative : <?php echo $_SESSION['NomResidence'] ?></li>
				<li>Site : <?php echo $_SESSION['NomProprietaire'] ?></li>
				<li><a href="deconnexion.php">Déconnexion</a></li>
			</ul>
		</div>
		<div class="footer3">
			<ul>
				<li><h3>RESSOURCES</h3></li>
				<li><a href="http://www.crous-bfc.fr/">Site web du Crous BFC</a></li>
				<li><a href="conditions.php">Conditions d'utilisations</a></li>
				<li><a href="manuel.pdf">Manuel d'aide pour l'utilisateur</a></li>
				<?php
					if($_SESSION['NoDroit'] == 1)
					echo '<li><a href="maintenance.php">Mettre l\'application en maintenance</a></li>';
					?>
			</ul>
		</div>
	</div>
</footer>
</html>



<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js?ver=1.3.2'></script>
<script src='fullcalendar-3.10.0/lib/jquery.min.js'></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src='fullcalendar-3.10.0/lib/moment.min.js'></script>
<script src='fullcalendar-3.10.0/fullcalendar.min.js'></script>
<script src='fullcalendar-3.10.0/locale/fr.js'></script>
<script src="js/main.js"></script>
<script src="highcharts.js"></script>
<script src="exporting.js"></script>
<script src="export-data.js"></script>
<script src="bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script src="js/menu.js"></script>

<?php
$path = $_SERVER['PHP_SELF'];
$file = basename ($path);
if($file == 'calendrier_vehicule.php')
{
	require_once('bdd.php');
	$conectBDD = new BDD();

	if($liste['NoProprietaire'] == 0)
		$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_ParProprietaire($_SESSION['NoResidence']);
		else
			$listeUtilisation = $conectBDD->UtiliseEtReserve_Liste_parImmatriculation_SiGestion($_SESSION['NoUtilisateur'], $_SESSION['NoProprietaire']);
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
			$color=$color.StrToUpper(substr('0'.dechex($cl[$i]),-2));
		}
		return ''.$color; 
	}
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
	
	?>

	<script type="text/javascript">

		<?php
		$now = date('Y-m-d');
		$now = new DateTime($now);
		$now = $now->format('Y-m-d')
		?>
		var date = <?php echo '"' . $now . '"';?>;

	    $('#calendar').fullCalendar({
		  locale: 'fr',
		  allDaySlot: false,
	      header: {
	        left: 'prev,next',
	        center: 'title',
			right: 'month,agendaWeek,agendaDay',
	      },
	      defaultDate: date,
		  defaultView: 'agendaWeek',
		  minTime : "06:00:00",
		  timeFormat: 'H:mm',
		  firstDay : 1,
	      navLinks: false, // can click day/week names to navigate views
	      editable: false,
	      eventLimit: false,
	      slotEventOverlap: false,
	      events: [
		 
			<?php  
			foreach ($listeUtilisation as $UneLigne) {
					$reservation = $UneLigne['DateHeureReservation'];
					$motif = $UneLigne['Destination'];
					$idReservation = $UneLigne['id'];
					$dateHeureDebut = $UneLigne['DateDebutUtilisation'];
					$dateHeureFin = $UneLigne['DateFinUtilisation'];
					$couleurAffichageUtilisateur = "".$UneLigne['CouleurAffichageVehicule'];
					
					$dateHeureDebut = new DateTime($dateHeureDebut);
					$dateDebut = $dateHeureDebut->format('Y-m-d');
					$heureDebut = $dateHeureDebut->format('H:i');
					
					$dateHeureFin = new DateTime($dateHeureFin);
					$datefin = $dateHeureFin->format('Y-m-d');
					$heurefin = $dateHeureFin->format('H:i');
					
					$dateHeureDebut = ''.$dateDebut.'T'.$heureDebut;
					$dateHeureFin = ''.$datefin.'T'.$heurefin;
					$UneLigne['NbPlaceVehicule'] -= 1;
					
					$motif = $UneLigne['ConstructeurVehicule'] . ' ' . $UneLigne['ModeleVehicule'] . '\n' . $UneLigne['NomConducteur'] . ' ' . $UneLigne['PrenomConducteur'] .'\n' .  $UneLigne["NbPersonnes"] . '/' . $UneLigne['NbPlaceVehicule'] . ' passagers' . '\n ' . $motif;
					$className = "default";
					if ($reservation != null)
					{
						//$motif = $motif . '\n' . '(En attente de validation)';
						$className = "reserve" . $idReservation ."";
						$url = "modif_reservation_vehicule.php?idReservation=".$idReservation."";
					}
					else
						$url = "modif_deplacement_vehicule.php?idDeplacement=".$idReservation."";
						
					echo "
					{
					title : '".$motif."',
					start : '".$dateHeureDebut."',
					end : '".$dateHeureFin."',
					color : '".$couleurAffichageUtilisateur."',
					textColor: 'black',
					className: '".$className."'";
						echo ", url : '".$url."'";
					echo"},
					";
			}

			?>
	      ],
	       
	      
		 eventRender: function(event, element) {
		 	 <?php  
			foreach ($listeUtilisation as $UneLigne) {
					$motif = $UneLigne['Destination'];
					$idReservation = $UneLigne['id'];
					$dateHeureDebut = $UneLigne['DateDebutUtilisation'];
					$dateHeureFin = $UneLigne['DateDebutUtilisation'];
					$couleurAffichageUtilisateur = "".$UneLigne['CouleurAffichageVehicule'];
					$couleurAffichageFoncee =  "#" . ChangerTonCouleur($couleurAffichageUtilisateur, -50);
					$hachure = " 'background' : 'repeating-linear-gradient( 45deg, " . $couleurAffichageUtilisateur . ",  " . $couleurAffichageUtilisateur . " 10px,  " . $couleurAffichageFoncee . " 10px,  " . $couleurAffichageFoncee . " 15px)'";
					$couleurAffichageTexte = @inverse_hexcolor($couleurAffichageUtilisateur);
					echo "if (event.className == 'reserve". $idReservation ."') 
					{
	           			element.css({
	           				'font-weight' : 'bold',
	           				'text-align' : 'center',

	                ".$hachure."});
	       			}
	       			else{
	       				element.css({
	       					'font-weight' : 'bold',
	           				'text-align' : 'center'});
	           			}";
					}?> 
	        
	    
	    }, 
	    });


	</script>
	<?php
	
}
?>