<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion Alertes Mail</title>
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
  	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
  	<meta charset="utf-8">
  	<?php
	require_once('auth/bdd.php');
	$conectBDD = new BDD();
	
	require_once('auth/gestionAlerte.php');
?>
</head>
<body>
	<div id="top-margin" class="top-margin">

<h1>Gestion Alertes Mail </h1><br>(envoi 1 mois avant les actions à effectuer)
<?php

$listeProprietaire = $conectBDD->Proprietaire_Liste();

foreach ($listeProprietaire as $UnProprietaire) {
	echo "<h2>" . $UnProprietaire['NomProprietaire'] . "</h2>";

	$listeVehicule = $conectBDD->Vehicule_Liste_ParProprietaire($UnProprietaire['NoProprietaire']);
	
		$listeAlerte = null;
		foreach ($listeVehicule as $vehicule) {
			$Alerte = VerifAlerte($vehicule['ImmatriculationVehicule'], 1);

			if($Alerte != null)
			{
				$listeGestionnaire = $conectBDD->User_Liste_parDroitEtProprietaire($UnProprietaire['NoProprietaire'], 2);
				//var_dump($listeGestionnaire);
				foreach ($listeGestionnaire as $ligne) {
					
					$destinataire = $ligne['PseudoUtilisateur'];
					$body = "Bonjour,<br>
								Je vous informe que 
								" . $Alerte . "
								Je vous prie de bien vouloir prendre rendez-vous au centre de contrôle habituel.<br>
								Une fois le contrôle effectué, je vous remercie de bien vouloir scanner la fiche de contrôle à la sous-direction des Affaires Générales (adresse mail : <a href='mailto:catherine.fritsch@crous-bfc.fr'>catherine.fritsch@crous-bfc.fr</a>).<br>
								<br>
								<div class='nota'>Cet email vous a été envoyé depuis un mail automatique, merci de ne pas y répondre.</div>";
					echo '<br>-> à <u>' . $destinataire . '</u><br>';
					@$mail = '';
					echo $body;
					require "auth/phpmailer/_lib/class.phpmailer.php";
					$mail = new PHPmailer();
					$mail->IsSMTP();
					$mail->Host = "10.253.50.7";
					$mail->From='crous-auto-velo@crous-bfc.fr';
					$mail->FromName='Alerte Crous Auto/Velo';
					$mail->AddBCC($ligne['PseudoUtilisateur']);
					$mail->CharSet = 'UTF-8';
					$mail->Subject="Le Véhicule " . $vehicule['ImmatriculationVehicule'] . " nécessite votre attention";
					$mail->MsgHTML($body);
					if(!$mail->Send())
					    echo "Erreur lors de l'envoi du mail au destinataire.";
					else
						echo "Mail envoyé correctement.";
					echo '------------------------------------------------------------------------------------------------------------------------------';
				}
			}
		}

	
	echo '</div>';
	echo '</div>';
}
?>