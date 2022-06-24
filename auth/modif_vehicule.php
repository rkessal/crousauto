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
	<title>Modification d'un Véhicule</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeProprietaire = $conectBDD->Proprietaire_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeProprietaire = $conectBDD->Proprietaire_Liste_Par_Proprietaire($_SESSION['NoProprietaire']);
	}
	?>
</head>
<body><div id="top-margin" class="top-margin">
<form method="POST">
	<?php
	$id = $_SESSION['ImmatSaisi'];
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$immatriculation = $InfosVehicule['ImmatriculationVehicule'];
	$date1Immat = $InfosVehicule['DatePremiereImmatriculationVehicule'];
	$constructeur = $InfosVehicule['ConstructeurVehicule'];
	$modele = $InfosVehicule['ModeleVehicule'];
	$couleur = $InfosVehicule['CouleurVehicule'];
	$carburant = $InfosVehicule['TypeCarburantVehicule'];
	$type = $InfosVehicule['TypeVehicule'];
	$nbPlace = $InfosVehicule['NbPlaceVehicule'];
	$nbPorte = $InfosVehicule['NbPorteVehicule'];
	$rapport = $InfosVehicule['TypeRapportVehicule'];
	$puissance = $InfosVehicule['PuissanceVehicule'];
	$proprietaire = $InfosVehicule['NomProprietaire'];
	$kmVoiture = $InfosVehicule['KilometrageVehicule'];
	$lieu = $InfosVehicule['LieuVehicule'];
	$PrixAchat = $InfosVehicule['PrixAchatVehicule'];

?>
<h1>Modification d'un Véhicule</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$immatriculation = $_SESSION['ImmatSaisi'];
		$date1Immat = $_POST['date1Immat'];
		$constructeur = $_POST['constructeur'];
		$modele = $_POST['modele'];
		$couleur = $_POST['couleur'];
		$carburant = $_POST['carburant'];
		$type = $InfosVehicule['TypeVehicule'];
		$nbPlace = $_POST['nbPlace'];
		@$nbPorte = $_POST['nbPorte'];
		$rapport = $_POST['rapport'];
		@$puissance = $_POST['puissance'];
		@$kilometrage = $_POST['kilometrage'];
		$proprietaire = $_POST['proprietaire'];
		$couleurAff = $_POST['couleurAff'];

		if ($couleur == "" )
		{
			$couleur = null; 
		}

		if ($carburant == "" )
		{
			$carburant = null;
		}

		if ($type == "")
		{
			$type = null;
		}

		if ($nbPorte == "" )
		{
			$nbPorte = null;
		}

		if ($nbPlace == "")
		{
			$nbPlace = null;
		}

		if ($rapport == "" )
		{
			$rapport = null;
		}

		if ($puissance == "" )
		{
			$puissance = null;
		}

		if ($kilometrage == "")
		{
			$kilometrage = null;
		}
		if ($_POST['PrixAchat'] == "")
		{
			$_POST['PrixAchat'] = null;
		}

		if(isset($_POST['location']))
			$location = 1;
		else
			$location = 0;

		if(isset($_POST['actifVehicule']))
			$actifVehicule = 1;
		else
			$actifVehicule = 0;

		$conectBDD = new BDD();
		$res = $conectBDD->Vehicule_Modif($id, $immatriculation, $date1Immat, $constructeur, $modele, $couleur, $carburant, $type, $nbPlace, $nbPorte, $rapport, $puissance, $kilometrage, $proprietaire, '#' . $couleurAff, $_POST['lieu'], $_POST['PrixAchat'], $location, $actifVehicule);


		if(isset($_POST['libreservice']))
			$res2 = $conectBDD->Vehicule_Modif_LibreService($immatriculation, 1);
		else
			$res2 = $conectBDD->Vehicule_Modif_LibreService($immatriculation, 0);

		if ($immatriculation == "" || $constructeur == "" || $modele == "" || $proprietaire == "")
		{
			?>
			<div class="alert alert-danger" role="alert">
				Veuillez remplir tous les champs obligatoires. 
			</div>
				<?php
		}

		else
		{
			if($res)
			{
				?>
				<div class="alert alert-success" role="alert">
				  	Le Véhicule à bien été enregistré.
				</div>
				<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_vehicule.php"> 
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Erreur
				</div>
				<?php
			}
		}
								
	}
	$id = $_SESSION['ImmatSaisi'];
	$InfosVehicule = $conectBDD->Vehicule_Infos_parId($id);
	$immatriculation = $InfosVehicule['ImmatriculationVehicule'];
	$date1Immat = $InfosVehicule['DatePremiereImmatriculationVehicule'];
	$constructeur = $InfosVehicule['ConstructeurVehicule'];
	$modele = $InfosVehicule['ModeleVehicule'];
	$couleur = $InfosVehicule['CouleurVehicule'];
	$carburant = $InfosVehicule['TypeCarburantVehicule'];
	$type = $InfosVehicule['TypeVehicule'];
	$infosVehicule['TypeVehicule'] = $InfosVehicule['TypeVehicule'];;
	$nbPlace = $InfosVehicule['NbPlaceVehicule'];
	$nbPorte = $InfosVehicule['NbPorteVehicule'];
	$rapport = $InfosVehicule['TypeRapportVehicule'];
	$puissance = $InfosVehicule['PuissanceVehicule'];
	$proprietaire = $InfosVehicule['NomProprietaire'];
	$kmVoiture = $InfosVehicule['KilometrageVehicule'];
	$couleurAff = $InfosVehicule['CouleurAffichageVehicule'];
	$lieu = $InfosVehicule['LieuVehicule'];
	$PrixAchat = $InfosVehicule['PrixAchatVehicule'];

	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Vehicule</span>
  			<?php echo '<input type="text" name="type" disabled required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $type . '">'; ?>
  		</div>
  		<br>

  		<?php
  		if($InfosVehicule['LibreServiceVehicule'] == 1)
  			$libreservice = ' checked ';
  		else
  			$libreservice = '  ';

  		if($InfosVehicule['LocationVehicule'] == 1)
  			$location = ' checked ';
  		else
  			$location = '  ';

  		if($InfosVehicule['ActifVehicule'] == 1)
  			$actifVehicule = ' checked ';
  		else
  			$actifVehicule = '  ';
  		?>
  		<div class="custom-control custom-checkbox">
  			<?php echo '<input type="checkbox" name="libreservice" class="custom-control-input"  ' . $libreservice . '" id="customSwitch1">'; ?>
			<label class="custom-control-label" for="customSwitch1"> Disponible pour réservation</label>
		</div>
		<br>
		<div class="custom-control custom-checkbox">
  			<?php echo '<input type="checkbox" name="location" class="custom-control-input"  ' . $location . '" id="customSwitch2">'; ?>
			<label class="custom-control-label" for="customSwitch2"> Véhicule en Location</label>
		</div>
		<br>
		<div class="custom-control custom-checkbox">
  			<?php echo '<input type="checkbox" name="actifVehicule" class="custom-control-input"  ' . $actifVehicule . '" id="customSwitch3">'; ?>
			<label class="custom-control-label" for="customSwitch3"> Véhicule Actif</label>
		</div>


  		<br>
  		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Proprietaire*</span>
			<select class="custom-select" name="proprietaire" style="width: 510px" required>
				<?php
				foreach ($listeProprietaire as $uneLigne) {
					if($InfosVehicule['NoProprietaire'] == $uneLigne['NoProprietaire'])
					{
						echo '<option selected value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
					}
					else
					{
						echo '<option value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
					}
				}
				?>
			</select>
		</div>
		<br>
  		<script src="jscolor.js"></script>
		<div class="input-group" style="text-align: center">
			<span class="input-group-addon"  id="basic-addon1" style="width: 210px">Couleur d'Affichage</span>
			<script src="jscolor.js"></script>
			<input name="couleurAff" class="jscolor input-text form-control" <?php echo 'value=' . $couleurAff . '">'; ?>
		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Immatriculation*</span>
  			<?php echo '<input type="text" name="immatriculation" disabled required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $id . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Lieu*</span>
  			<?php echo '<input type="text" name="lieu" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $lieu . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Date de 1ère Immatriculation*</span>
  			<?php echo '<input type="text" name="date1Immat" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $date1Immat . '"  id="datepicker" autocomplete="off" placeholder="Sélectionnez une date">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Constructeur*</span>
  			<?php echo '<input type="text" name="constructeur" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $constructeur . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Modèle*</span>
  			<?php echo '<input type="text" name="modele" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $modele . '">'; ?>
  		</div>
		<br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Couleur</span>
  			<?php echo '<input type="text" name="couleur" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $couleur . '">'; ?>
  		</div>
  		<br>

  		<?php
  		if($infosVehicule['TypeVehicule'] == "Velo")
  		{
  		?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Carburant</span>
  			<select class="custom-select" name="carburant" required>
  				<?php
					if($InfosVehicule['TypeCarburantVehicule'] == "Electrique")
					{
						echo '<option selected value="Electrique">Electrique</option>';
					}
					else
					{
						echo '<option value="Electrique">Electrique</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Normal")
					{
						echo '<option selected value="Normal">Normal</option>';
					}
					else
					{
						echo '<option value="Normal">Normal</option>';
					}
					?>
  			</select>
  		</div>
  		<br>
  		<?php
  		}
  		else
  		{
  		?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Carburant</span>
  			<select class="custom-select" name="carburant" required>
  				<?php
					if($InfosVehicule['TypeCarburantVehicule'] == "Essence E5")
					{
						echo '<option selected value="Essence E5">Essence E5</option>';
					}
					else
					{
						echo '<option value="Essence E5">Essence E5</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Essence E10")
					{
						echo '<option selected value="Essence E10">Essence E10</option>';
					}
					else
					{
						echo '<option value="Essence E10">Essence E10</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Essence E85")
					{
						echo '<option selected value="Essence E85">Essence E85</option>';
					}
					else
					{
						echo '<option value="Essence E85">Essence E85</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Diesel B7")
					{
						echo '<option selected value="Diesel B7">Diesel B7</option>';
					}
					else
					{
						echo '<option value="Diesel B7">Diesel B7</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Diesel B10")
					{
						echo '<option selected value="Diesel B10">Diesel B10</option>';
					}
					else
					{
						echo '<option value="Diesel B10">Diesel B10</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Diesel XTL")
					{
						echo '<option selected value="Diesel XTL">Diesel XTL</option>';
					}
					else
					{
						echo '<option value="Diesel XTL">Diesel XTL</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hybride / E5")
					{
						echo '<option selected value="Hybride / E5">Hybride / E5</option>';
					}
					else
					{
						echo '<option value="Hybride / E5">Hybride / E5</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hybride / E10")
					{
						echo '<option selected value="Hybride / E10">Hybride / E10</option>';
					}
					else
					{
						echo '<option value="Hybride / E10">Hybride / E10</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hybride / E85")
					{
						echo '<option selected value="Hybride / E85">Hybride / E85</option>';
					}
					else
					{
						echo '<option value="Hybride / E85">Hybride / E85</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hybride / B7")
					{
						echo '<option selected value="Hybride / B7">Hybride / B7</option>';
					}
					else
					{
						echo '<option value="Hybride / B7">Hybride / B7</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hybride / B10")
					{
						echo '<option selected value="Hybride / B10">Hybride / B10</option>';
					}
					else
					{
						echo '<option value="Hybride / B10">Hybride / B10</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Electrique")
					{
						echo '<option selected value="Electrique">Electrique</option>';
					}
					else
					{
						echo '<option value="Electrique">Electrique</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Hydrogène H2")
					{
						echo '<option selected value="Hydrogène H2">Hydrogène H2</option>';
					}
					else
					{
						echo '<option value="Hydrogène H2">Hydrogène H2</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Gaz CNG")
					{
						echo '<option selected value="Gaz CNG">Gaz CNG</option>';
					}
					else
					{
						echo '<option value="Gaz CNG">Gaz CNG</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Gaz LNG")
					{
						echo '<option selected value="Gaz LNG">Gaz LNG</option>';
					}
					else
					{
						echo '<option value="Gaz LNG">Gaz LNG</option>';
					}
					if($InfosVehicule['TypeCarburantVehicule'] == "Gaz LPG")
					{
						echo '<option selected value="Gaz LPG">Gaz LPG</option>';
					}
					else
					{
						echo '<option value="Gaz LPG">Gaz LPG</option>';
					}
				?>
  			</select>
  		</div>
  		<br>

  		<?php
  		}
  		if($infosVehicule['TypeVehicule'] == "Velo")
  		{
  		?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Place</span>
  			<select class="custom-select" name="nbPlace" required>
  				<?php
				if($InfosVehicule['NbPlaceVehicule'] == 1)
				{
					echo '<option selected value="1">1</option>';
				}
				else
				{
					echo '<option value="1">1</option>';
				}
				if($InfosVehicule['NbPlaceVehicule'] == 2)
				{
					echo '<option selected value="2">2</option>';
				}
				else
				{
					echo '<option value="2">2</option>';
				}
				?>
  			</select>
  		</div>
  		<br>
  		<?php
  		}
  		else
  		{
  		?>
  		<!--modifier la liste si vélo-->
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Place</span>
  			<select class="custom-select" name="nbPlace" required>
  				<?php
					if($InfosVehicule['NbPlaceVehicule'] == 2)
					{
						echo '<option selected value="2">2</option>';
					}
					else
					{
						echo '<option value="2">2</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 3)
					{
						echo '<option selected value="3">3</option>';
					}
					else
					{
						echo '<option value="3">3</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 4)
					{
						echo '<option selected value="4">4</option>';
					}
					else
					{
						echo '<option value="4">4</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 5)
					{
						echo '<option selected value="5">5</option>';
					}
					else
					{
						echo '<option value="5">5</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 6)
					{
						echo '<option selected value="6">6</option>';
					}
					else
					{
						echo '<option value="6">6</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 7)
					{
						echo '<option selected value="7">7</option>';
					}
					else
					{
						echo '<option value="7">7</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 8)
					{
						echo '<option selected value="8">8</option>';
					}
					else
					{
						echo '<option value="8">8</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 9)
					{
						echo '<option selected value="9">9</option>';
					}
					else
					{
						echo '<option value="9">9</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 10)
					{
						echo '<option selected value="10">10</option>';
					}
					else
					{
						echo '<option value="10">10</option>';
					}
					if($InfosVehicule['NbPlaceVehicule'] == 11)
					{
						echo '<option selected value="11">11</option>';
					}
					else
					{
						echo '<option value="11">11</option>';
					}
				?>
  			</select>
  		</div>
  		<br>
  		<?php
  		}
  		if($infosVehicule['TypeVehicule'] != "Velo")
  		{
  		?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Porte</span>
  			<select class="custom-select" name="nbPorte" required>
  				<?php
					if($InfosVehicule['NbPorteVehicule'] == 3)
					{
						echo '<option selected value="3">3</option>';
					}
					else
					{
						echo '<option value="3">3</option>';
					}
					if($InfosVehicule['NbPorteVehicule'] == 4)
					{
						echo '<option selected value="4">4</option>';
					}
					else
					{
						echo '<option value="4">4</option>';
					}
					if($InfosVehicule['NbPorteVehicule'] == 5)
					{
						echo '<option selected value="5">5</option>';
					}
					else
					{
						echo '<option value="5">5</option>';
					}
					?>
				</select>
  		</div>
  		<br>
  		<?php
	  	}
	  	?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Rapport</span>
  			<select class="custom-select" name="rapport" required>
  				<?php
					if($InfosVehicule['TypeRapportVehicule'] == "Manuel")
					{
						echo '<option selected value="Manuel">Manuel</option>';
					}
					else
					{
						echo '<option value="Manuel">Manuel</option>';
					}
					if($InfosVehicule['TypeRapportVehicule'] == "Automatique")
					{
						echo '<option selected value="Automatique">Automatique</option>';
					}
					else
					{
						echo '<option value="Automatique">Automatique</option>';
					}
				?>
			</select>
  		</div>
  		<br>
  		<div class="input-group" style="text-align: center">
			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Prix d'achat (en €)</span>
			<input id="kilometrage" type="text" name="PrixAchat" style="width: 200px" class="input-text form-control" <?php echo 'value="' . $PrixAchat . '"'; ?>aria-describedby="basic-addon1">
		</div>
		<br>

  		<?php
  		if($infosVehicule['TypeVehicule'] != "Velo")
  		{
  		?>
  		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Puissance Fiscal</span>
  			<?php echo '<input type="text" name="puissance" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $puissance . '">'; ?>
  		</div>
		<br>
		<?php
		}
		?>
		<?php
  		if($infosVehicule['TypeVehicule'] != "Velo")
  		{
  		?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 210px" id="basic-addon1">Kilometrage à l'achat</span>
  			<?php echo '<input type="text" name="kilometrage" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $kmVoiture . '">'; ?>
  		</div>
		<br>
		<?php
		}
		?>

		
		
		<br><br>

		<br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button><br><br>

		<div class="nota">
			<p>Les champs suivis d'une * sont obligatoires.</p>
		</div></form>
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

</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>