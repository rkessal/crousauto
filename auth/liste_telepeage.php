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
	<title>Liste des Action d'un Badge de Télépéage</title>

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
	$InfosT = $conectBDD->Telepeage_Infos_parId($id);
	$listeCartes = $conectBDD->Utiliser_Telepeage_Liste_ParId($id);
	//var_dump($listeCartes);
?>
<div id="top-margin" class="top-margin">
<h1 class="title">Liste des Actions d'un Badge de Télépéage <a class="btn btn-primary" style="display: inline-block;" href="creation_utilisation_telepeage.php"><p><div class="glyphicon glyphicon-plus"></div> Saisir une utilisation du badge Télépéage</p></a></h1>

<table class="table"><tr><th scope="col">Numéro</th><th scope="col">Véhicule</th><th scope="col">Fournisseur</th><th scope="col">Abonnement</th></tr>
<?php
		echo '<tr><th scope="row">'.$InfosT['NomTelepeage'].'</th>';
		echo '<th scope="row">'.$InfosT['ImmatriculationVehicule'].'</th>';
		echo '<th scope="row">'.$InfosT['FournisseurTelepeage'].' </th><th>'.$InfosT['AbonnementTelepeage'].'</th>';

	echo '</table>';
?>
<br>
<br>

<table class="table"><tr><th scope="col">Date</th><th scope="col">Montant</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeCartes as $uneLigne) {
		if($uneLigne['DateTelepeage'] != '1900-01-01')
		{
			echo '';
			$Date = new DateTime($uneLigne['DateTelepeage']);
			echo '<tr><th scope="row">'.$Date->format('m/Y').'</th>';
			echo '<td>'.$uneLigne['Montant'].' €</td>';
			$idCarte = $uneLigne['NoTelepeage'];
			$idDate = $uneLigne['DateTelepeage'];
			$id = $InfosT['ImmatriculationVehicule'];
			$_SESSION['ImmatSaisi'] = $id;
			echo '<td> &nbsp
				<form style="display: inline-block;" method="post" action="modif_utilisation_telepeage.php">
				<input type="hidden" name="idModif" value="' . $idCarte . '">
				<input type="hidden" name="idDate" value="' . $idDate . '">
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