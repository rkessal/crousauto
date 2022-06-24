<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: index.php');
}

if(isset($_REQUEST['Immat']))
	$_SESSION['ImmatSaisi'] = $_REQUEST['Immat'];

//var_dump($_SESSION['Immat']);?>
<!DOCTYPE html>
<html>
<head>
	<title>Fiche Véhicule</title>
  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeUser = $conectBDD->User_Liste();
	
	$infosVehicule = $conectBDD->Vehicule_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$infosControles = $conectBDD->Controle_Technique_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$infosOccurences = $conectBDD->Occurence_Element_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$infosEntretien = $conectBDD->Entretien_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$infosDeplacement = $conectBDD->Utilise_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$UtilisationVehicule = $conectBDD->Utilisation_Infos_parId($_SESSION['ImmatSaisi']);
	$infosAssurance = $conectBDD->Assurance_Liste_parImmatriculation($_SESSION['ImmatSaisi']);
	$infosKm = $conectBDD->Kilometrage_Liste_parImmatriculation($_SESSION['ImmatSaisi']);

	?>
</head>
<body>
<div id="top-margin" class="top-margin">
<button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button><h1 class="title" style="display: inline-block;">Dossier du Véhicule</h1>&nbsp&nbsp
<?php 
	$dispo = true;
	$dateNow = date('Y-m-d') . " " . date('H') . ":" . date('i') . ":" . date('s');
	foreach ($UtilisationVehicule as $dispoLigne) {
		if($dispoLigne['DateDebutUtilisation'] <= $dateNow and $dateNow <= $dispoLigne['DateFinUtilisation'])
			$dispo = false;
	}
	if($dispo)
		echo '<h1 style="display: inline-block"><span class="badge badge-success">Disponible</span></h1>';
	else
	echo '<h1 style="display: inline-block"><span class="badge badge-danger">En Déplacement</span></h1>';
		?><br>
		<?php
		if($liste['NoDroit'] == 1)
			echo '<a class="btn btn-primary" href="modif_vehicule.php"><p><div class="glyphicon glyphicon-pencil"></div> Modifier le Véhicule</p></a>';
		 
		if($infosVehicule['TypeVehicule'] != "Velo" and $infosVehicule['LocationVehicule'] == 0)
		{
			?>
			<a class="btn btn-primary" href="cout_fonctionnement_vehicule.php"><p><div class="glyphicon glyphicon-eur"></div> Coût de fonctionnement du véhicule</p></a> 

			<a class="btn btn-primary" href="entretien_vehicule.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir un entretien</p></a> 
			<a class="btn btn-primary" href="ct_vehicule.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir un contrôle technique</p></a> 
			<?php
		}
			?>
		 <a class="btn btn-primary" href="deplacement_vehicule.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une réservation</p></a> 
		 <a class="btn btn-primary" href="reservation_vehicule.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une demande de réservation</p></a> 
	<div class="tr-ajust">
		<table class="table">
			
				<tr><th scope="row">Type</th> <th> <?php echo $infosVehicule['TypeVehicule']; ?></th></tr>
				<tr><th scope="col" style="width:40%">Immatriculation</th><th scope="col"> <?php echo $infosVehicule['ImmatriculationVehicule'] . ' ';
				if($infosVehicule['ActifVehicule'] == 1)
					echo '<span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div> Actif</span>';
				else
					echo '<span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div> Désactivé</span>';
				 ?> </th></tr>
				<tr><td scope="row">Date de 1ère Imatriculation</td> <td><?php 
				$DateImmat= new DateTime($infosVehicule['DatePremiereImmatriculationVehicule']);
				echo $DateImmat->format('d/m/Y'); 
				if($infosVehicule['PrixAchatVehicule'] == '' or $infosVehicule['PrixAchatVehicule'] == null)
					$infosVehicule['PrixAchatVehicule'] = 0;
				if($infosVehicule['KilometrageVehicule'] == '' or $infosVehicule['KilometrageVehicule'] == null)
					$infosVehicule['KilometrageVehicule'] = 0;

				?> </td></tr>
				<tr><td scope="row">Prix d'Achat</td> <td><?php echo $infosVehicule['PrixAchatVehicule'] . ' €'; ?> </td></tr>
				<tr><th scope="row">Constructeur</th> <th> <?php echo $infosVehicule['ConstructeurVehicule']; ?> </th></tr>
				<tr><th scope="row">Modèle</th> <th> <?php echo $infosVehicule['ModeleVehicule']; ?></th></tr>
				<tr><td scope="row">Couleur</td> <td><?php echo $infosVehicule['CouleurVehicule']; ?> </td></tr>
				<tr><td scope="row">Carburant</td> <td> <?php echo $infosVehicule['TypeCarburantVehicule']; ?></td></tr>



				<tr><td scope="row">Kilometrage &nbsp;&nbsp;&nbsp;&nbsp;<a href="kilometrage_vehicule.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Ajouter</a></td><td>
				<?php
				echo '<form method="post" action="modif_kilometrage_vehicule.php"><ul>';
				if($infosKm)
				{
					foreach ($infosKm as $unKm) {
						$Date= new DateTime($unKm['DateKilometrage']);
						echo '<li>le ' . $Date->format('d/m/Y') . ', ' . $unKm['KilometrageReleve'] . ' kms';
						echo '<button class="btn btn-link" name="DateKilometrage" value="' . $unKm['DateKilometrage'] . '">modifier</button></li>';
					}
					
				}
					
				echo '<li> à l\'achat ' . $infosVehicule['KilometrageVehicule'] . ' kms</li>';
				echo '</ul></form>';
				?>
				</td>
				<?php
				if($infosVehicule['TypeVehicule'] != "Velo")
					echo '<tr><td scope="row">Nombre de Place</td> <td> ' . $infosVehicule['NbPlaceVehicule'] . '</td></tr>';
				if($infosVehicule['TypeVehicule'] != "Velo")
					echo '<tr><td scope="row">Nombre de Portes</td> <td> ' . $infosVehicule['NbPorteVehicule'] . '</td></tr>';
				?>

				<tr><td scope="row">Type de Rapport</td> <td><?php echo $infosVehicule['TypeRapportVehicule']; ?> </td></tr>

				<?php
				if($infosVehicule['TypeVehicule'] != "Velo")
					echo '<tr><td scope="row">Puissance Fiscal</td> <td> ' . $infosVehicule['PuissanceVehicule'] . '</td></tr>';
				?>

				<tr><td scope="row">Résidence Administrative</td> <td><?php echo $infosVehicule['NomResidence'] . ' (Site de ' . $infosVehicule['NomProprietaire'] . ')'; ?> </td></tr>

				<tr><td scope="row">Lieu</td> <td><?php 
				echo $infosVehicule['LieuVehicule']; ?> </td></tr>
				<tr><td scope="row">Réservable</td><td>
				<?php
				if($infosVehicule['LibreServiceVehicule'] == 1)
					echo '<h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div> Oui</span></h2>';
				else
					echo '<h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div> Non</span></h2>';?> </td></tr>
				<tr><td scope="row">Location</td><td>
				<?php
				if($infosVehicule['LocationVehicule'] == 1)
					echo '<h2><span class="badge badge-success"><div class="glyphicon glyphicon-ok"> </div> Oui</span></h2>';
				else
					echo '<h2><span class="badge badge-danger"><div class="glyphicon glyphicon-remove"> </div> Non</span></h2>';?> </td></tr>
				</tr>
				<tr><td scope="row">Assurance &nbsp;&nbsp;&nbsp;&nbsp;<a href="assurance_vehicule.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Ajouter</a></td><td>
					<?php
					if($infosAssurance)
					{
						echo '<form method="post" action="modif_assurance_vehicule.php"><ul>';
						foreach ($infosAssurance as $uneDate) {
							$Date= new DateTime($uneDate['DateAssurance']);
							echo '<li>le ' . $Date->format('d/m/Y') . ', paiement de ' . $uneDate['MontantAssurance'] . ' € à ' . $uneDate['NomAssurance'];
							echo '<button class="btn btn-link" name="DateAssurance" value="' . $uneDate['DateAssurance'] . '">modifier</button></li>';
						}
						echo '</ul></form>';
					}
					else
						echo '<font style="color:red">aucune assurance</style>';
					?>
				</td>
		</div>

		</table>

<br>
<?php
if($infosVehicule['TypeVehicule'] != "Velo" and $infosVehicule['LocationVehicule'] == 0)
{
	?>
	<h1 style="display: inline-block;" class="title">Fréquence des Entretiens</h1>
	<div class="enregistrement-boite">
	<table class="table"><tr><th scope="col">Libellé</th><th>Fréquence</th><th scope="col">Action</th></tr>
	<?php
		if($infosOccurences==null)
		{
			echo '<td colspan="7"><center><b>Aucun Fréquence d\'Entretien enregistré</b></center></td>';
		}
		else
		{
			foreach ($infosOccurences as $uneLigne) {
				echo '';
				echo '<tr><th scope="row">'.$uneLigne['LibelleElement'].'</th>';
				echo '<td> tout les <b>'.$uneLigne['OccurenceKmElement'].' km</b> ou tout les <b>'.$uneLigne['OccurenceMoisElement'].' mois</b></td>';

				$idElement = $uneLigne['NoElement'];
				$_SESSION['idElement'] = $idElement;
				$id = $_SESSION['ImmatSaisi'];
				echo '<td> &nbsp
					<form style="display: inline-block;" method="post" action="modif_occurence_element_vehicule.php">
					<input type="hidden" name="idModif" value="' . $id . '">
					<input type="hidden" name="EntretienModif" value="' . $idElement . '">
					<button type="submit" name="Modifier" class="btn btn-primary">
					<i class="glyphicon glyphicon-pencil"></i>  Modifier
				</button> &nbsp
					</form>
				</td></tr>';
			}
		}
		echo '</table></div>';

	?>
	<br>


<br>

<h1 style="display: inline-block;" class="title">Liste des Entretiens</h1>
<div class="enregistrement-boite">
<table class="table"><tr><th scope="col">Date</th><th scope="col">Type</th><th scope="col">Opération</th><th scope="col">Kilometrage</th><th scope="col">Document</th><th scope="col">Observations</th><th scope="col">Montant</th><th scope="col">Action</th></tr>

<?php


	if($infosEntretien==null)
	{
		echo '<td colspan="8"><center><b>Aucun Entretien enregistré</b></center></td>';
	}
	else
	{
		echo'';
		foreach ($infosEntretien as $uneLigne) {
			echo '';
			$Date= new DateTime($uneLigne['DatePassageEntretien']);
			echo '<tr><th scope="row">'.$Date->format('d/m/Y').'</th>';
			echo '<th scope="row">'.$uneLigne['TypeEntretien'].'</th>';
			echo '<th scope="row">'.$uneLigne['LibelleElement'].'</th>';
			echo '<td>'.$uneLigne['Kilometrage'].'</td>';

			$doc = $uneLigne['Document'];
			$idEntretien = $uneLigne['idEntretien'];
			$_SESSION['idEntretien'] = $idEntretien;
			$dateEntretien = $uneLigne['DatePassageEntretien'];
			$_SESSION['DatePassageEntretien'] = $dateEntretien;
			echo '<td> <a href="doc/' . $doc . '">'. $doc .'</td>';
			echo '<td>'.$uneLigne['Observations'].'</td>';
			echo '<td>' .  $uneLigne['MontantEntretien'] . ' €</td>';
			$id = $_SESSION['ImmatSaisi'];
			echo '<td> &nbsp
				<form style="display: inline-block;" method="post" action="modif_entretien_vehicule.php">
				<input type="hidden" name="idModif" value="' . $id . '">
				<input type="hidden" name="EntretienModif" value="' . $idEntretien . '">
				<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
				</form>
			</td></tr>';
		}
	}
	echo '</table>';

?>

</div>
<br>
<h1 style="display: inline-block;" class="title">Liste des Contrôles Techniques</h1>
<div class="enregistrement-boite">
<table class="table"><tr><th scope="col">Date</th><th scope="col">Type</th><th scope="col">Kilometrage</th><th scope="col">Document</th><th scope="col">Statut</th><th scope="col">Montant</th><th scope="col">Action</th></tr>
<?php
	if($infosControles==null)
	{
		echo '<td colspan="6"><center><b>Aucun Contrôle Technique enregistré</b></center></td>';
	}
	else
	{
		foreach ($infosControles as $uneLigne) {
		echo '';
		$Date = new DateTime($uneLigne['DatePassageControle']);
		echo '<tr><th scope="row">'.$Date->format('d/m/Y').'</th>';
		echo '<th scope="row">'.$uneLigne['TypeControle'].'</th>';
		echo '<td>'.$uneLigne['KilometrageControle'].'</td>';
		$doc = $uneLigne['DocumentControle'];
		echo '<td> <a href="doc/' . $doc . '">'. $doc .'</td>';
		$idcontrole = $uneLigne['NoControle'];
		$_SESSION['NoControle'] = $idcontrole;
		if($uneLigne['OkControle'])
			echo '<td><h2><span class="badge badge-success">Accepté</span></h2></td>';
		else
			echo '<td><h2><span class="badge badge-danger">Ajourné</span></h2></td>';
		echo '<td>' .  $uneLigne['MontantControle'] . ' €</td>';
		$id = $_SESSION['ImmatSaisi'];

		$iddate = $uneLigne['DatePassageControle'];
		echo '<td> &nbsp
			<form style="display: inline-block;" method="post" action="modif_ct_vehicule.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<input type="hidden" name="ControleModif" value="' . $idcontrole . '">
			<input type="hidden" name="DateModif" value="' . $iddate . '">
			<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
			</form>
		</td></tr>';
		}
	}
	
	echo '</table></div>';

	$ListeUtilisationCarte = $conectBDD->CarteEssence_Liste_ParImmat($_SESSION['ImmatSaisi']);
	$idCarte = $ListeUtilisationCarte['NoCarte'];
	$ListeUtilisationT = $conectBDD->Telepeage_Liste_ParImmat($_SESSION['ImmatSaisi']);
	$idT = $ListeUtilisationT['NoTelepeage'];
	$montantT = $ListeUtilisationT['Montant'];
	$infosCarte = $conectBDD->Carte_Essence_Infos_parImmat($_SESSION['ImmatSaisi']);
	$infosT = $conectBDD->Telepeage_Infos_parImmat($_SESSION['ImmatSaisi']);

}
?>

	<br>
<h1 style="display: inline-block;" class="title">Liste des Déplacements</h1>
<div class="enregistrement-boite">
<table class="table"><tr><th scope="col">Date</th><th scope="col">Horaires</th><th scope="col">Destination</th><th scope="col">Conducteur</th><th scope="col">Action</th></tr>
<?php
	if($infosDeplacement==null)
	{
		echo '<td colspan="5"><center><b>Aucun Déplacement enregistré</b></center></td>';
	}
	else
	{
		foreach ($infosDeplacement as $uneLigne) {
			$DateDebutUtilisation= new DateTime($uneLigne['DateDebutUtilisation']);
			$DateFinUtilisation= new DateTime($uneLigne['DateFinUtilisation']);
			echo '';
			echo '<tr><th scope="row">'.$DateDebutUtilisation->format('d/m/Y').'</th>';
			echo '<th scope="row">'.$DateDebutUtilisation->format('H:i').' - '.$DateFinUtilisation->format('H:i').'</th>';
			$uneLigne['NbPlaceVehicule'] -= 1;
			echo '<td>'.$uneLigne['Destination'].'</td>';
			echo '<td>'.$uneLigne['NomConducteur'].' '.$uneLigne['PrenomConducteur'].' <span class="label label-default">' . $uneLigne["NbPersonnes"] . '/' . $uneLigne['NbPlaceVehicule'] . ' passagers</span></td>';
			$idDeplacement = $uneLigne['idDeplacement'];
			echo '<td> &nbsp
				<form style="display: inline-block;" method="post" action="modif_deplacement_vehicule.php">
				<button type="submit" name="idDeplacement" class="btn btn-primary" value="' . $idDeplacement . '">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
				</form>
			</td></tr>';
		}
	}
	echo '</table></div>';
	
	$infosCarte = $conectBDD->Carte_Essence_Infos_parImmat($_SESSION['ImmatSaisi']);
	$infosT = $conectBDD->Telepeage_Infos_parImmat($_SESSION['ImmatSaisi']);
	//var_dump($infosCarte);
	//var_dump($infosT);
?>
<?php
if($infosVehicule['TypeVehicule'] != "Velo")
{
	?>
<br>

<h1 class="title">Gestion de la Carte Essence et du Badge Télépéage</h1>

<?php
	if($infosCarte != null)
	{
		echo '<form style="display: inline-block;" method="post" action="liste_carte_essence.php">';
				$idCarte = $infosCarte['NoCarte'];
				$id = $_SESSION['ImmatSaisi'];
				$montantCarte = $infosCarte['Montant'];
		echo '<input type="hidden" name="idListe" value="' . $idCarte . '">
				<input type="hidden" name="Montant" value="' . $montantCarte . '">
				<input type="hidden" name="id" value="' . $id . '">
				<button type="submit" name="Modifier" class="btn btn-primary">
					<i class="glyphicon glyphicon-th-list"></i> Liste des Utilisations de la Carte Essence
				</button> &nbsp
			</form>
			<a class="btn btn-primary" style="display: inline-block;" href="creation_utilisation_carte_essence.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une utilisation d\'une carte Essence</p></a>';
	}
	else
		echo '<div class="alert alert-danger" role="alert">
			  	  <h4 class="alert-heading">Ce Véhicule ne dispose pas de Carte Essence </h4> <a style="display: inline-block;" class="btn btn-danger" href="creation_carte_essence.php"><div class="glyphicon glyphicon-plus"></div> Créer une Carte Essence</a>
			 </div>';

	echo'<br><br>';

	if($infosT['NoTelepeage'] != null)
	{
		echo '<form style="display: inline-block;" method="post" action="liste_telepeage.php">';
				$idT = $infosT['NoTelepeage'];
				$montantT = $infosT['Montant'];
				$id = $_SESSION['ImmatSaisi'];
		echo '<input type="hidden" name="idListe" value="' . $idT . '">
				<input type="hidden" name="Montant" value="' . $montantT . '">
				<input type="hidden" name="id" value="' . $id . '">
				<button type="submit" name="Modifier" class="btn btn-primary">
					<i class="glyphicon glyphicon-th-list"></i> Liste des Utilisations du Badge Télépéage
				</button> &nbsp
			</form>
			<a class="btn btn-primary" style="display: inline-block;" href="creation_utilisation_telepeage.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une utilisation d\'un badge Télépéage</p></a>';
	}
	else
		echo '<div class="alert alert-danger" role="alert">
			  	 <h4 class="alert-heading">Ce Véhicule ne dispose pas de Badge Télépéage </h4> <a style="display: inline-block;" class="btn btn-danger" href="creation_telepeage.php"><div class="glyphicon glyphicon-plus"></div> Créer un Badge Télépéage</a>
			 </div>';
}
?>

</div>


<br><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>
