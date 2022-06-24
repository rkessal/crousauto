<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($_SESSION['NoResidence'] != 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestion des Crous</title>

</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeProprietaire = $conectBDD->Residence_Liste();
?>
<body>
<div id="top-margin" class = "top-margin">
<h1 style="display: inline-block;" class="title">GESTION DES CROUS <a class="btn btn-primary" href="creation_residence.php"><p><div class="glyphicon glyphicon-plus"></div> Créer un Crous</p></a></h1>
<table class="table vehicules"><tr><th scope="col"></th><th scope="col">Nom</th><th scope="col">Adresse</th><th scope="col">Telephone</th><th scope="col">Fax</th><th scope="col">Action</th></tr>
<?php
	foreach ($listeProprietaire as $uneLigne) {
		echo '';
		echo '<tr><th scope="row"><img style="width:30px" src="logo/'.$uneLigne['LogoResidence'].'"></th>';
		echo '<th scope="row">'.$uneLigne['NomResidence'].'</th>';
		echo '<td>'.$uneLigne['AdresseResidence'].' <br>
		' . $uneLigne['CPResidence'].' '.$uneLigne['VilleResidence'].'</td>';
		echo '<td>'.$uneLigne['TelephoneResidence'].'</td>';
		echo '<td>'.$uneLigne['FaxResidence'].'</td>';
		$id = $uneLigne['NoResidence'];
		echo '<td> &nbsp
		<form style="display: inline-block;" method="post" action="modif_residence.php">
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