<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (($liste['DroitVehicule'] == 0) and ($liste['ReserverVehicule'] == 0))
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Demande de réservation d'un Véhicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	if(isset($_REQUEST['Immat']))
		$_SESSION['ImmatSaisi'] = $_REQUEST['Immat'];

	if(isset($_POST['ImmatSaisi']))
		$_SESSION['ImmatSaisi'] = $_POST['ImmatSaisi'];

	$id = $_SESSION['ImmatSaisi'];
	$conectBDD = new BDD();
	$listeConducteur = $conectBDD->Conducteur_Liste_ParUser($_SESSION['NoUtilisateur']);
	$listeProprietaire = $conectBDD->Proprietaire_Liste();
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$dateEtHeure = date('Y') . '-' . date('m') . '-' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');
	?>
	
	</head>
<body><div id="top-margin" class="top-margin">
<form method="POST">


<h1>Demander la Réservation d'un Véhicule</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$_POST['date'] = new DateTime($_POST['date']);
		$_POST['date'] = $_POST['date']->format('Y-m-d');
		$datedebut = $_POST['date'] . ' ' . $_POST['heuredebut'];
		$datedebut = new DateTime($datedebut);
		$datedebut = $datedebut->format('Y-m-d H:i');
		$datefin = $_POST['date'] . ' ' . $_POST['heurefin'];
		$datefin = new DateTime($datefin);
		$datefin = $datefin->format('Y-m-d H:i');
		$destination = $_POST['destination'];
		$conducteur = $_POST['conducteur'];

		if ($destination == "" )
		{
			$destination = null; 
		}

		if($datefin <= $datedebut)
		{
			?>
				<div class="alert alert-danger" role="alert">
				  	La Demande de Réservation de ce véhicule n'a pas été prise en compte. <b>L'heure de fin est inférieur à l'heure de début</b>.
				</div>
			<?php
		}
		else
		{

			$conectBDD = new BDD();
			$res = $conectBDD->Reserve_Create($id, $datedebut, $datefin, $destination, $conducteur, $dateEtHeure, $_POST['passagers']);
		
			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	La Demande de Réservation de ce véhicule à bien été enregistrée.
				</div>
				
				<?php
				$listeGestionnaire = $conectBDD->User_Liste_parDroit_SiGestionVehicule_MailAdminEtGest($id);
				//var_dump($listeGestionnaire);
					$debut = new DateTime($datedebut);
					$fin = new DateTime($datefin);
					$body = "
						Bonjour,<br>
								Une demande de réservation de véhicule vient d'être saisie pour le ". $debut->format('d/m/Y') .".<br>
								Merci de bien vouloir la valider.<br>
								<br>
								Cordialement,
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>
						";
					@$mail = '';
					require_once "phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					foreach ($listeGestionnaire as $ligne) {
						$mail->AddBCC($ligne['PseudoUtilisateur']);
					}
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Demande de réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail gestionnaire au destinataire.";
					}

					$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);

					$debut = new DateTime($datedebut);
					$fin = new DateTime($datefin);
					$body = "Bonjour,<br>
								Vous venez de réserver le véhicule " . $InfosVehicule['ConstructeurVehicule'] . " " . $InfosVehicule['ModeleVehicule'] 
								. " immatriculé " . $InfosVehicule['ImmatriculationVehicule'] . " pour le " . $debut->format('d/m/Y') ." de " . $debut->format('H:i') ." à " . $fin->format('H:i') .".<br>
								<br>
								Cette réservation est soumise à la validation de la sous direction des Affaires Générales.<br>
								Vous serez informé par mail de cette validation.<br>
								<br>
								Cordialement,<br>
								<br>
								La Sous-direction des Affaires Générales
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					@$mail = '';
					require_once "phpmailer/_lib/class.phpmailer.php";
					
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					$mail->AddBCC($liste['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Confirmation demande de réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail user au destinataire.";
					}
					?>

					<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=calendrier_vehicule.php"> 
					<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Erreur.
				</div>
				<?php
			}
		}
								
	}

	$color = @inverse_hexcolor($InfosVehicule['CouleurAffichageVehicule']);
	echo '<table class="table" style="background-color: ' . $InfosVehicule['CouleurAffichageVehicule'] . ';color:#' . $color . ';"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Nombre de Place</th></tr></tr>';

		echo '';
		echo '<tr><th>'.$InfosVehicule['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$InfosVehicule['ConstructeurVehicule'].' - ';
		echo  $InfosVehicule['ModeleVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['CouleurVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $InfosVehicule['NbPlaceVehicule'].'</td></tr>';
		
		echo '<tr><td colspan="5"><b><u>Gestionnaire de ce véhicule :</b></u> ';
		echo ' ' . $InfosVehicule['LieuVehicule'] . ' (Site de ' . $InfosVehicule['NomProprietaire'] . ')';
		echo '</td></tr>';
		echo '</table>';
		$dateEtHeure = date('Y') . '-' . date('m') . '-' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');
		$reserve = new DateTime($dateEtHeure);
?>		

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date et heure de Reservation</span>
  			<?php echo'<input type=text disabled required name="datedebut" value="'. $reserve->format('d/m/Y H:i') . '" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">'; ?>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Conducteur*</span>
				<select class="custom-select" style="width: 500px" name="conducteur" style="width: auto" required>
					<?php
					foreach ($listeConducteur as $uneLigne) {
						if($uneLigne['ActifConducteur'])
							echo '<option value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
					}
					?>
				</select>
				<?php
				if($liste['DroitVehicule']) 
					echo '<a class="btn btn-primary" href="creation_conducteur.php"><p>Créer un Conducteur</p></a>';
				?>
		</div><br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date d'utilisation*</span>
  			<input type="text" placeholder="Sélectionnez une date" required name="date" style="width: 200px" class="input-text form-control datepicker" aria-describedby="basic-addon1" id="datepickerAvecDateMin" autocomplete="off">
  		</div>

		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Début*</span>
  			<select class="custom-select" name="heuredebut" style="width: auto" required>
					<?php
					$compteur = 6;
					$fin = 21;
					while ($compteur <= $fin) {
						echo '<option value="'.$compteur.':00">'.$compteur.':00</option>';
						echo '<option value="'.$compteur.':30">'.$compteur.':30</option>';
						$compteur++;
					}
					?>
				</select>
  			<!--<input type="time" required name="heuredebut" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">-->
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Heure de Fin*</span>
  			<select class="custom-select" name="heurefin" style="width: auto" required>
					<?php
					$compteur = 6;
					$fin = 21;
					while ($compteur <= $fin) {
						echo '<option value="'.$compteur.':00">'.$compteur.':00</option>';
						echo '<option value="'.$compteur.':30">'.$compteur.':30</option>';
						$compteur++;
					}
					?>
				</select>
  			<!--<input type="time" required name="heurefin" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">-->
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Destination</span>
  			<input type="text" name="destination" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Passagers*</span>
				<select class="custom-select" name="passagers" style="width: auto" required>
					<?php
					$compteur = 0;
					while ($compteur < $InfosVehicule['NbPlaceVehicule']) {
						echo '<option value="'.$compteur.'">'.$compteur . '</option>';
						$compteur += 1;
					}
					?>
				</select>
		</div>
		<br>
		<h4><i class="glyphicon glyphicon-ok"></i> En réservant ce véhicule, j'accepte les <a href="conditions.php">conditions d'utilisations</a>.<br></h4>

		<br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button><br><br>

		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div>

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

</form>
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
?>
