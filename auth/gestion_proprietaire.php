<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitUtilisateur'] == 0 and $_SESSION['NoResidence'] != 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des Sites</title>

</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
		$listeProprietaire = $conectBDD->Proprietaire_Liste_parResidence($_SESSION['NoResidence']);
?>
<body>
<div id="top-margin" class = "top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES SITES <a class="btn btn-primary" href="creation_proprietaire.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Site</p></a></h1>
<table class="table vehicules"><tr><th scope="col">Nom</th><th scope="col">Adresse</th><th scope="col">Telephone</th><th scope="col">Fax</th><th scope="col">Résidence Administrative</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeProprietaire as $uneLigne) {
		echo '';
		echo '<th scope="row">'.$uneLigne['NomProprietaire'].'</th>';
		echo '<td>'.$uneLigne['AdresseProprietaire'].' <br>
		' . $uneLigne['CPProprietaire'].' '.$uneLigne['VilleProprietaire'].'</td>';
		echo '<td>'.$uneLigne['TelephoneProprietaire'].'</td>';
		echo '<td>'.$uneLigne['FaxProprietaire'].'</td>';
		echo '<td>'.$uneLigne['NomResidence'].'</td>';
		$id = $uneLigne['NoProprietaire'];
		echo '<td> &nbsp
		<form style="display: inline-block;" method="post" action="modif_proprietaire.php">
			<input type="hidden" name="idModif" value="' . $id . '">
			<button type="submit" name="Modifier" class="btn btn-primary">
				<i class="glyphicon glyphicon-pencil"></i> Modifier
			</button> &nbsp
			</form>
		</td></tr>';
	}
	echo '</table>';

?>

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>