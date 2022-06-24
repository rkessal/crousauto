<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitVehicule'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Saisir l'utilisation du Badge de Télépéage</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	if(isset($_POST['ImmatSaisi']))
		$_SESSION['ImmatSaisi'] = $_POST['ImmatSaisi'];
	$id = $_SESSION['ImmatSaisi'];
	$conectBDD = new BDD();
	$listeConducteur = $conectBDD->Conducteur_Liste();
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$InfosBadge = $conectBDD->Telepeage_Infos_parImmat($id);
	$listeDeplacement = [];
	?>
</head>
<body><div id="top-margin" class="top-margin">

<h1>Saisir l'utilisation du Badge de Télépéage</h1>
<br>
	<?php
	if(isset($_POST["Date"]))
	{
		$date2 = $_POST['annee'] . '-' . $_POST['mois'] . "-%" ;
		$date =  $_POST['annee'] . '-' . $_POST['mois'] . "-01"  ;
		$listeDeplacement = $conectBDD->Utilise_Liste_parImmatriculation_et_parMois($id, $date2);
	}
	else
	{
		$date = date('Y') . '-' . date('m') . '-' . date('d');
		$date2 = date('Y') . '-' . date('m') . "-%" ;
		$listeDeplacement = $conectBDD->Utilise_Liste_parImmatriculation_et_parMois($id, $date2);
	}
	if(isset($_POST["Valider"]))
	{
		$listeDeplacement = $conectBDD->Utilise_Liste_parImmatriculation_et_parMois($id, $date2);
		foreach ($listeDeplacement as $uneLigne) {
			if(isset($_POST[$uneLigne['idDeplacement']]))
				$statutT = 1;
			else
				$statutT = 0;
		$idDeplacement = $uneLigne['idDeplacement'];
		$nobadge = (int)$InfosBadge['NoTelepeage'];
		if($_POST['montant'])
			$montant = (int)$_POST['montant'];
		else
			$montant = 0;
		$NoTelepeage = (int)$InfosBadge['NoTelepeage'];
		
		$res = $conectBDD->Utilise_ActiviteTelepeage($idDeplacement, $statutT);
		}
		$date3 = $date . '-01';
		$res2 = $conectBDD->Utiliser_Telepeage_Create(@$NoTelepeage, $date3, @$montant);
			if($res2)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	L'Utilisation du badge de télépéage du véhicule à bien été enregistré.
				</div>
				<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=liste_telepeage.php">
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Cette utilisation à déjà été saisie.
				</div>
				<?php
			}
		if(isset($_POST["Date"]))
		{
			$date2 = $_POST['date'] . "-%" ;
		}
		else
		{
			$date2 = date('Y') . '-' . date('m') . "-%" ;
		}		
	}
	$listeDeplacement = $conectBDD->Utilise_Liste_parImmatriculation_et_parMois($id, $date2);

	/*var_dump($id);
	var_dump($date);
	var_dump($entree);
	var_dump($sortie);
	var_dump($montant);
	var_dump($conducteur);
	var_dump($autoroute);
	var_dump($InfosBadge['NoTelepeage']);*/

	?>

	<table class="table"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Propriétaire</th><th scope="col">Badge de Télépéage</th></tr></tr>
	<?php
		echo '';
		echo '<tr><th>'.$InfosVehicule['ImmatriculationVehicule'].'</th>';
		echo '<td>'.$InfosVehicule['ConstructeurVehicule'].' - ';
		echo  $InfosVehicule['ModeleVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['CouleurVehicule'].'</td>';
		echo '<td>'.$InfosVehicule['TypeCarburantVehicule'].'</td>';
		echo '<td>'. $InfosVehicule['NomProprietaire'].'</td>';
		echo '<td>'. $InfosBadge['NomTelepeage'].'</td>';
		echo '</table>';

?>
	<form method="POST">
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Choix de la période*</span>
  			<select class="custom-select" style="width: 200px" name="mois" required>
  				<?php
  					$d = new DateTime($date);
  					$mois = $d->format('m');
  					$mois = (string)$mois;
					if($mois == "01")
						echo '<option selected value="01">Janvier</option>';
					else
						echo '<option value="01">Janvier</option>';
					if($mois == "02")
						echo '<option selected value="02">Fevrier</option>';
					else
						echo '<option value="02">Fevrier</option>';
					if($mois == "03")
						echo '<option selected value="03">Mars</option>';
					else
						echo '<option value="03">Mars</option>';
					if($mois == "04")
						echo '<option selected value="04">Avril</option>';
					else
						echo '<option value="04">Avril</option>';
					if($mois == "05")
						echo '<option selected value="05">Mai</option>';
					else
						echo '<option value="05">Mai</option>';
					if($mois == "06")
						echo '<option selected value="06">Juin</option>';
					else
						echo '<option value="06">Juin</option>';
					if($mois == "07")
						echo '<option selected value="07">Juillet</option>';
					else
						echo '<option value="07">Juillet</option>';
					if($mois == "08")
						echo '<option selected value="08">Aout</option>';
					else
						echo '<option value="08">Aout</option>';
					if($mois == "09")
						echo '<option selected value="09">Septembre</option>';
					else
						echo '<option value="09">Septembre</option>';
					if($mois == "10")
						echo '<option selected value="10">Octobre</option>';
					else
						echo '<option value="10">Octobre</option>';
					if($mois == "11")
						echo '<option selected value="11">Novembre</option>';
					else
						echo '<option value="11">Novembre</option>';
					if($mois == "12")
						echo '<option selected value="12">Decembre</option>';
					else
						echo '<option value="12">Decembre</option>';

					var_dump($mois);
				?>
			</select>

			<select class="custom-select" style="width: 200px" name="annee" required>
			<?php
			$a = new DateTime($date);
			$annee = $a->format('Y');
			var_dump($annee);
			$datefin = date('Y') + 10;
			$compteur = 2018;
			while ($compteur <= $datefin) {
				if($annee == $compteur)
					echo '<option selected value="'.$compteur.'">'. $compteur . '</option>';
				else
					echo '<option value="'.$compteur.'">'. $compteur . '</option>';
				$compteur += 1;
			}
			?>
			</select>
  		</div>
		<br>
		<br><button type="submit" name="Date" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider la période
		</button><br>
	</form>

		<form method="POST">

		<div class="input-group" style="text-align: center">
			<h1>Déplacement effectués : </h1>
		</div>
		<br>
			<table class="table"></tr><th scope="col"> </th><th scope="col">Destination</th><th scope="col">Date de Début</th><th scope="col">Date de Fin</th><th scope="col">Conducteur</th></tr>
				<?php
				if($listeDeplacement == null)
					echo '<tr><td colspan="5"><center>Aucun déplacement n\'a été enregistré pour ce véhicule</center></td></tr>';
				else
				{
					foreach ($listeDeplacement as $uneLigne) {
					$DateDebutUtilisation = new DateTime($uneLigne['DateDebutUtilisation']);
					$DateFinUtilisation = new DateTime($uneLigne['DateFinUtilisation']);
					echo '<tr><td><input type="checkbox" class="operations" name="'.$uneLigne['idDeplacement'].'"></td>'. ' 
   							<td>' . $uneLigne['Destination'] .'</td><td>' . $DateDebutUtilisation->format('d/m/Y H:i') . '</td><td>' . $DateFinUtilisation->format('d/m/Y H:i') . '</td><td>' . $uneLigne['NomConducteur'] .'</td></tr>';
					}
				}
				?>
		</table>
		<br><br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Montant (en €)</span>
  			<input type="text" name="montant" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>

		<br><button type="submit" name="Valider" class="btn btn-primary">
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