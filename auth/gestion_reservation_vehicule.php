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
	<title>Réservations</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	
	?>
</head>
<body>
<div id="top-margin" class="top-margin">
<h1 class="title" style="display: inline-block;">
	DEMANDES DE RESERVATIONS DE VEHICULES
</h1>
	<?php
	if(isset($_POST["Accepter"]))
	{
		$id = $_POST['Immat'];
		$conducteur = $_POST['conducteur'];
		$DateHeureReservation = $_POST['DateHeureReservation'];
		$infosReservation = $conectBDD->Reserver_Liste_ParIdCondDateRes($id, $conducteur, $DateHeureReservation);
		$datedebut = $infosReservation['DateDebutUtilisation'];
		$datefin = $infosReservation['DateFinUtilisation'];
		$destination = $infosReservation['Destination'];
		/*var_dump($_POST['Immat']);
		var_dump($_POST['conducteur']);
		var_dump($_POST['DateHeureReservation']);
		var_dump($infosReservation);*/
		$DejaUtiliser = $conectBDD->Utilise_Verificaton_parImmatriculationEntre2Dates($id, $datedebut, $datefin, 0);
		if(!$DejaUtiliser)
		{

			$res = $conectBDD->Utilise_Create($id, $datedebut, $datefin, $destination, $conducteur, $infosReservation['NbPersonnes']);
			
			if($res)
			{

				$infosReservation = $conectBDD->Reserver_Liste_ParIdCondDateRes($id, $conducteur, $DateHeureReservation);
				$res2 = $conectBDD->Reserver_Delete($id, $conducteur, $DateHeureReservation);
				$Infosconducteur = $conectBDD->Conducteur_Infos_parId($conducteur);

				?>
				<div class="alert alert-success" role="alert">
				  	La Demande à bien été accepté.
				</div>
				<?php
				$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);

					$debut = new DateTime($infosReservation['DateDebutUtilisation']);
					$fin = new DateTime($infosReservation['DateFinUtilisation']);
					$body = "Bonjour,<br>
								Je vous informe que votre demande de réservation de véhicule " . $InfosVehicule['ConstructeurVehicule'] . " " . $InfosVehicule['ModeleVehicule'] 
								. " immatriculé " . $InfosVehicule['ImmatriculationVehicule'] . " pour le " . $debut->format('d/m/Y') ." de " . $debut->format('H:i') ." à " . $fin->format('H:i') ." a été validé par la Sous-direction des Affaires Générales.<br>
								<br>
								Cordialement,<br>
								<br>
								La Sous-direction des Affaires Générales
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					@$mail = '';
					require "phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					$mail->AddBCC($Infosconducteur['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Validation de votre demande de réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail au destinataire.";
					}
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Le Vehicule est déjà utilisé à cette heure-ci.
				</div>
				<?php
			}
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Le Vehicule est déjà utilisé à cette heure-ci.
			</div>
			<?php
		}

	}
	if(isset($_POST["Refuser"]))
	{
		$id = $_POST['Immat'];
		$conducteur = $_POST['conducteur'];
		$DateHeureReservation = $_POST['DateHeureReservation'];
		$infosReservation = $conectBDD->Reserver_Liste_ParIdCondDateRes($id, $conducteur, $DateHeureReservation);

		$res = $conectBDD->Reserver_Delete($id, $conducteur, $DateHeureReservation);
		if($res)
		{
			$Infosconducteur = $conectBDD->Conducteur_Infos_parId($conducteur);
			$datedebut = $infosReservation['DateDebutUtilisation'];
			$datefin = $infosReservation['DateFinUtilisation'];
			$destination = $infosReservation['Destination'];
			?>
			<div class="alert alert-warning" role="alert">
			  	La Demande à bien été refusée.
			</div>
			<?php
			$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
					$debut = new DateTime($infosReservation['DateDebutUtilisation']);
					$fin = new DateTime($infosReservation['DateFinUtilisation']);
					$body = "Bonjour,<br>
								Je vous informe que votre demande de réservation de véhicule " . $InfosVehicule['ConstructeurVehicule'] . " immatriculé " . $InfosVehicule['ModeleVehicule'] 
								. " " . $InfosVehicule['ImmatriculationVehicule'] . " pour le " . $debut->format('d/m/Y') ." de " . $debut->format('H:i') ." à " . $fin->format('H:i') ." a été refusé par la Sous-direction des Affaires Générales.<br>
								<br>
								Cordialement,<br>
								<br>
								La Sous-direction des Affaires Générales
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					@$mail = '';
					require "phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					$mail->AddBCC($Infosconducteur['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Refus de votre demande de réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail au destinataire.";
					}
					
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Refus impossible.
			</div>
			<?php
		}
	}
	if(isset($_POST["Annuler"]))
	{
		$id = $_POST['Immat'];
		$conducteur = $_POST['conducteur'];
		$DateHeureReservation = $_POST['DateHeureReservation'];

		$infosReservation = $conectBDD->Reserver_Liste_ParIdCondDateRes($id, $conducteur, $DateHeureReservation);
		$res = $conectBDD->Reserver_Delete($id, $conducteur, $DateHeureReservation);
		if($res)
		{
			?>
			<div class="alert alert-warning" role="alert">
			  	La Demande à bien été annulée
			</div>
			<?php
				$Infosconducteur = $conectBDD->Conducteur_Infos_parId($conducteur);
				$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
					$debut = new DateTime($infosReservation['DateDebutUtilisation']);
					$fin = new DateTime($infosReservation['DateFinUtilisation']);
					$body = "Bonjour,<br>
								Votre demande d'annulation  du véhicule " . $InfosVehicule['ConstructeurVehicule'] . " " . $InfosVehicule['ModeleVehicule'] 
								. " immatriculé " . $InfosVehicule['ImmatriculationVehicule'] . " pour le " . $debut->format('d/m/Y') ." de " . $debut->format('H:i') ." à " . $fin->format('H:i') ." a bien été pris en compte.<br>
								<br>
								Cordialement,<br>
								<br>
								La Sous-direction des Affaires Générales
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					@$mail = '';
					require "phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					$mail->AddBCC($Infosconducteur['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Annulation de votre demande de réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail au destinataire.";
					}
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Annulation impossible.
			</div>
			<?php
		}
	}
	if(isset($_POST["SupprimerDeplacement"]))
	{

		$InfosDeplacement = $conectBDD->Utilise_Infos_parId($_POST['SupprimerDeplacement']);
		$res = $conectBDD->Utilise_Delete($_POST["SupprimerDeplacement"]);

		if($res)
		{
			?>
			<div class="alert alert-warning" role="alert">
			  	La Réservation du véhicule à bien été supprimée.
			</div>
			<?php
				$Infosconducteur = $conectBDD->Conducteur_Infos_parId($InfosDeplacement['NoConducteur']);
				$InfosVehicule = $conectBDD->Vehicule_Infos_parId($InfosDeplacement['ImmatriculationVehicule']);

				$debut = new DateTime($InfosDeplacement['DateDebutUtilisation']);
					$fin = new DateTime($InfosDeplacement['DateFinUtilisation']);
					$body = "Bonjour,<br>
								Votre demande d'annulation de la réservation du véhicule " . $InfosVehicule['ConstructeurVehicule'] . " " . $InfosVehicule['ModeleVehicule'] 
								. " immatriculé " . $InfosVehicule['ImmatriculationVehicule'] . " pour le " . $debut->format('d/m/Y') ." de " . $debut->format('H:i') ." à " . $fin->format('H:i') ." a bien été pris en compte.<br>
								<br>
								Cordialement,<br>
								<br>
								La Sous-direction des Affaires Générales
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					@$mail = '';
					require "phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Crous Auto/Velo';
					$mail->AddBCC($Infosconducteur['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Annulation de votre réservation";
					$mail->MsgHTML($body);
					if(!$mail->Send()){
					    echo "Erreur lors de l'envoi du mail au destinataire.";
					}
					
				?>
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Impossible de supprimer cette réservation.
			</div>
			<?php
		}
	}
	if($liste['NoResidence'] == 0)
		$listeReservation = $conectBDD->Reserver_Liste();
	else
	{
		if($liste['NoDroit'] == 1){
			$listeReservation = $conectBDD->Reserver_Liste_ParResidence($_SESSION['NoResidence']);
		}
		else {
			$listeReservation = $conectBDD->Reserver_Liste_ParUser_SiGestion((int)$_SESSION['NoUtilisateur'], (int)$_SESSION['NoProprietaire']);
		}
	}

	?>
<div class="table-responsive">
<table class="table"><tr><th scope="col">Date de Réservation</th><th scope="col">Véhicule</th><th scope="col">Type</th><th scope="col">Conducteur</th><th scope="col">Date</th><th scope="col">Horaires</th><th scope="col">Destination</th><th scope="col">Actions</th>
</tr>

	<?php
	if($listeReservation == null)
	{
		echo '<td colspan="8"><center><b>Aucune demande de réservation n\'est actuellement en cours</b></center></td>';
	}
	else
	{
			foreach ($listeReservation as $uneLigne) {
			echo '';
			$DateHeure = new DateTime($uneLigne['DateHeureReservation']);
			echo '<th>'.$DateHeure->format('d/m/Y H:i').'</th>';
			echo '<th>' . $uneLigne['ConstructeurVehicule'] . ' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</th>';
			$id = $uneLigne['ImmatriculationVehicule'];
			echo '<td>'.$uneLigne['TypeVehicule'].' ';
			if($uneLigne['LibreServiceVehicule'] == 1)
				echo '<span class="badge badge-success">Réservable</span></td>';

			$uneLigne['NbPlaceVehicule']-=1;
			echo '<td>'.$uneLigne['NomConducteur']. ' ' .$uneLigne['PrenomConducteur'].' <span class="badge">' . $uneLigne["NbPersonnes"] . '/' . $uneLigne["NbPlaceVehicule"] . ' passagers</span></td>';
			$DateDebut = new DateTime($uneLigne['DateDebutUtilisation']);
			$DateFin = new DateTime($uneLigne['DateFinUtilisation']);
			echo '<td>le '. $DateDebut->format('d/m/Y') .'</td><td>' . $DateDebut->format('H:i') .' - ' . $DateFin->format('H:i') . '</td>';
			echo '<td>'.$uneLigne['Destination'].'</td>';
			if($liste['DroitVehicule']) 
			{
			echo '<td>';
			echo '<form method="POST" style="display:inline-block">
				<input type="hidden" name="Immat" value="'.$uneLigne['ImmatriculationVehicule']. '">
				<input type="hidden" name="conducteur" value="'.$uneLigne['NoConducteur']. '">
				<input type="hidden" name="DateHeureReservation" value="'.$uneLigne['DateHeureReservation']. '">
				<button type="submit" name="Accepter" class="btn btn-success">
					<i class="glyphicon glyphicon-ok"></i> Accepter
				</button>
				<button type="submit" name="Refuser" class="btn btn-danger">
					<i class="glyphicon glyphicon-remove"></i> Refuser
				</button>
				</form>
				<form method="POST" style="display:inline-block" action="modif_reservation_vehicule.php">
				<button type="submit" name="idReservation" class="btn btn-warning" value="' . $uneLigne['idReservation'] . '">
					<i class="glyphicon glyphicon-pencil"></i> Modifier
				</button>

				</form>
				</td>';

			}
			else
			{
				echo '<td>';
				$VerifReservation = $conectBDD->Conducteur_Liste_ParUserEtConducteur($_SESSION['NoUtilisateur'], $uneLigne['NoConducteur']);
				if($VerifReservation)
					echo '<form method="POST" style="display:inline-block">
					<input type="hidden" name="Immat" value="'.$uneLigne['ImmatriculationVehicule']. '">
					<input type="hidden" name="conducteur" value="'.$uneLigne['NoConducteur']. '">
					<input type="hidden" name="DateHeureReservation" value="'.$uneLigne['DateHeureReservation']. '">
					
					<button type="submit" name="Annuler" class="btn btn-danger">
						<i class="glyphicon glyphicon-remove"></i> Annuler
					</button> 
					</form>
					<form method="POST" style="display:inline-block" action="modif_reservation_vehicule.php">
						<button type="submit" name="idReservation" class="btn btn-warning" value="' . $uneLigne['idReservation'] . '">
							<i class="glyphicon glyphicon-pencil"></i> Modifier
						</button> &nbsp

					</form>
					</td>';
			}
			echo'</tr>';
		}
	}
	
	echo '</table></div>';

	$listeDeplacement = $conectBDD->Utilise_Liste_parUser((int)$_SESSION['NoUtilisateur']);
?>
<br>
<h1 class="title" style="display: inline-block;">
	LISTE DE VOS RESERVATIONS ACCEPTEES
</h1>
<div class="table-responsive">
	<table class="table"><tr><th scope="col">Véhicule</th><th scope="col">Type</th><th scope="col">Conducteur</th><th scope="col">Date</th><th scope="col">Horaires</th><th scope="col">Destination</th><th scope="col">Action</th>
	</tr>
	<?php
	if($listeDeplacement == null)
	{
		echo '<tr><td colspan="8"><center><b>Aucune réservation acceptées récente</b></center></td></tr>';
	}
	else
	{

		foreach ($listeDeplacement as $uneLigne) {
			echo '<tr>';
			//var_dump($uneLigne);
			echo '<th>' . $uneLigne['ConstructeurVehicule'] . ' ' . $uneLigne['ModeleVehicule'] . ' ('.$uneLigne['ImmatriculationVehicule'].')</th>';
			$id = $uneLigne['ImmatriculationVehicule'];
			echo '<td>'.$uneLigne['TypeVehicule'].' ';
			if($uneLigne['LibreServiceVehicule'] == 1)
				echo '<span class="badge badge-success">Réservable</span></td>';

			$uneLigne['NbPlaceVehicule']-=1;
			echo '<td>'.$uneLigne['NomConducteur']. ' ' .$uneLigne['PrenomConducteur'].' <span class="badge badge-default">' . $uneLigne["NbPersonnes"] . '/' . $uneLigne["NbPlaceVehicule"] . ' passagers</span></td>';
			$DateDebut = new DateTime($uneLigne['DateDebutUtilisation']);
			$DateFin = new DateTime($uneLigne['DateFinUtilisation']);
			echo '<td>le '. $DateDebut->format('d/m/Y') .'</td><td>' . $DateDebut->format('H:i') .' - ' . $DateFin->format('H:i') . '</td>';
			echo '<td>'.$uneLigne['Destination'].'</td>';
			if($DateFin->format('Y-m-d') >= date('Y-m-d')) 
			{
			echo '<td>';
			echo '<form method="POST" style="display:inline-block">
				<button type="submit" value=" ' . $uneLigne['idDeplacement'] . '" name="SupprimerDeplacement" class="btn btn-danger">
					<i class="glyphicon glyphicon-remove"></i> Annuler mon déplacement
				</button>
				</form>';
				echo '</td>';
			}
			else
				echo '<td></td>';


			echo '</tr>';
		}
	}
			?>
</table></div>
</div>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>