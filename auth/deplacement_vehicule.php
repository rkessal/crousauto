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
	<title>Saisir une Réservation</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$id = $_SESSION['ImmatSaisi'];
	$conectBDD = new BDD();
	$listeConducteur = $conectBDD->Conducteur_Liste_ParUser($_SESSION['NoUtilisateur']);
	$listeProprietaire = $conectBDD->Proprietaire_Liste();
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST">


<h1>Saisir une Réservation</h1>
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

		$DejaUtiliser = $conectBDD->Utilise_Verificaton_parImmatriculationEntre2Dates($id, $datedebut, $datefin, 0);
		if(!$DejaUtiliser)
		{

			$conectBDD = new BDD();
			$res = $conectBDD->Utilise_Create($id, $datedebut, $datefin, $destination, $conducteur, $_POST['passagers']);

			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	Le Réservation du véhicule à bien été enregistrée.
				</div>
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Ce véhicule est déjà utilisé à cette heure-ci.
				</div>
				<?php
			}
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	Ce véhicule est déjà utilisé à cette heure-ci.
			</div>
			<?php
		}

								
	}
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
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date d'utilisation*</span>
  			<input type="text" required name="date" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" id="datepicker" autocomplete="off" placeholder="Sélectionnez une date">
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
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Conducteur*</span>
				<select class="custom-select" name="conducteur" style="width: auto" required>
					<?php
					foreach ($listeConducteur as $uneLigne) {
						if($uneLigne['ActifConducteur'])
							echo '<option value="'.$uneLigne['NoConducteur'].'">'.$uneLigne['NomConducteur'].' '. $uneLigne['PrenomConducteur'] . '</option>';
					}
					?>
				</select><a class="btn btn-primary" href="creation_conducteur.php"><p>Créer un Conducteur</p></a> 
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