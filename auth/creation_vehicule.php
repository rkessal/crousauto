<?php session_start(); 
require_once('bdd.php');
$_SESSION['page'] = "creation_vehicule.php";
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
	<title>Création d'un Véhicule</title>

	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$listeDroit = $conectBDD->Droit_Liste();
	if($_SESSION['NoResidence'] == 0)
		$listeProprietaire = $conectBDD->Proprietaire_Liste();
	else
	{
		if($liste['NoDroit'] == 1)
			$listeProprietaire = $conectBDD->Proprietaire_Liste_ParResidence($_SESSION['NoResidence']);
		else
			$listeProprietaire = $conectBDD->Proprietaire_Liste_Par_Proprietaire($_SESSION['NoProprietaire']);
	}
	$listeElement = $conectBDD->Element_Liste();
	?>
</head>
<body>
	<div id="top-margin" class="top-margin">
		<h1>Création d'un Véhicule</h1>
		<br>
		<?php

  		if (isset($_REQUEST['dispo'])) 
  		{
  			$dispo = 1;
  		}
  		else
  		{
  			$dispo = 0;
  		}

  		if (isset($_REQUEST['location'])) 
  		{
  			$location = 1;
  		}
  		else
  		{
  			$location = 0;
  		}

  		//var_dump($dispo);

		if(isset($_POST["Valider"]))
		{
			//var_dump($_POST["Valider"]);

			$immatriculation = $_POST['immatriculation'];
			$date1Immat = $_POST['date1Immat'];
			$constructeur = $_POST['constructeur'];
			$modele = $_POST['modele'];
			$couleur = $_POST['couleur'];
			$carburant = $_POST['carburant'];
			$type = $_POST['type'];
			$nbPlace = (int)$_POST['nbPlace'];
			$nbPorte = (int)$_POST['nbPorte'];
			$rapport = $_POST['rapport'];
			$puissance = $_POST['puissance'];
			$proprietaire = $_POST['proprietaire'];
			$kmVoiture = $_POST['kilometrage'];
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

			if ($kmVoiture == "")
			{
				$kmVoiture = null;
			}

			if ($_POST['PrixAchat'] == "")
			{
				$_POST['PrixAchat'] = null;
			}

			$res = $conectBDD->Vehicule_Create($immatriculation, $date1Immat, $constructeur, $modele, $couleur, $carburant, $type, $nbPlace, $nbPorte, $rapport, $puissance, $proprietaire, $kmVoiture, '#' . $couleurAff, $dispo, $_POST['lieu'], $_POST['PrixAchat'], $location);
		/*var_dump($res);



		var_dump($immatriculation);
		var_dump($date1Immat);
		var_dump($constructeur);
		var_dump($modele);
		var_dump($couleur);
		var_dump($carburant);
		var_dump($type);
		var_dump($nbPlace);
		var_dump($nbPorte);
		var_dump($rapport);
		var_dump($puissance);
		var_dump($proprietaire);
		var_dump($kmVoiture);
		var_dump($couleurAff);*/
		

		if ($immatriculation == "" || $constructeur == "" || $modele == "" || $proprietaire == "" || $kmVoiture = "")
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
				$listeGestionnaire = $conectBDD->User_Liste_parDroitEtProprietaire($proprietaire, 2);
				//var_dump($listeGestionnaire);
				foreach ($listeGestionnaire as $ligne) {
					$res = $conectBDD->Gerer_Create($ligne['NoUtilisateur'], $immatriculation);
				}
				?>
				<div class="alert alert-success" role="alert">
					Le Véhicule à bien été enregistré.
				</div>
				<meta http-equiv="refresh" content="2;URL=calendrier_vehicule.php"> 
				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
					Ce Véhicule a déjà été enregistré.
				</div>
				<?php
			}
		}
		$verif = 0;
		$ok = 0;
		if($_POST['type'] != "Velo")
		{
			foreach ($listeElement as $uneLigne)
			{

				$verif += 1;
				$nom = 'km_' . $uneLigne['NoElement'];
				$km = (int)$_POST[$nom];
				$nom2 = 'mois_' . $uneLigne['NoElement'];
				$mois = (int)$_POST[$nom2];
				$NoElement = (int)$uneLigne['NoElement'];
				if($km != 'n/a' and $mois != 'n/a')
				{
					$res2 = $conectBDD->Occurence_Element_Create($immatriculation, $NoElement, $km, $mois);
					if($res2)
					{
						$ok += 1;
					}
				}
				else
				{
					$ok += 1;
				}
				//var_dump($res2);
				
			}

			if($ok = $verif)
			{
				?>
				<div class="alert alert-success" role="alert">
					Les Fréquences d'Entretien ont bien été enregistrés.
				</div>

				<?php
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
					Ces fréquences d'Entretien ont déjà été enregistrées.
				</div>
				<?php
			}
		}

	}
	?>
	<?php
	if (isset($_REQUEST['type']))
	{
		$typeVehicule = $_REQUEST['type'];
		$_SESSION['typeVehicule'] = $typeVehicule;

	}
	else
	{
		$typeVehicule = "default";
	}




	?>

	<form method="POST" action="creation_vehicule.php">
	<div class="input-group" style="text-align: center">
		<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Vehicule</span>
			<select id="typeVehicule" class="custom-select custom-select-lg-mb3" name="type" required onchange="this.form.submit();">
				<option <?php if ($typeVehicule == "default") echo 'selected="selected"' ?> value="default">Choisir type</option>
				<option <?php if ($typeVehicule == "VL") echo 'selected="selected"' ?> value="VL">VL</option>
				<option <?php if ($typeVehicule == "Camion") echo 'selected="selected"' ?> value="Camion">Camion</option>
				<option <?php if ($typeVehicule == "Vehicule frigorifique") echo 'selected="selected"' ?> value="Vehicule frigorifique">Vehicule frigorifique</option>
				<option <?php if ($typeVehicule == "Utilitaire") echo 'selected="selected"' ?> value="Utilitaire">Utilitaire</option>
				<option <?php if ($typeVehicule == "Bus") echo 'selected="selected"' ?> value="Bus">Bus</option>
				<option <?php if ($typeVehicule == "Velo") echo 'selected="selected"' ?> value="Velo">Velo</option>
			</select>
	</div>
	<br>

			<div id="caracteristiques-vehicule" class="hide">
		      	<div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="dispo" id="accesGestion">
				<label class="custom-control-label" for="accesGestion"> Disponible pour réservation</label>
		      	<br><br>
		  </div>
		  <div class="custom-control custom-checkbox">
			<input type="checkbox" class="custom-control-input location" name="location" id="location">
		    <label class="custom-control-label" for="location"> Véhicule en location</label>
		    </div>
		    <br>

			<div class="input-group" style="text-align: center">

				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Site*</span>
				<select class="custom-select" name="proprietaire" required>
					<?php
					foreach ($listeProprietaire as $uneLigne) {
						echo '<option value="'.$uneLigne['NoProprietaire'].'">'.$uneLigne['NomProprietaire'].' (' . $uneLigne['NomResidence'] . ')</option>';
					}
					?>
				</select>
			</div>
			<br>

			<?php
			$couleurAff = rand_hexcolor();
			$couleurAff = '#' . $couleurAff;
			?>

			<script src="jscolor.js"></script>
			<div class="input-group" style="text-align: center">
				<span class="input-group-addon"  id="basic-addon1" style="width: 210px">Couleur d'Affichage</span>
				<script src="jscolor.js"></script>

			<input name="couleurAff" class="jscolor input-text form-control" <?php echo 'value=' . $couleurAff . '">'; ?>
			</div>
			<br>

			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Lieu*</span>
				<input id="kilometrage" type="text" required name="lieu"  class="input-text form-control" aria-describedby="basic-addon1">
			</div>
			<br>
			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Immatriculation*</span>
				<input type="text" name="immatriculation" required  class="input-text form-control" aria-describedby="basic-addon1">
			</div>
			<br>
			<div class="input-group" style="text-align: center">
				<span  class="input-group-addon" style="width: 210px" id="basic-addon1">Date de 1ère Immatriculation*</span>
				<input type="text" required name="date1Immat"  class="input-text form-control" aria-describedby="basic-addon1" i id="datepicker" autocomplete="off" placeholder="Sélectionnez une date">
			</div>
			<br>
			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Constructeur*</span>
				<input type="text" name="constructeur" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
			</div>
			<br>
			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Modèle*</span>
				<input type="text" name="modele" class="input-text form-control" aria-describedby="basic-addon1">
			</div>
			<br>
			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Couleur</span>
				<input type="text" name="couleur" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
			</div>
			<br><!-- réduire pour vélo -->
			<div id="velo" class="hide">
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Carburant</span>
					<select class="custom-select" name="carburant" required>
						<option value="Electrique">Electrique</option>
						<option value="Normal">Normal</option>
						<br>
					</select>
				</div>
				<br>
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Place</span>
					<select class="custom-select" name="nbPlace" required>
						<option value="1">1</option>
						<option value="2">2</option>
					</select>

				</div>
				<br>

			</div>	

			<div class="input-group" style="text-align: center">
				<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Rapport</span>
				<select class="custom-select" name="rapport" required>
					<option value="Manuel">Manuel</option>
					<option value="Automatique">Automatique</option>
				</select>
			</div>
			<br>
			<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Prix d'achat (en €)</span>
					<input id="kilometrage" type="text" name="PrixAchat" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
				</div>
				<br>




			<div id="voiture" class="hide">
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Type de Carburant</span>
					<select class="custom-select" name="carburant" required>
						<option value="Essence E5">Essence E5</option>
						<option value="Essence E10">Essence E10</option>
						<option value="Essence E85">Essence E85</option>
						<option value="Diesel B7">Diesel B7</option>
						<option value="Diesel B10">Diesel B10</option>
						<option value="Diesel XTL">Diesel XTL</option>
						<option value="Hybride / E5">Hybride / E5</option>
						<option value="Hybride / E10">Hybride / E10</option>
						<option value="Hybride / E85">Hybride / E85</option>
						<option value="Hybride / B7">Hybride / B7</option>
						<option value="Hybride / B10">Hybride / B10</option>
						<option value="Electrique">Electrique</option>
						<option value="Hydrogène H2">Hydrogène H2</option>
						<option value="Gaz CNG">Gaz CNG</option>
						<option value="Gaz LNG">Gaz LNG</option>
						<option value="Gaz LPG">Gaz LPG</option>
					</select>
				</div>
				<br>
				<!-- réduire pour vélo -->
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Place</span>
					<select class="custom-select" name="nbPlace" required>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">11</option>
					</select>
				</div>
				<br>

				<!-- n/a pour vélo -->
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Nombre de Porte</span>
					<select class="custom-select" name="nbPorte" required>

						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				<br>
				<!-- n/a pour vélo -->
				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Puissance Fiscal</span>
					<input type="text" name="puissance" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
				</div>
				<br>

				<div class="input-group" style="text-align: center">
					<span class="input-group-addon" style="width: 210px" id="basic-addon1">Kilometrage à l'achat</span>
					<input id="kilometrage" type="text" name="kilometrage" style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1">
				</div>
				<br>
			</div>
			<div id="entretien" class="hide">
				<div class="input-group" style="text-align: center">
				<h1> Fréquence des Entretiens </h1>
			</div>
			<?php
			foreach ($listeElement as $uneLigne) {
					echo '<br><h3>Pour ' . $uneLigne["LibelleElement"] . ' :</h3>';
					$NoElement = $uneLigne['NoElement'];
					?>
					<br>
					<div class="input-group" style="text-align: center">
						<span class="input-group-addon" style="width: 300px" id="basic-addon1">Nombre de kilomètres avant entretien</span>
						<?php $nomkm = 'km ' . $NoElement;
						echo '<select class="custom-select" name="' . $nomkm . '" required>' ?>
						<?php
									//var_dump('km_' . $NoElement);
						$compteur = 10000;
						echo '<option value="n/a">non concerné</option>';
						while($compteur <= 300000)
						{
							echo '<option value="'.$compteur.'">'.$compteur.' kilomètres</option>';
							$compteur += 10000;
						}
						?>
						</select>
					</div>
					<br>
					<div class="input-group" style="text-align: center">
						<span class="input-group-addon" style="width: 300px" id="basic-addon1">Nombre de mois avant entretien</span>
						<?php $nommois = 'mois_' . $NoElement;
						echo '<select class="custom-select" name="' . $nommois . '" required>' ?>
						<?php
						$compteur = 1;
						echo '<option value="n/a">non concerné</option>';
						while($compteur <= 60)
						{
							echo '<option value="'.$compteur.'">'.$compteur.' mois</option>';
							$compteur += 1;
						}
					?>
						</select>
					</div>
				<?php
			
			}

			?>

			</div>

			


			<br><br><!-- n/a pour vélo -->
		

	<br><button type="submit" name="Valider" class="btn btn-primary" value="Valider">
		<i class="glyphicon glyphicon-ok-sign"></i> Valider
	</button>
	<br><br>
	<div class="nota">
		<p>Les champs suivis d'une * sont obligatoires.</p>
	</div>
	</div>
</div>
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
function rand_hexcolor()
{
	$color = dechex(mt_rand(0,16777215));
	$color = str_pad($color,6,'0');

	return $color;
}
?>