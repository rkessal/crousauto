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
	<title>Liste des Action d'une Carte Essence</title>

</head>
<?php
	include("menu.php");
?>
<body>
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
	if(isset($_POST['idListe']))
	{
		$_SESSION['id']=$_POST['idListe'];
	}

	$id = $_SESSION['id'];
	$InfosCarte = $conectBDD->Carte_Essence_Infos_parId($id);
	$listeCartes = $conectBDD->Utiliser_Carte_Essence_Liste_ParId($id);
?>
<div id="top-margin" class="top-margin">
<h1 class="title">Liste des Actions d'une Carte Essence <a class="btn btn-primary" style="display: inline-block;" href="creation_utilisation_carte_essence.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une utilisation de la carte essence</p></a></h1>

<table class="table"><tr><th scope="col">Numéro</th><th scope="col">Véhicule</th><th scope="col">Fournisseur</th></tr>
<?php
		echo '<tr><th scope="row">'.$InfosCarte['NomCarte'].'</th>';
		echo '<th scope="row">'.$InfosCarte['ImmatriculationVehicule'].'</th>';
		echo '<th scope="row">'.$InfosCarte['FournisseurCarte'].' - expire le '.$InfosCarte['DateRenouvellementCarte'].'</th>';


	echo '</table>';
?>
<br>
<br>

<table class="table"><tr><th scope="col">Date</th><th scope="col">Conducteur</th><th scope="col">Station Service</th><th scope="col">Kilometrage</th><th scope="col">Litre</th><th scope="col">Montant</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeCartes as $uneLigne) {
		if($uneLigne['DateUtilisation'] != '1900-01-01')
		{
			echo '';
			$Date = new DateTime($uneLigne['DateUtilisation']);
			echo '<tr><th scope="row">'.$Date->format('d/m/Y').'</th>';
			echo '<th scope="row">'.$uneLigne['NomConducteur'].'</th>';
			echo '<th scope="row">'.$uneLigne['Station'].'</th>';
			echo '<td>'.$uneLigne['KilometrageCarte'].' </td>';
			echo '<td>'.$uneLigne['Litre'].' </td>';
			echo '<td>'.$uneLigne['Montant'].' €</td>';
			$idCarte = $uneLigne['NoCarte'];
			$idDate = $uneLigne['DateUtilisation'];
			$idCond = $uneLigne['NoConducteur'];
			$id = $InfosCarte['ImmatriculationVehicule'];
			$_SESSION['ImmatSaisi'] = $id;
			echo '<td> &nbsp
				<form style="display: inline-block;" method="post" action="modif_utilisation_carte_essence.php">
				<input type="hidden" name="idModif" value="' . $idCarte . '">
				<input type="hidden" name="idDate" value="' . $idDate . '">
				<input type="hidden" name="idCond" value="' . $idCond . '">
				<input type="hidden" name="immatSaisie" value="' . $id . '">
				<button type="submit" name="Modifier" class="btn btn-primary">
					<i class="glyphicon glyphicon-pencil"></i> Modifier
				</button> &nbsp
				</form>
			</td></tr>';
		}
	}
	echo '</table>';

?>

<br><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>