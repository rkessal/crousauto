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
	<title>Modifier le kilometrage d'un vehicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$id = $_SESSION['ImmatSaisi'];
	if(isset($_POST['DateKilometrage']))
		$_SESSION['DateKilometrage'] = $_POST['DateKilometrage'];
	$conectBDD = new BDD();
	$listeConducteur = $conectBDD->Conducteur_Liste_ParUser($_SESSION['NoUtilisateur']);
	$infosKilometrage = $conectBDD->Kilometrage_Liste_parDateEtImmat($id, $_SESSION['DateKilometrage']);
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	//var_dump($infosKilometrage);
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST">


<h1>Modifier le kilometrage d'un vehicule</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{

		$conectBDD = new BDD();
		$res = $conectBDD->Kilometrage_Modif($id, $_SESSION['DateKilometrage'], $_POST['km']);

		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le kilometrage du véhicule à bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=affichage_vehicule.php">
			<?php
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Echec.
			</div>
			<?php
		}

								
	}
	$infosKilometrage = $conectBDD->Kilometrage_Liste_parDateEtImmat($id, $_SESSION['DateKilometrage']);
	$color = inverse_hexcolor($InfosVehicule['CouleurAffichageVehicule']);
	echo '<table class="table" style="background-color:' . $InfosVehicule['CouleurAffichageVehicule'] . ';color: #' . $color . ';"><tr><th scope="col" <td colspan="6"><center><h1>VEHICULE<h1></center></th></tr></tr><th scope="col">Immatriculation</th><th scope="col">Constructeur / Modèle</th><th scope="col">Couleur</th><th scope="col">Carburant</th><th scope="col">Nombre de Place</th></tr></tr>';
	

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


?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date*</span>
  			<input type="date" required name="date" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1"  <?php  echo' value="' . $infosKilometrage['DateKilometrage']  . '"'; ?> disabled>
  		</div>
		<br>

		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Kilometrage*</span>
  			<input type="text" name="km" style="width: 200px"  <?php  echo' value="' . $infosKilometrage['KilometrageReleve']  . '"'; ?> class="input-text form-control" aria-describedby="basic-addon1">
  		</div>
		<br>
		
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