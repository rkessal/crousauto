<?php
class BDD
{
	//infos de connexions
		private $monPdo;
		
		const SERVEUR_SQL = "localhost";
		const BDD= "crous_auto_ng";
		const USER = "root";
		const MDP = "";
		/*
		const SERVEUR_SQL = "10.253.50.11";
		const BDD= "crous_auto_ng";
		const USER = "crousauto";
		const MDP = "S1mon";
		*/
	
	function __construct()
	{
		$this->monPdo = new PDO('mysql:host='.self::SERVEUR_SQL.';dbname='.self::BDD, self::USER, self::MDP);
	}

	function Profil_Connexion($Pseudo, $mdp)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT utilisateur.* FROM utilisateur where PseudoUtilisateur=:ParamPseudo AND ActifUtilisateur = 1');
		$requetePreparée->bindParam('ParamPseudo',$Pseudo);
		$reponse = $requetePreparée->execute();
		$uneLigne = $requetePreparée->fetch(PDO::FETCH_ASSOC);
		$hash = substr( $uneLigne["MotDePasseUtilisateur"], 0, 60 );
		$resultat = password_verify(trim($mdp), $hash);
		if(/*$resultat*/isset($uneLigne["MotDePasseUtilisateur"]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	 
	function Profil_Recherche($Pseudo)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT utilisateur.* FROM utilisateur where PseudoUtilisateur=:ParamPseudo AND ActifUtilisateur = 1');
		$requetePreparée->bindParam('ParamPseudo',$Pseudo);
		$reponse = $requetePreparée->execute();
		$uneLigne = $requetePreparée->fetch(PDO::FETCH_ASSOC);
		if($uneLigne)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function Droit_Liste()
	{
		$reponse = $this->monPdo->query('SELECT droit.* FROM droit ORDER BY NomDroit asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Table_maintenance_create()
	{
		$requetePreparée = $this->monPdo->prepare('
			CREATE TABLE maintenance 
			(
				idMaintenance int not null,
				EtatMaintenance int not null,
				CONSTRAINT PK_maintenance PRIMARY KEY (idMaintenance)
			);');
		$reponse = $requetePreparée->execute();
		$id = 0;
		$requetePreparée = $this->monPdo->prepare('
			INSERT INTO maintenance 
			(idMaintenance, EtatMaintenance) VALUES (:ParamId, :ParamId);');
		$requetePreparée->bindParam('ParamId',$id);
		$reponse = $requetePreparée->execute();
		return $reponse;
	}

	function Maintenance_Modif($etat)
	{
		$id = 0;
		$req = $this->monPdo->prepare("UPDATE maintenance
		SET EtatMaintenance = :ParamEtat
		WHERE idMaintenance = :ParamId");
		$req->bindParam('ParamEtat',$etat);
		$req->bindParam('ParamId',$id);
		$reponse = $req->execute();
		return $reponse;
	}

	function Maintenance_infos()
	{
		$id = 0;
		$requetePreparée = $this->monPdo->prepare('SELECT maintenance.* FROM maintenance where idMaintenance = :ParamNo');
		$requetePreparée->bindParam('ParamNo',$id);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Droit_Liste_parId($id)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT droit.* FROM droit where NoDroit = :ParamNo ORDER BY NomDroit asc');
		$requetePreparée->bindParam('ParamNo',$id);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Proprietaire_Liste()
	{
		$reponse = $this->monPdo->query('SELECT DISTINCT proprietaire.*, residence_administrative.* FROM proprietaire, residence_administrative WHERE proprietaire.NoResidence = residence_administrative.NoResidence ORDER BY NomProprietaire asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Residence_Liste()
	{
		$reponse = $this->monPdo->query('SELECT DISTINCT residence_administrative.* FROM residence_administrative ORDER BY NomResidence asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Proprietaire_Liste_parResidence($residence)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT proprietaire.*, residence_administrative.* FROM proprietaire, residence_administrative WHERE proprietaire.NoResidence = residence_administrative.NoResidence AND residence_administrative.NoResidence = :ParamResidence ORDER BY NomProprietaire asc');
		$requetePreparée->bindParam('ParamResidence',$residence);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Proprietaire_Liste_Par_Proprietaire($proprio)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT proprietaire.*, residence_administrative.* FROM proprietaire, residence_administrative WHERE proprietaire.NoResidence = residence_administrative.NoResidence AND NoProprietaire = :ParamProprietaire ORDER BY NomProprietaire asc');
		$requetePreparée->bindParam('ParamProprietaire',$proprio);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Residence_Liste_Par_Residence($residence)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT DISTINCT residence_administrative.* FROM residence_administrative WHERE NoResidence = :ParamResidence ORDER BY NomResidence asc');
		$requetePreparée->bindParam('ParamResidence',$residence);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Service_Liste()
	{
		$reponse = $this->monPdo->query('SELECT service.*, proprietaire.*, residence_administrative.* FROM service, proprietaire, residence_administrative WHERE service.NoProp = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence ORDER BY NomService asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Service_Liste_ParResidence($id)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT service.*, proprietaire.*, residence_administrative.* FROM service, proprietaire, residence_administrative WHERE service.NoProp = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence AND residence_administrative.NoResidence = :ParamId ORDER BY NomService asc');
		$requetePreparée->bindParam('ParamId',$id);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Service_Liste_ParProprietaire($id)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT service.*, proprietaire.*, residence_administrative.* FROM service, proprietaire, residence_administrative WHERE service.NoProp = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence AND proprietaire.NoProprietaire = :ParamId ORDER BY NomService asc');
		$requetePreparée->bindParam('ParamId',$id);
		$reponse = $requetePreparée->execute();
		$tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste()
	{
		$reponse = $this->monPdo->query('SELECT utilisateur.*, droit.*, proprietaire.*, residence_administrative.* FROM utilisateur, droit, proprietaire, residence_administrative  WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND residence_administrative.NoResidence = proprietaire.NoResidence ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste_parProprietaire($crous)
	{
		$reponse = $this->monPdo->prepare('SELECT utilisateur.*, droit.*, proprietaire.*, residence_administrative.* FROM utilisateur, droit, proprietaire, residence_administrative WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND residence_administrative.NoResidence = proprietaire.NoResidence AND utilisateur.NoProprietaire = :ParamCrous ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamCrous',$crous);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste_parResidence($crous)
	{
		$reponse = $this->monPdo->prepare('SELECT utilisateur.*, droit.*, proprietaire.*, residence_administrative.* FROM utilisateur, droit, proprietaire, residence_administrative WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND residence_administrative.NoResidence = proprietaire.NoResidence AND residence_administrative.NoResidence = :ParamCrous ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamCrous',$crous);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}


	function User_Liste_parDroit_SiGestionVehicule_MailAdminEtGest($immat)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilisateur.* FROM utilisateur, droit, proprietaire, vehicule WHERE (utilisateur.NoProprietaire = proprietaire.NoProprietaire and vehicule.NoProprietaire = proprietaire.NoProprietaire and vehicule.ImmatriculationVehicule = :ParamImmat AND utilisateur.NoDroit = 2) OR (utilisateur.NoDroit = 1 and utilisateur.ActifUtilisateur = 1 and utilisateur.NoProprietaire = proprietaire.NoProprietaire and NoResidence = (SELECT DISTINCT NoResidence FROM vehicule, proprietaire WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire and vehicule.ImmatriculationVehicule = :ParamImmat))');
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste_parDroit_SiGestionVehicule($immat, $droit)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilisateur.*, droit.*, proprietaire.*, residence_administrative.*, gerer.* FROM utilisateur, gerer, droit, residence_administrative, proprietaire WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND gerer.ImmatriculationVehicule = :ParamImmat AND gerer.NoUtilisateur = utilisateur.NoUtilisateur AND residence_administrative.NoResidence = proprietaire.NoResidence AND utilisateur.NoDroit = :ParamDroit ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamDroit',$droit);
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste_parDroitEtProprietaire($prop, $droit)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilisateur.*, droit.*, proprietaire.*, residence_administrative.* FROM utilisateur, droit, residence_administrative, proprietaire WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND proprietaire.NoProprietaire = :ParamProp AND ActifUtilisateur = 1 AND residence_administrative.NoResidence = proprietaire.NoResidence AND utilisateur.NoDroit = :ParamDroit ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamDroit',$droit);
		$reponse->bindParam('ParamProp',$prop);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste()
	{
		$reponse = $this->monPdo->query('SELECT conducteur.*, service.*, proprietaire.*, residence_administrative.*, utilisateur.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService AND proprietaire.NoResidence = residence_administrative.NoResidence AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur ORDER BY NomProprietaire ASC, NomConducteur asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste_Actif()
	{
		$reponse = $this->monPdo->query('SELECT conducteur.*, service.*, proprietaire.*, residence_administrative.*, utilisateur.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService AND proprietaire.NoResidence = residence_administrative.NoResidence AND ActifConducteur = 1 AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur ORDER BY NomProprietaire ASC, NomConducteur asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste_ParProprietaire($crous)
	{
		$reponse = $this->monPdo->prepare('SELECT conducteur.*, utilisateur.*, service.*, proprietaire.*, residence_administrative.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService and utilisateur.NoProprietaire = :ParamProprietaire AND proprietaire.NoResidence = residence_administrative.NoResidence AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur ORDER BY NomProprietaire ASC, NomConducteur asc');
		$reponse->bindParam('ParamProprietaire',$crous);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste_ParResidence($crous)
	{
		$reponse = $this->monPdo->prepare('SELECT conducteur.*, utilisateur.*, service.*, proprietaire.*, residence_administrative.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService and residence_administrative.NoResidence = :ParamProprietaire AND proprietaire.NoResidence = residence_administrative.NoResidence AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur ORDER BY NomProprietaire ASC, NomConducteur asc');
		$reponse->bindParam('ParamProprietaire',$crous);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste_ParUser($noUser)
	{
		$reponse = $this->monPdo->prepare('SELECT conducteur.*, utilisateur.*, service.*, proprietaire.*, residence_administrative.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService AND proprietaire.NoResidence = residence_administrative.NoResidence AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur and conducteur.NoUtilisateur = :ParamId ORDER BY NomConducteur asc');
		$reponse->bindParam('ParamId',$noUser);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Liste_ParUserEtConducteur($noUser, $NoConducteur)
	{
		$reponse = $this->monPdo->prepare('SELECT conducteur.*, utilisateur.*, service.*, proprietaire.*, residence_administrative.* FROM conducteur, service, proprietaire, residence_administrative, utilisateur WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND conducteur.NoService = service.NoService AND proprietaire.NoResidence = residence_administrative.NoResidence AND utilisateur.NoUtilisateur = conducteur.NoUtilisateur and conducteur.NoUtilisateur = :ParamId and conducteur.NoConducteur = :ParamCond ORDER BY NomConducteur asc');
		$reponse->bindParam('ParamId',$noUser);
		$reponse->bindParam('ParamCond',$NoConducteur);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Element_Liste()
	{
		$reponse = $this->monPdo->query('SELECT element.* FROM element ORDER BY LibelleElement asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Entretien_Liste()
	{
		$reponse = $this->monPdo->query('SELECT entretien.* FROM entretien ORDER BY TypeEntretien asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationService($date1, $date2)
	{
		$reponse = $this->monPdo->prepare('SELECT NomService, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, conducteur, service WHERE utilise.NoConducteur = conducteur.NoConducteur AND conducteur.NoService = service.NoService AND DateDebutUtilisation  BETWEEN :ParamDate1 AND :ParamDate2  GROUP BY NomService ORDER BY NomService asc');
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationServiceParResidence($date1, $date2, $residence)
	{
		$reponse = $this->monPdo->prepare('SELECT NomService, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, conducteur, service, utilisateur, proprietaire WHERE utilise.NoConducteur = conducteur.NoConducteur AND conducteur.NoService = service.NoService AND conducteur.NoUtilisateur = utilisateur.NoUtilisateur AND utilisateur.NoProprietaire = proprietaire.NoProprietaire AND NoResidence = :ParamResidence AND DateDebutUtilisation  BETWEEN :ParamDate1 AND :ParamDate2  GROUP BY NomService ORDER BY NomService asc');
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationServiceParImmat($immat, $date1, $date2)
	{
		$reponse = $this->monPdo->prepare('SELECT NomService, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, conducteur, service WHERE utilise.NoConducteur = conducteur.NoConducteur AND conducteur.NoService = service.NoService AND ImmatriculationVehicule = :ParamPseudo and DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY NomService ORDER BY NomService asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationVehiculeParImmat($immat, $date1, $date2)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, vehicule WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND vehicule.ImmatriculationVehicule = :ParamPseudo AND DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationVehiculeParImmatEtResidence($immat, $date1, $date2, $residence)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, vehicule, proprietaire WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND vehicule.ImmatriculationVehicule = :ParamPseudo AND DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2 AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND NoResidence = :ParamResidence  GROUP BY vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationVehicule_parResidence($date1, $date2, $residence)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, vehicule, proprietaire WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND NoResidence = :ParamResidence AND DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_ListeUtilisationVehicule($date1, $date2)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule, SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, vehicule WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY vehicule.ImmatriculationVehicule, ConstructeurVehicule, ModeleVehicule  ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Statistique_General_Liste()
	{
		$reponse = $this->monPdo->prepare('SELECT SUM(HOUR(DateFinUtilisation) - HOUR(DateDebutUtilisation)) AS Nb FROM utilise, conducteur, service WHERE utilise.NoConducteur = conducteur.NoConducteur AND conducteur.NoService = service.NoService  ORDER BY NomService asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Cout_Liste_Entre2Dates_ParResidence($date1, $date2, $residence)
	{
		$reponse = $this->monPdo->prepare("
			SELECT TAB1.ImmatriculationVehicule, SUM(TAB1.Montant) AS Montant
				FROM (
					SELECT DISTINCT passer_entretien.ImmatriculationVehicule, SUM(passer_entretien.MontantEntretien) AS Montant FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND DatePassageEntretien BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
					SELECT DISTINCT carte_essence.ImmatriculationVehicule, SUM(Montant) AS Montant FROM carte_essence, vehicule, utiliser_carte_essence WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND utiliser_carte_essence.NoCarte = carte_essence.NoCarte AND DateUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, carte_essence.NoCarte
			        UNION
			            SELECT DISTINCT vehicule.ImmatriculationVehicule, SUM(Montant) AS Montant FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND DateTelepeage BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, telepeage.NoTelepeage
					UNION
						SELECT ImmatriculationVehicule, SUM(passer_controle_technique.MontantControle) AS Montant FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND DatePassageControle BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
						SELECT ImmatriculationVehicule, SUM(MontantAssurance) as Montant FROM assurance WHERE DateAssurance BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
				)AS TAB1, vehicule, proprietaire
				WHERE vehicule.ImmatriculationVehicule = TAB1.ImmatriculationVehicule AND proprietaire.NoProprietaire = vehicule.NoProprietaire AND NoResidence = :ParamResidence 
				GROUP BY TAB1.ImmatriculationVehicule
				ORDER BY TAB1.ImmatriculationVehicule asc");
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Cout_Liste_Entre2Dates_ParType_EtVehicule($immat, $date1, $date2)
	{
		$reponse = $this->monPdo->prepare("
			SELECT TAB1.NomCout as NomCout, SUM(TAB1.Montant) AS Montant
				FROM (
					SELECT DISTINCT 'Entretien' AS NomCout, ImmatriculationVehicule, SUM(passer_entretien.MontantEntretien) AS Montant FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND DatePassageEntretien BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
					SELECT DISTINCT 'Carburant' AS NomCout, vehicule.ImmatriculationVehicule, SUM(Montant) AS Montant FROM carte_essence, vehicule, utiliser_carte_essence WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND utiliser_carte_essence.NoCarte = carte_essence.NoCarte AND DateUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, carte_essence.NoCarte
			        UNION
			            SELECT DISTINCT 'Peage' AS NomCout, vehicule.ImmatriculationVehicule, SUM(Montant) AS Montant FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND DateTelepeage BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, telepeage.NoTelepeage
					UNION
						SELECT 'Controle Technique' AS NomCout, ImmatriculationVehicule, SUM(passer_controle_technique.MontantControle) AS Montant FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND DatePassageControle BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
						SELECT 'Assurance' AS NomCout, ImmatriculationVehicule,SUM(MontantAssurance) as Montant FROM assurance WHERE DateAssurance BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
				)AS TAB1
				WHERE TAB1.ImmatriculationVehicule = :ParamImmat
				GROUP BY TAB1.NomCout, TAB1.ImmatriculationVehicule
				ORDER BY TAB1.NomCout asc");
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Cout_Liste_Entre2Dates($date1, $date2)
	{
		$reponse = $this->monPdo->prepare("
			SELECT TAB1.ImmatriculationVehicule, SUM(TAB1.Montant) AS Montant
				FROM (
					SELECT DISTINCT passer_entretien.ImmatriculationVehicule, SUM(passer_entretien.MontantEntretien) AS Montant FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND DatePassageEntretien BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
					SELECT DISTINCT carte_essence.ImmatriculationVehicule, SUM(Montant) AS Montant FROM carte_essence, vehicule, utiliser_carte_essence WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND utiliser_carte_essence.NoCarte = carte_essence.NoCarte AND DateUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, carte_essence.NoCarte
			        UNION
			            SELECT DISTINCT vehicule.ImmatriculationVehicule, SUM(Montant) AS Montant FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND DateTelepeage BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, telepeage.NoTelepeage
					UNION
						SELECT ImmatriculationVehicule, SUM(passer_controle_technique.MontantControle) AS Montant FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND DatePassageControle BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
						SELECT ImmatriculationVehicule, SUM(MontantAssurance) as Montant FROM assurance WHERE DateAssurance BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
				)AS TAB1
				GROUP BY TAB1.ImmatriculationVehicule
				ORDER BY TAB1.ImmatriculationVehicule asc");
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Cout_Liste_Entre2Dates_ParVehicule($immat, $date1, $date2)
	{
		$reponse = $this->monPdo->prepare("
			SELECT TAB1.ImmatriculationVehicule, SUM(TAB1.Montant) AS Montant
				FROM (
					SELECT DISTINCT passer_entretien.ImmatriculationVehicule, SUM(passer_entretien.MontantEntretien) AS Montant FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND DatePassageEntretien BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
					SELECT DISTINCT carte_essence.ImmatriculationVehicule, SUM(Montant) AS Montant FROM carte_essence, vehicule, utiliser_carte_essence WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND utiliser_carte_essence.NoCarte = carte_essence.NoCarte AND DateUtilisation BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, carte_essence.NoCarte
			        UNION
			            SELECT DISTINCT vehicule.ImmatriculationVehicule, SUM(Montant) AS Montant FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND DateTelepeage BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule, telepeage.NoTelepeage
					UNION
						SELECT ImmatriculationVehicule, SUM(passer_controle_technique.MontantControle) AS Montant FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND DatePassageControle BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
					UNION
						SELECT ImmatriculationVehicule, SUM(MontantAssurance) as Montant FROM assurance WHERE DateAssurance BETWEEN :ParamDate1 AND :ParamDate2 GROUP BY ImmatriculationVehicule
				)AS TAB1
				WHERE TAB1.ImmatriculationVehicule = :ParamImmat
				GROUP BY TAB1.ImmatriculationVehicule
				ORDER BY TAB1.ImmatriculationVehicule asc");
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Liste_parImmatriculation($immat)
	{
		$reponse = $this->monPdo->prepare('SELECT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = :ParamPseudo AND DATEDIFF(NOW(),DateFinUtilisation) <= 30 ORDER BY DateDebutUtilisation desc, conducteur.NomConducteur asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Liste_parUser($user)
	{
		$reponse = $this->monPdo->prepare('SELECT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur and ActifVehicule = 1 AND NoUtilisateur = :ParamPseudo AND DATEDIFF(NOW(),DateFinUtilisation) <= 60 ORDER BY DateDebutUtilisation desc');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Verificaton_parImmatriculationEntre2Dates($immat, $date1, $date2, $idDeplacement)
	{
		$reponse = $this->monPdo->prepare('
			SELECT utilise.* 
			FROM utilise 
			WHERE ImmatriculationVehicule = :ParamPseudo 
			AND (
					(DateDebutUtilisation BETWEEN :ParamDate1 AND :ParamDate2)
				OR (DateFinUtilisation BETWEEN :ParamDate1 AND :ParamDate2)
				OR (:ParamDate1 BETWEEN DateDebutUtilisation AND DateFinUtilisation)
				OR (:ParamDate2 BETWEEN DateDebutUtilisation AND DateFinUtilisation)/*
				OR DateDebutUtilisation < :ParamDate1
				OR DateFinUtilisation > :ParamDate2
				OR DateDebutUtilisation < :ParamDate2 
				OR DateFinUtilisation > :ParamDate1*/
			)
			AND idDeplacement <> :ParamIdDeplacement 
			ORDER BY DateDebutUtilisation desc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->bindParam('ParamIdDeplacement',$idDeplacement);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Liste_parImmatriculation_et_parMois($immat, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = :ParamPseudo AND (DateDebutUtilisation LIKE :ParamDate OR DateFinUtilisation LIKE :ParamDate) ORDER BY DateDebutUtilisation ASC');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Reserver_Liste_ParIdCondDateRes($immat, $conducteur, $DateHeureReservation)
	{
		$reponse = $this->monPdo->prepare('SELECT reserver.*, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = :ParamPseudo AND conducteur.NoConducteur = :ParamConducteur AND DateHeureReservation = :ParamDateHeure');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamConducteur',$conducteur);
		$reponse->bindParam('ParamDateHeure',$DateHeureReservation);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}	

	function Reserver_Liste_ParIdCondDateDebutUtil($immat, $conducteur, $DateDebutUtilisation)
	{
		$reponse = $this->monPdo->prepare('SELECT reserver.*, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND ActifVehicule = 1 AND vehicule.ImmatriculationVehicule = :ParamPseudo AND conducteur.NoConducteur = :ParamConducteur AND DateDebutUtilisation = :ParamDateHeure');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamConducteur',$conducteur);
		$reponse->bindParam('ParamDateHeure',$DateDebutUtilisation);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}	

	function Reserver_Liste_ParIdCondDateDebutUtilDestNbPers($immat, $conducteur, $DateDebutUtilisation, $dest, $nbPers)
	{
		$reponse = $this->monPdo->prepare('SELECT reserver.*, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = :ParamPseudo AND ActifVehicule = 1 AND conducteur.NoConducteur = :ParamConducteur AND DateDebutUtilisation = :ParamDateHeure AND Destination = :ParamDestination AND NbPersonnes = :ParamNbPassagers');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->bindParam('ParamConducteur',$conducteur);
		$reponse->bindParam('ParamDateHeure',$DateDebutUtilisation);
		$reponse->bindParam('ParamDestination',$dest);
		$reponse->bindParam('ParamNbPassagers',$nbPers);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}	

	function Utilise_Liste_parImmatriculation_ParDate_SiGestion($user, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilise.*, vehicule.*, conducteur.*, proprietaire.*, gerer.* FROM utilise, vehicule, conducteur, proprietaire, gerer WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamPseudo AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Liste_parImmatriculation_ParDate_EtProprietaire($date, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = :ParamProprietaire AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Liste_parImmatriculation_ParDate($date)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}
	
	function Utilise_Liste_parImmatriculation_ParDateEtProprietaire($date, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utilise.*, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = :ParamProp AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation_ParProprietaire($proprietaire)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.*, proprietaire.* FROM utilise, vehicule, conducteur, proprietaire WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND proprietaire.NoProprietaire = vehicule.NoProprietaire AND proprietaire.NoResidence = :ParamProp 
			UNION 
			SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.*, proprietaire.* FROM reserver, vehicule, conducteur, proprietaire WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND proprietaire.NoProprietaire = vehicule.NoProprietaire AND proprietaire.NoResidence = :ParamProp

		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation()
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination,utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur  UNION SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur

		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		//$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation_SiGestion($idUser, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND LibreServiceVehicule = 1 AND NoProprietaire = :ParamProp
			UNION
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, gerer, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamPseudo 
			UNION
			SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, gerer, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamPseudo 
			UNION
			SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND LibreServiceVehicule = 1 AND NoProprietaire = :ParamProp  
		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamPseudo',$idUser);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation_ParDateEtProprietaire($date, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.*, proprietaire.* FROM utilise, vehicule, conducteur, proprietaire WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND proprietaire.NoProprietaire = vehicule.NoProprietaire AND proprietaire.NoResidence = :ParamProp AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) UNION SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination,reserver.NbPersonnes, vehicule.*, conducteur.*, proprietaire.* FROM reserver, vehicule, conducteur, proprietaire WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND proprietaire.NoProprietaire = vehicule.NoProprietaire AND proprietaire.NoResidence = :ParamProp AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate)

		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation_ParDate($date)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination,utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) UNION SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate)

		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function UtiliseEtReserve_Liste_parImmatriculation_ParDate_SiGestion($idUser, $date, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND LibreServiceVehicule = 1 AND NoProprietaire = :ParamProp AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) 
			UNION
			SELECT DISTINCT null AS DateHeureReservation, utilise.DateDebutUtilisation, utilise.DateFinUtilisation, utilise.idDeplacement AS id, utilise.Destination, utilise.NbPersonnes, vehicule.*, conducteur.* FROM utilise, vehicule, gerer, conducteur WHERE utilise.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND utilise.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamPseudo AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) 
			UNION
			SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, gerer, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamPseudo AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) 
			UNION
			SELECT DISTINCT DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.idReservation AS id, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* FROM reserver, vehicule, conducteur WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND LibreServiceVehicule = 1 AND NoProprietaire = :ParamProp AND (DateDebutUtilisation LIKE :ParamDate or DateFinUtilisation LIKE :ParamDate) 
		 ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamDate',$date);
		$reponse->bindParam('ParamPseudo',$idUser);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}


	function Reserver_Liste_ParUser_SiGestion($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
				SELECT DISTINCT idReservation, DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* 
				FROM reserver, vehicule, gerer, conducteur 
				WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule 
				AND reserver.NoConducteur = conducteur.NoConducteur 
				AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule 
				AND gerer.NoUtilisateur = :ParamPseudo
			UNION
				SELECT DISTINCT idReservation, DateHeureReservation, reserver.DateDebutUtilisation, reserver.DateFinUtilisation, reserver.Destination, reserver.NbPersonnes, vehicule.*, conducteur.* 
				FROM reserver, vehicule, conducteur 
				WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule 
				AND reserver.NoConducteur = conducteur.NoConducteur 
				AND LibreServiceVehicule = 1 
				AND NoProprietaire = :ParamProp
		 	ORDER BY DateDebutUtilisation ASC, DateFinUtilisation asc');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Reserver_Liste_ParProprietaire($proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT reserver.*, vehicule.*, conducteur.*, proprietaire.* FROM reserver, vehicule, conducteur, proprietaire WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.NoProprietaire = :ParamProprietaire ORDER BY vehicule.ImmatriculationVehicule ASC, DateHeureReservation asc');
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Reserver_Liste_ParResidence($residence)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT reserver.*, vehicule.*, conducteur.*, proprietaire.*, residence_administrative.* FROM reserver, vehicule, conducteur, proprietaire, residence_administrative WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence AND proprietaire.NoResidence = :ParamResidence ORDER BY vehicule.ImmatriculationVehicule ASC, DateHeureReservation asc');
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Reserver_Liste()
	{
		$reponse = $this->monPdo->query('SELECT DISTINCT * FROM reserver, vehicule, conducteur, proprietaire WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = proprietaire.NoProprietaire ORDER BY vehicule.ImmatriculationVehicule ASC, DateHeureReservation asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Reserver_Liste_parId($idReservation)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT reserver.*, vehicule.*, conducteur.*, proprietaire.* FROM reserver, vehicule, conducteur, proprietaire WHERE reserver.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND reserver.NoConducteur = conducteur.NoConducteur AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND idReservation = :ParamId');
		$reponse->bindParam('ParamId',$idReservation);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Liste_parPseudo($pseudo)
	{
		$reponse = $this->monPdo->prepare('SELECT utilisateur.*, droit.*, proprietaire.*, residence_administrative.* FROM utilisateur, droit, proprietaire, residence_administrative WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND residence_administrative.NoResidence = proprietaire.NoResidence AND PseudoUtilisateur = :ParamPseudo ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamPseudo',$pseudo);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Controle_Technique_Liste()
	{
		$reponse = $this->monPdo->query('SELECT controle_technique.* FROM controle_technique ORDER BY NoControle asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function CarteEssence_Liste_ParUser_SiUtiliser($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
				SELECT DISTINCT carte_essence.*
				FROM carte_essence, vehicule, gerer
				WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule 
				AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule 
				AND gerer.NoUtilisateur = :ParamPseudo 
				GROUP BY carte_essence.NoCarte
			UNION
				SELECT DISTINCT carte_essence.*
				FROM carte_essence, vehicule
				WHERE vehicule.NoProprietaire = :ParamProp 
				AND LibreServiceVehicule = 1 
				AND vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule 
				GROUP BY carte_essence.NoCarte
			ORDER BY NomCarte ASC');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function CarteEssence_Liste()
	{
		$reponse = $this->monPdo->query('SELECT DISTINCT carte_essence.* FROM carte_essence, vehicule WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule GROUP BY carte_essence.NoCarte ORDER BY NomCarte ASC, vehicule.ImmatriculationVehicule asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function CarteEssence_Liste_ParImmat($immat)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT carte_essence.* FROM carte_essence, vehicule WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND vehicule.ImmatriculationVehicule = :ParamPseudo GROUP BY carte_essence.NoCarte ORDER BY NomCarte ASC, vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function CarteEssence_Liste_ParProprietaire($proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT vehicule.*, carte_essence.*  FROM carte_essence, vehicule WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND vehicule.NoProprietaire = :ParamProprietaire GROUP BY carte_essence.NoCarte
			ORDER BY NomCarte ASC, vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function CarteEssence_Liste_ParResidence($residence)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT vehicule.*, carte_essence.*
			FROM carte_essence, vehicule, proprietaire, residence_administrative 
			WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule 
			AND vehicule.NoProprietaire = proprietaire.NoProprietaire 
			AND proprietaire.NoResidence = :ParamResidence 
			GROUP BY carte_essence.NoCarte
		ORDER BY NomCarte ASC');
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Liste_ParUser_SiUtiliser($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
				SELECT DISTINCT vehicule.*, telepeage.*, utiliser_telepeage.*, SUM(Montant) AS MontantTotal
				FROM telepeage, vehicule, gerer, utiliser_telepeage 
				WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule 
				AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule 
				AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage 
				AND gerer.NoUtilisateur = :ParamPseudo 
				GROUP BY telepeage.NoTelepeage
			UNION
				SELECT DISTINCT vehicule.*, telepeage.*,  utiliser_telepeage.*, SUM(Montant) AS MontantTotal 
				FROM telepeage, vehicule, utiliser_telepeage
				WHERE vehicule.NoProprietaire = :ParamProp 
				AND LibreServiceVehicule = 1 
				AND vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule 
				AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage
				GROUP BY telepeage.NoTelepeage
			ORDER BY NomTelepeage ASC');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Liste()
	{
		$reponse = $this->monPdo->query('SELECT DISTINCT vehicule.*, telepeage.*, utiliser_telepeage.*, SUM(Montant) AS MontantTotal  FROM telepeage, vehicule,  utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage GROUP BY telepeage.NoTelepeage ORDER BY NomTelepeage ASC, vehicule.ImmatriculationVehicule asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Liste_parProprietaire($proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT vehicule.*, telepeage.*, utiliser_telepeage.*, SUM(Montant) AS MontantTotal  FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND NoProprietaire = :ParamProprietaire AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage GROUP BY telepeage.NoTelepeage
			ORDER BY NomTelepeage ASC, vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Liste_parResidence($residence)
	{
		$reponse = $this->monPdo->prepare('
		SELECT DISTINCT vehicule.*, telepeage.*, utiliser_telepeage.*, SUM(Montant) AS MontantTotal  FROM telepeage, vehicule, utiliser_telepeage, proprietaire, residence_administrative WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND proprietaire.NoResidence = :ParamResidence AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage GROUP BY telepeage.NoTelepeage
		 ORDER BY NomTelepeage ASC');
		$reponse->bindParam('ParamResidence',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Liste_ParImmat($immat)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT vehicule.*, telepeage.*, utiliser_telepeage.*, SUM(Montant) AS Montant  FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND vehicule.ImmatriculationVehicule = :ParamPseudo GROUP BY telepeage.NoTelepeage ORDER BY NomTelepeage ASC, vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamPseudo',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utiliser_Telepeage_Liste_ParId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT utiliser_telepeage.*  FROM utiliser_telepeage WHERE NoTelepeage = :ParamId ORDER BY DateTelepeage desc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utiliser_Carte_Essence_Liste_ParId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT utiliser_carte_essence.*, conducteur.*, (Montant) AS Montant  FROM conducteur, utiliser_carte_essence WHERE utiliser_carte_essence.NoConducteur = conducteur.NoConducteur AND NoCarte = :ParamId ORDER BY DateUtilisation desc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste()
	{
		$reponse = $this->monPdo->query('SELECT vehicule.*, proprietaire.*, residence_administrative.* FROM vehicule, proprietaire, residence_administrative WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND proprietaire.NoResidence = residence_administrative.NoResidence ORDER BY NomProprietaire ASC, vehicule.ImmatriculationVehicule asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}
	
	function Vehicule_Liste_SiPasBadgeT($proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT vehicule.* FROM vehicule, proprietaire WHERE vehicule.ImmatriculationVehicule not in (SELECT telepeage.ImmatriculationVehicule FROM telepeage) AND vehicule.NoProprietaire = proprietaire.NoProprietaire and NoResidence = :ParamProprietaire and ActifVehicule = 1 ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_SiPasCarteEssence($proprietaire)
	{
		$reponse = $this->monPdo->prepare('SELECT DISTINCT vehicule.* FROM vehicule, proprietaire WHERE vehicule.ImmatriculationVehicule not in (SELECT carte_essence.ImmatriculationVehicule FROM carte_essence) and vehicule.NoProprietaire = proprietaire.NoProprietaire and proprietaire.NoResidence = :ParamProprietaire  and ActifVehicule = 1 ORDER BY vehicule.ImmatriculationVehicule asc');
		$reponse->bindParam('ParamProprietaire',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_parImmatriculation($immatriculation)
	{

		$reponse = $this->monPdo->prepare('SELECT vehicule.*, proprietaire.*, residence_administrative.* FROM vehicule, proprietaire, residence_administrative WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND ImmatriculationVehicule = :ParamImmat AND residence_administrative.NoResidence = proprietaire.NoResidence ORDER BY NomProprietaire ASC, ImmatriculationVehicule asc');
		$reponse->bindParam('ParamImmat',$immatriculation);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_parProprietaire($proprietaire)
	{

		$reponse = $this->monPdo->prepare('SELECT vehicule.*, proprietaire.* FROM vehicule, proprietaire WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND proprietaire.NoProprietaire = :ParamId and ActifVehicule = 1  ORDER BY NomProprietaire ASC, ImmatriculationVehicule asc');
		$reponse->bindParam('ParamId',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_parResidence($residence)
	{

		$reponse = $this->monPdo->prepare('SELECT vehicule.*, proprietaire.*, residence_administrative.* FROM vehicule, proprietaire, residence_administrative WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND proprietaire.NoResidence = residence_administrative.NoResidence AND residence_administrative.NoResidence = :ParamId  and ActifVehicule = 1 ORDER BY NomProprietaire ASC, ImmatriculationVehicule asc');
		$reponse->bindParam('ParamId',$residence);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_ParUser_SiGestion($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
			SELECT DISTINCT vehicule.*, proprietaire.*, residence_administrative.* FROM vehicule, gerer, proprietaire, residence_administrative WHERE vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence AND NoUtilisateur = :ParamPseudo  and ActifVehicule = 1 
			UNION
			SELECT DISTINCT vehicule.*, proprietaire.*, residence_administrative.* FROM vehicule, proprietaire, residence_administrative WHERE vehicule.NoProprietaire = :ParamId AND LibreServiceVehicule = 1 AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND residence_administrative.NoResidence = proprietaire.NoResidence and ActifVehicule = 1 
			ORDER BY ImmatriculationVehicule asc');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamId',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_ParUser_SiGestion_SiPasCarteEssence($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
				SELECT DISTINCT vehicule.*, proprietaire.* FROM vehicule, proprietaire, gerer WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule not in (SELECT carte_essence.ImmatriculationVehicule FROM carte_essence) AND gerer.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND NoUtilisateur = :ParamPseudo and ActifVehicule = 1 
			UNION
				SELECT DISTINCT vehicule.*, proprietaire.* FROM vehicule, proprietaire  WHERE vehicule.NoProprietaire = :ParamProp AND LibreServiceVehicule = 1 AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule  not in (SELECT carte_essence.ImmatriculationVehicule FROM carte_essence) and ActifVehicule = 1 

			ORDER BY NomProprietaire ASC');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Liste_ParUser_SiGestion_SiPasBadgeT($user, $proprietaire)
	{
		$reponse = $this->monPdo->prepare('
				SELECT DISTINCT vehicule.*, proprietaire.* FROM vehicule, proprietaire, gerer WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule  AND vehicule.ImmatriculationVehicule not in (SELECT telepeage.ImmatriculationVehicule FROM telepeage) AND NoUtilisateur = :ParamPseudo and ActifVehicule = 1  
			UNION
				SELECT DISTINCT vehicule.*, proprietaire.* FROM vehicule, proprietaire WHERE vehicule.NoProprietaire = :ParamProp AND LibreServiceVehicule = 1 AND vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule not in (SELECT telepeage.ImmatriculationVehicule FROM telepeage) and ActifVehicule = 1 

			ORDER BY NomProprietaire ASC');
		$reponse->bindParam('ParamPseudo',$user);
		$reponse->bindParam('ParamProp',$proprietaire);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Create($pseudo, $mdp, $droit, $actif, $proprietaire)
	{
		$req = $this->monPdo->prepare("INSERT INTO utilisateur(PseudoUtilisateur, MotDePasseUtilisateur , NoDroit, ActifUtilisateur, NoProprietaire) values (:ParamPseudo, :ParamMdp, :ParamDroit,:ParamActif, :ParamProprietaire)");
		$req->bindParam('ParamPseudo',$pseudo);
		$req->bindParam('ParamMdp',$mdp);
		$req->bindParam('ParamDroit',$droit);
		$req->bindParam('ParamActif',$actif);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$reponse = $req->execute();
		return $reponse;
	}

	function Kilometrage_Create($immat, $date, $km)
	{
		$req = $this->monPdo->prepare("INSERT INTO kilometrage (ImmatriculationVehicule, DateKilometrage, KilometrageReleve) values (:ParamImmat, :ParamDate, :ParamKm)");
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$reponse = $req->execute();
		return $reponse;
	}

	function Kilometrage_Modif($immat, $date, $km)
	{
		$req = $this->monPdo->prepare("
			UPDATE kilometrage 
			SET KilometrageReleve = :ParamKm
			WHERE ImmatriculationVehicule = :ParamImmat
			AND DateKilometrage = :ParamDate");
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$reponse = $req->execute();
		return $reponse;
	}

	function Assurance_Create($immat, $date, $nom, $montant)
	{
		$req = $this->monPdo->prepare("INSERT INTO assurance (ImmatriculationVehicule, DateAssurance , NomAssurance, MontantAssurance) values (:ParamImmat, :ParamDate, :ParamNom,:ParamMontant)");
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Assurance_Modif($immat, $date, $nom, $montant)
	{
		$req = $this->monPdo->prepare("
			UPDATE assurance 
			SET NomAssurance = :ParamNom,
			MontantAssurance = :ParamMontant
			WHERE ImmatriculationVehicule = :ParamImmat
			AND DateAssurance = :ParamDate");
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utilise_Create($id, $datedebut, $datefin, $destination, $conducteur, $passagers)
	{
		$req = $this->monPdo->prepare("INSERT INTO utilise(ImmatriculationVehicule, DateDebutUtilisation, DateFinUtilisation, Destination, NoConducteur, NbPersonnes) values (:ParamId, :ParamDateDebut, :ParamDateFin, :ParamDestination, :ParamConducteur, :ParamNbPassagers)");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamDateDebut',$datedebut);
		$req->bindParam('ParamDateFin',$datefin);
		$req->bindParam('ParamDestination',$destination);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamNbPassagers',$passagers);
		$reponse = $req->execute();
		return $reponse;
	}

function Reserve_Create($id, $datedebut, $datefin, $destination, $conducteur, $dateEtHeure, $passagers)
	{
		$req = $this->monPdo->prepare("INSERT INTO reserver(ImmatriculationVehicule, DateDebutUtilisation, DateFinUtilisation, Destination, NoConducteur, DateHeureReservation, NbPersonnes) values (:ParamId, :ParamDateDebut, :ParamDateFin,:ParamDestination, :ParamConducteur, :ParamDateHeure, :ParamNbPassagers)");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamDateDebut',$datedebut);
		$req->bindParam('ParamDateFin',$datefin);
		$req->bindParam('ParamDestination',$destination);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamDateHeure',$dateEtHeure);
		$req->bindParam('ParamNbPassagers',$passagers);
		$reponse = $req->execute();
		return $reponse;
	}

	function Conducteur_Create($nom, $prenom, $actif, $adresse, $cp, $ville, $telephone, $portable, $service, $permis, $doc, $user)
	{
		$req = $this->monPdo->prepare("INSERT INTO conducteur (NomConducteur, PrenomConducteur , ActifConducteur, AdresseConducteur, CPConducteur, VilleConducteur, TelephoneConducteur, PortableConducteur, NoService, PermisConducteur, ScanPermis, NoUtilisateur) values (:ParamNom, :ParamPrenom, :ParamActif, :ParamAdresse, :ParamCP, :ParamVille, :ParamTelephone, :ParamPortable, :ParamService, :ParamPermis, :ParamDoc, :ParamUser)");
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamPrenom',$prenom);
		$req->bindParam('ParamActif',$actif);
		$req->bindParam('ParamAdresse',$adresse);
		$req->bindParam('ParamCP',$cp);
		$req->bindParam('ParamPermis',$permis);
		$req->bindParam('ParamVille',$ville);
		$req->bindParam('ParamTelephone',$telephone);
		$req->bindParam('ParamPortable',$portable);
		$req->bindParam('ParamService',$service);
		$req->bindParam('ParamDoc',$doc);
		$req->bindParam('ParamUser',$user);
		$reponse = $req->execute();
		return $reponse;
	}
	
	function Carte_Essence_Create($nom, $vehicule, $fournisseur, $dateRenouvellement)
	{
		$req = $this->monPdo->prepare("INSERT INTO carte_essence(NomCarte, ImmatriculationVehicule, FournisseurCarte, DateRenouvellementCarte) values (:ParamNom, :ParamImmat, :ParamFournisseur, :ParamDateRenouvellement)");
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamImmat',$vehicule);
		$req->bindParam('ParamFournisseur',$fournisseur);
		$req->bindParam('ParamDateRenouvellement',$dateRenouvellement);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utiliser_Carte_Essence_Create($nom, $conducteur, $date, $km, $litre, $PrixAuLitre, $station)
	{
		$req = $this->monPdo->prepare("INSERT INTO utiliser_carte_essence(NoCarte, NoConducteur, DateUtilisation, KilometrageCarte, Litre, Montant, Station) values (:ParamNo, :ParamCond, :ParamDate, :ParamKm, :ParamLitre, :ParamPrixAuLitre, :ParamStation)");
		$req->bindParam('ParamNo',$nom);
		$req->bindParam('ParamCond',$conducteur);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamLitre',$litre);
		$req->bindParam('ParamPrixAuLitre',$PrixAuLitre);
		$req->bindParam('ParamStation',$station);
		$reponse = $req->execute();
		return $reponse;
	}

	function Telepeage_Create($nom, $vehicule, $fournisseur, $abonnement)
	{
		$req = $this->monPdo->prepare("INSERT INTO telepeage(NomTelepeage, ImmatriculationVehicule, FournisseurTelepeage, AbonnementTelepeage) values (:ParamNom, :ParamImmat, :ParamFournisseur, :ParamAbonnement)");
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamImmat',$vehicule);
		$req->bindParam('ParamFournisseur',$fournisseur);
		$req->bindParam('ParamAbonnement',$abonnement);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utiliser_Telepeage_Create($no, $date, $montant)
	{
		$req = $this->monPdo->prepare("INSERT INTO utiliser_telepeage(NoTelepeage, DateTelepeage, Montant) values (:ParamNo, :ParamDate, :ParamMontant)");
		$req->bindParam('ParamNo',$no);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Proprietaire_Create($Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, $residence)
	{
		$req = $this->monPdo->prepare("
			INSERT INTO proprietaire (NomProprietaire, AdresseProprietaire, VilleProprietaire, CPProprietaire, TelephoneProprietaire, FaxProprietaire, NoResidence) 
			values (:ParamNom, :ParamAdresse, :ParamVille, :ParamCP, :ParamTelephone, :ParamFax,  :ParamResidence)");
		$req->bindParam('ParamNom',$Nom);
		$req->bindParam('ParamAdresse',$Adresse);
		$req->bindParam('ParamVille',$Ville);
		$req->bindParam('ParamCP',$CP);
		$req->bindParam('ParamTelephone',$Telephone);
		$req->bindParam('ParamFax',$Fax);
		$req->bindParam('ParamResidence',$residence);
		$reponse = $req->execute();
		return $reponse;
	}

	function Residence_Create($Nom, $Adresse, $Ville, $CP, $Telephone, $Fax, $logo)
	{
		$req = $this->monPdo->prepare("INSERT INTO residence_administrative (NomResidence, AdresseResidence , VilleResidence, CPResidence, TelephoneResidence, FaxResidence, LogoResidence) values (:ParamNom, :ParamAdresse, :ParamVille, :ParamCP, :ParamTelephone, :ParamFax, :ParamLogo)");
		$req->bindParam('ParamNom',$Nom);
		$req->bindParam('ParamAdresse',$Adresse);
		$req->bindParam('ParamVille',$Ville);
		$req->bindParam('ParamCP',$CP);
		$req->bindParam('ParamTelephone',$Telephone);
		$req->bindParam('ParamFax',$Fax);
		$req->bindParam('ParamLogo',$logo);
		$reponse = $req->execute();
		return $reponse;
	}

	function Droit_Create($Nom, $vehicule, $proprietaire, $conducteur, $service, $typecontrole, $operation, $utilisateur, $reservation)
	{
		$req = $this->monPdo->prepare("INSERT INTO droit(NomDroit, DroitVehicule, DroitProprietaire, DroitConducteur, DroitService, DroitTypeControle, DroitOperation, DroitUtilisateur, ReserverVehicule) values (:ParamNom, :ParamVehicule, :ParamProprietaire, :ParamConducteur,:ParamService, :ParamType, :ParamOperation, :ParamUtilisateur, :ParamReservation)");
		$req->bindParam('ParamNom',$Nom);
		$req->bindParam('ParamVehicule',$vehicule);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamService',$service);
		$req->bindParam('ParamType',$typecontrole);
		$req->bindParam('ParamOperation',$operation);
		$req->bindParam('ParamUtilisateur',$utilisateur);
		$req->bindParam('ParamReservation',$reservation);
		$reponse = $req->execute();
		return $reponse;
	}

	function Entretien_Create($Nom)
	{
		$req = $this->monPdo->prepare("INSERT INTO entretien(TypeEntretien) values (:ParamNom)");
		$req->bindParam('ParamNom',$Nom);
		$reponse = $req->execute();
		return $reponse;
	}

	function Controle_Technique_Create($Nom)
	{
		$req = $this->monPdo->prepare("INSERT INTO controle_technique(TypeControle) values (:ParamNom)");
		$req->bindParam('ParamNom',$Nom);
		$reponse = $req->execute();
		return $reponse;
	}

	function Element_Create($libelle)
	{
		$req = $this->monPdo->prepare("INSERT INTO element(LibelleElement) values (:ParamLibelle)");
		$req->bindParam('ParamLibelle',$libelle);
		$reponse = $req->execute();
		return $reponse;
	}

	function Service_Create($Nom, $proprietaire)
	{
		$req = $this->monPdo->prepare("INSERT INTO service(NomService, NoProp) values (:ParamNom, :ParamProp)");
		$req->bindParam('ParamNom',$Nom);
		$req->bindParam('ParamProp',$proprietaire);
		$reponse = $req->execute();
		return $reponse;
	}

	function Vehicule_Create($immatriculation, $date1Immat, $constructeur, $modele, $couleur, $carburant, $type, $nbPlace, $nbPorte, $rapport, $puissance, $proprietaire, $kmVoiture, $couleurAff, $disponibilite, $lieu, $prix, $location)
	{
		$req = $this->monPdo->prepare("INSERT INTO `vehicule` (`ImmatriculationVehicule`, `DatePremiereImmatriculationVehicule`, `ConstructeurVehicule`, `ModeleVehicule`, `CouleurVehicule`, `TypeCarburantVehicule`, `TypeVehicule`, `NbPlaceVehicule`, `NbPorteVehicule`, `TypeRapportVehicule`, `PuissanceVehicule`, `NoProprietaire`, KilometrageVehicule, CouleurAffichageVehicule, LibreServiceVehicule, LieuVehicule, PrixAchatVehicule, LocationVehicule) VALUES (:ParamImmat, :ParamDate, :ParamConstruct, :ParamModele, :ParamCouleur, :ParamCarburant, :ParamType, :ParamNbPlace, :ParamNbPorte, :ParamRapport, :ParamPuissance, :ParamProprietaire, :ParamKmVoiture, :ParamCouleurAff, :ParamDisponibilite, :ParamLieu, :ParamPrix, :ParamLocation);");
		$req->bindParam('ParamImmat',$immatriculation);
		$req->bindParam('ParamDate',$date1Immat);
		$req->bindParam('ParamConstruct',$constructeur);
		$req->bindParam('ParamModele',$modele);
		$req->bindParam('ParamCouleur',$couleur);
		$req->bindParam('ParamCarburant',$carburant);
		$req->bindParam('ParamType',$type);
		$req->bindParam('ParamNbPlace',$nbPlace);
		$req->bindParam('ParamNbPorte',$nbPorte);
		$req->bindParam('ParamRapport',$rapport);
		$req->bindParam('ParamPuissance',$puissance);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$req->bindParam('ParamKmVoiture',$kmVoiture);
		$req->bindParam('ParamCouleurAff',$couleurAff);
		$req->bindParam('ParamDisponibilite',$disponibilite);
		$req->bindParam('ParamLieu',$lieu);
		$req->bindParam('ParamPrix',$prix);
		$req->bindParam('ParamLocation',$location);
		$reponse = $req->execute();
		return $reponse;
	}

	function Passer_Entretien_Create($id, $date, $km, $observations, $document, $entretien, $element, $montant)
	{
		$req = $this->monPdo->prepare("INSERT INTO `passer_entretien` (`ImmatriculationVehicule`, `Kilometrage`, `Observations`, `DatePassageEntretien`, `Document`, `NoEntretien`, `NoElement`, MontantEntretien) VALUES (:ParamId, :ParamKm, :ParamObs, :ParamDate, :ParamDoc, :ParamEntretien, :ParamElement, :ParamMontant);");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamObs',$observations);
		$req->bindParam('ParamDoc',$document);
		$req->bindParam('ParamEntretien',$entretien);
		$req->bindParam('ParamElement',$element);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Passer_Entretien_Modifier($date, $km, $observations, $document, $entretien, $element, $idEntretien, $montant)
	{
		$req = $this->monPdo->prepare("UPDATE `passer_entretien` SET `Kilometrage` = :ParamKm, `Observations` = :ParamObs, `DatePassageEntretien` = :ParamDate, `Document` = :ParamDoc, `NoEntretien` = :ParamEntretien, `NoElement` = :ParamElement, `MontantEntretien` = :ParamMontant WHERE `passer_entretien`.idEntretien = :ParamIdEntretien");
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamObs',$observations);
		$req->bindParam('ParamDoc',$document);
		$req->bindParam('ParamEntretien',$entretien);
		$req->bindParam('ParamElement',$element);
		$req->bindParam('ParamIdEntretien',$idEntretien);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;	
	}

	function passer_entretien_info_parID($id)
	{
		$req = $this->monPdo->prepare("SELECT * FROM `passer_entretien` WHERE idEntretien = :ParamId ORDER BY `idEntretien` DESC");
		$req->bindParam('ParamId',$id);
		$req->execute();
		$uneLigne = $req->fetch(PDO::FETCH_ASSOC);
		return $uneLigne;

	}

	function Passer_Controle_Technique_Create($id, $date, $km, $document, $controle, $ok, $montant)
	{
		$req = $this->monPdo->prepare("INSERT INTO `passer_controle_technique` (`ImmatriculationVehicule`, `KilometrageControle`, `DatePassageControle`, `DocumentControle`, `NoControle`, OkControle, MontantControle) VALUES (:ParamId, :ParamKm, :ParamDate, :ParamDoc, :ParamControle, :ParamOk, :ParamMontant);");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamDoc',$document);
		$req->bindParam('ParamControle',$controle);
		$req->bindParam('ParamOk',$ok);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Gerer_Create($id, $immat)
	{
		$req = $this->monPdo->prepare("INSERT INTO `gerer` (`ImmatriculationVehicule`, `NoUtilisateur`, Gere) VALUES (:ParamImmat, :ParamId, 1);");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamImmat',$immat);
		$reponse = $req->execute();
		return $reponse;
	}

	
	function Occurence_Element_Create($immat, $element, $km, $mois)
	{
		$req = $this->monPdo->prepare("INSERT INTO `occurence_element` (`NoElement`, `ImmatriculationVehicule`, OccurenceKmElement, OccurenceMoisElement) VALUES (:ParamElement,:ParamImmat,:ParamKm, :ParamMois);");
		$req->bindParam('ParamElement',$element);
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamMois',$mois);
		$reponse = $req->execute();
		return $reponse;
	}

	function VerifCreationVehicule($immat, $element, $km, $mois )
	{
		
	}

	function Gerer_Delete($id, $immat)
	{
		$req = $this->monPdo->prepare("DELETE gerer.* from gerer where ImmatriculationVehicule=:ParamImmat AND NoUtilisateur = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamImmat',$immat);
		$reponse = $req->execute();
		return $reponse;
	}

	function Passer_Entretien_Delete($id)
	{
		$req = $this->monPdo->prepare("DELETE passer_entretien.* from passer_entretien where idEntretien=:ParamId");
		$req->bindParam('ParamId',$id);
		$reponse = $req->execute();
		return $reponse;
	}

	function Reserver_Delete($immat, $conducteur, $DateHeureReservation)
	{
		$req = $this->monPdo->prepare("DELETE reserver.* from reserver where ImmatriculationVehicule=:ParamImmat AND NoConducteur = :ParamId AND DateHeureReservation = :ParamDateHeure");
		$req->bindParam('ParamId',$conducteur);
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamDateHeure',$DateHeureReservation);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utilise_Delete($idDeplacement)
	{
		$req = $this->monPdo->prepare("DELETE utilise.* from utilise where idDeplacement=:ParamId");
		$req->bindParam('ParamId',$idDeplacement);
	
		$reponse = $req->execute();
		return $reponse;
	}

	function Profil_AfficherModeration($pseudo)
	{
		$req = $this->monPdo->prepare('SELECT utilisateur.* FROM utilisateur where PseudoUtilisateur=:ParamPseudo');
		$req->bindParam('ParamPseudo',$pseudo);
		$req->execute();
        $tableauReponse = $req->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
	}

	function User_ReiniMdp($pseudo, $mdp)
	{
		$req = $this->monPdo->prepare(" UPDATE utilisateur SET MotDePasseUtilisateur = :ParamMdp WHERE PseudoUtilisateur = :ParamPseudo");
		$req->bindParam('ParamPseudo',$pseudo);
		$req->bindParam('ParamMdp',$mdp);
		$reponse = $req->execute();
		return $reponse;
	}

	function User_ReiniMdp_parId($id, $mdp)
	{
		$req = $this->monPdo->prepare(" UPDATE utilisateur SET MotDePasseUtilisateur = :ParamMdp WHERE NoUtilisateur = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamMdp',$mdp);
		$reponse = $req->execute();
		return $reponse;
	}

	function User_Infos_parPseudo($pseudo)
	{
		$requetePreparée = $this->monPdo->prepare('SELECT utilisateur.* FROM utilisateur where PseudoUtilisateur=:ParamPseudo');
		$requetePreparée->bindParam('ParamPseudo',$pseudo);
		$reponse = $requetePreparée->execute();
		$uneLigne = $requetePreparée->fetch(PDO::FETCH_ASSOC);
		return $uneLigne;
	}

	function User_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT utilisateur.*, droit.*, proprietaire.* FROM utilisateur, droit, proprietaire WHERE utilisateur.NoProprietaire = proprietaire.NoProprietaire AND utilisateur.NoDroit = droit.NoDroit AND NoUtilisateur = :ParamId ORDER BY NomProprietaire ASC, PseudoUtilisateur asc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Element_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT element.* FROM element WHERE NoElement = :ParamId ORDER BY LibelleElement ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Carte_Essence_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT carte_essence.*, vehicule.* FROM carte_essence, vehicule WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND NoCarte = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Carte_Essence_Infos_parNom($id)
	{
		$reponse = $this->monPdo->prepare('SELECT carte_essence.* FROM carte_essence WHERE NomCarte = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Carte_Essence_Infos_parImmat($id)
	{
		$reponse = $this->monPdo->prepare('SELECT carte_essence.*, utiliser_carte_essence.* FROM carte_essence, utiliser_carte_essence WHERE utiliser_carte_essence.NoCarte = carte_essence.NoCarte and ImmatriculationVehicule = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utiliser_Carte_Essence_Infos_parIdEtCondEtDate($id, $date, $cond)
	{
		$reponse = $this->monPdo->prepare('SELECT utiliser_carte_essence.* FROM utiliser_carte_essence WHERE NoCarte = :ParamId AND NoConducteur = :ParamConducteur AND DateUtilisation = :ParamDate');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamDate',$date);
		$reponse->bindParam('ParamConducteur',$cond);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utiliser_Telepeage_Infos_parIdEtCondEtDate($id, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT utiliser_telepeage.* FROM utiliser_telepeage WHERE NoTelepeage = :ParamId AND DateTelepeage = :ParamDate');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT telepeage.*, vehicule.* FROM telepeage, vehicule WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND NoTelepeage = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Infos_parImmat($id)
	{
		$reponse = $this->monPdo->prepare('SELECT telepeage.*, utiliser_telepeage.*, SUM(Montant) as Montant FROM telepeage, utiliser_telepeage WHERE utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage and ImmatriculationVehicule = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Telepeage_Infos_parNom($id)
	{
		$reponse = $this->monPdo->prepare('SELECT telepeage.* FROM telepeage WHERE NoMTelepeage = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Proprietaire_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT proprietaire.*, residence_administrative.* FROM proprietaire, residence_administrative WHERE NoProprietaire = :ParamId AND residence_administrative.NoResidence = proprietaire.NoResidence ORDER BY NomProprietaire ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Residence_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT residence_administrative.* FROM residence_administrative WHERE NoResidence = :ParamId ORDER BY NomResidence ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Service_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT service.* FROM service WHERE NoService = :ParamId ORDER BY NomService ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilisation_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT utilise.* FROM utilise WHERE ImmatriculationVehicule = :ParamId ORDER BY DateDebutUtilisation ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Utilise_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT utilise.*, conducteur.* FROM utilise, conducteur WHERE utilise.NoConducteur = conducteur.NoConducteur and  idDeplacement = :ParamId  ORDER BY DateDebutUtilisation ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Passer_Controle_Technique_Infos_parId($id, $controle, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT controle_technique.*, passer_controle_technique.* FROM controle_technique, passer_controle_technique WHERE controle_technique.NoControle = passer_controle_technique.NoControle and  ImmatriculationVehicule = :ParamId and controle_technique.NoControle = :ParamControle and DatePassageControle = :ParamDate');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamControle',$controle);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Entretien_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT entretien.* FROM entretien WHERE NoEntretien = :ParamId ORDER BY TypeEntretien ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Occurence_Element_Infos_parId($id, $immat)
	{
		$reponse = $this->monPdo->prepare('SELECT occurence_element.*, element.* FROM occurence_element, element WHERE element.NoElement = occurence_element.NoElement AND element.NoElement = :ParamId AND ImmatriculationVehicule = :ParamImmat');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Controle_Technique_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT controle_technique.* FROM controle_technique WHERE NoControle = :ParamId ORDER BY TypeControle ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Conducteur_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT conducteur.*, service.*, utilisateur.*, proprietaire.* FROM conducteur, service, utilisateur, proprietaire WHERE conducteur.NoService = service.NoService AND conducteur.NoUtilisateur = utilisateur.NoUtilisateur AND NoConducteur = :ParamId ORDER BY NomConducteur ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Gerer_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT gerer.*, vehicule.*, utilisateur.* FROM gerer, vehicule, utilisateur WHERE gerer.NoUtilisateur = utilisateur.NoUtilisateur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND NoUtilisateur = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Gerer_Infos_parIdEtImmat($id, $immat)
	{
		$reponse = $this->monPdo->prepare('SELECT gerer.*, vehicule.*, utilisateur.* FROM gerer, vehicule, utilisateur WHERE gerer.NoUtilisateur = utilisateur.NoUtilisateur AND vehicule.ImmatriculationVehicule = gerer.ImmatriculationVehicule AND gerer.NoUtilisateur = :ParamId AND gerer.ImmatriculationVehicule = :ParamImmat');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamImmat',$immat);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Infos_parId($id)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.*, proprietaire.* FROM vehicule, proprietaire WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND ImmatriculationVehicule = :ParamId ORDER BY ImmatriculationVehicule ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Vehicule_Occurence_Entretien_Infos_parIdEtNoElement($id, $element)
	{
		$reponse = $this->monPdo->prepare('SELECT vehicule.*, occurence_element.* FROM vehicule, occurence_element WHERE vehicule.ImmatriculationVehicule = occurence_element.ImmatriculationVehicule AND occurence_element.ImmatriculationVehicule = :ParamId AND NoElement = :ParamElement ORDER BY occurence_element.ImmatriculationVehicule ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamElement',$element);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	/*function Vehicule_Infos_Entretien_parId()
	{
		$reponse = $this->monPdo->query('SELECT vehicule.*, proprietaire.*, occurence_element.*, element.*, gerer.*, passer_entretien.*, entretien.* FROM vehicule, proprietaire, occurence_element, element,  gerer, passer_entretien, entretien WHERE vehicule.NoProprietaire = proprietaire.NoProprietaire AND vehicule.ImmatriculationVehicule = occurence_element.ImmatriculationVehicule AND occurence_element.NoElement = element.NoElement AND gerer.ImmatriculationVehicule = vehicule.ImmatriculationVehicule AND passer_entretien.ImmatriculationVehicule = vehicule.ImmatriculationVehicule ORDER BY NomProprietaire ASC, vehicule.ImmatriculationVehicule asc');
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}*/

	function Controle_Technique_Liste_parImmatriculation($id)
	{
		$reponse = $this->monPdo->prepare('SELECT passer_controle_technique.*, controle_technique.* FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND ImmatriculationVehicule = :ParamId ORDER BY DatePassageControle desc, controle_technique.NoControle asc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Assurance_Liste_parImmatriculation($id)
	{
		$reponse = $this->monPdo->prepare('SELECT assurance.* FROM assurance WHERE  ImmatriculationVehicule = :ParamId ORDER BY DateAssurance ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Kilometrage_Liste_parImmatriculation($id)
	{
		$reponse = $this->monPdo->prepare('SELECT kilometrage.* FROM kilometrage WHERE  ImmatriculationVehicule = :ParamId ORDER BY DateKilometrage DESC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Kilometrage_Liste_parImmatriculationASC($id)
	{
		$reponse = $this->monPdo->prepare('SELECT kilometrage.* FROM kilometrage WHERE  ImmatriculationVehicule = :ParamId ORDER BY DateKilometrage ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Kilometrage_Liste()
	{
		$reponse = $this->monPdo->prepare('SELECT ImmatriculationVehicule, KilometrageReleve  FROM kilometrage AND DateKilometrage ORDER BY ImmatriculationVehicule DESC');
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}
	
	function Assurance_Liste_parDateEtImmat($id, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT assurance.* FROM assurance WHERE  ImmatriculationVehicule = :ParamId AND   DateAssurance = :ParamDate ORDER BY DateAssurance ASC');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Kilometrage_Liste_parDateEtImmat($id, $date)
	{
		$reponse = $this->monPdo->prepare('SELECT kilometrage.* FROM kilometrage WHERE  ImmatriculationVehicule = :ParamId AND   DateKilometrage = :ParamDate ORDER BY DateKilometrage DESC');
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamDate',$date);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Entretien_Liste_parImmatriculation($id)
	{
		$reponse = $this->monPdo->prepare('SELECT passer_entretien.*, entretien.*, element.*, occurence_element.* FROM passer_entretien, entretien, element, occurence_element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement and passer_entretien.ImmatriculationVehicule = occurence_element.ImmatriculationVehicule AND occurence_element.ImmatriculationVehicule = :ParamId ORDER BY DatePassageEntretien desc, entretien.NoEntretien asc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Cout_Liste_parImmatriculation_Entre2Dates($id, $date1, $date2)
	{
		$reponse = $this->monPdo->prepare("
			SELECT DISTINCT passer_entretien.DatePassageEntretien AS DatePassage, entretien.TypeEntretien AS Type, '' AS Informations, passer_entretien.Kilometrage AS Kilometrage, passer_entretien.Document AS Document, passer_entretien.MontantEntretien AS Montant FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND ImmatriculationVehicule = :ParamId AND DatePassageEntretien BETWEEN :ParamDate1 AND :ParamDate2
			UNION
			SELECT DISTINCT utiliser_carte_essence.DateUtilisation AS DatePassage, 'Carburant' AS Type, CONCAT('Carte n°', carte_essence.NoCarte) AS Informations, KilometrageCarte as Kilometrage, '' as Document, SUM(Montant) AS Montant FROM carte_essence, vehicule, utiliser_carte_essence WHERE vehicule.ImmatriculationVehicule = carte_essence.ImmatriculationVehicule AND utiliser_carte_essence.NoCarte = carte_essence.NoCarte AND vehicule.ImmatriculationVehicule = :ParamId GROUP BY carte_essence.NoCarte AND DateUtilisation BETWEEN :ParamDate1 AND :ParamDate2
			UNION
			SELECT DISTINCT utiliser_telepeage.DateTelepeage AS DatePassage, 'Péage' AS Type, CONCAT('Badge n°', telepeage.NoTelepeage) AS Informations, '' as Kilometrage, '' as Document, SUM(Montant) AS Montant  FROM telepeage, vehicule, utiliser_telepeage WHERE vehicule.ImmatriculationVehicule = telepeage.ImmatriculationVehicule AND utiliser_telepeage.NoTelepeage = telepeage.NoTelepeage AND vehicule.ImmatriculationVehicule = :ParamId GROUP BY telepeage.NoTelepeage AND DateTelepeage BETWEEN :ParamDate1 AND :ParamDate2
			UNION
			SELECT passer_controle_technique.DatePassageControle AS DatePassage, controle_technique.TypeControle AS Type, passer_controle_technique.OkControle AS Informations, passer_controle_technique.KilometrageControle AS Kilometrage, passer_controle_technique.DocumentControle AS Document, passer_controle_technique.MontantControle AS Montant FROM passer_controle_technique, controle_technique WHERE passer_controle_technique.NoControle = controle_technique.NoControle AND ImmatriculationVehicule = :ParamId AND DatePassageControle BETWEEN :ParamDate1 AND :ParamDate2
			UNION
			SELECT DateKilometrage AS DatePassage, 'Relevé de Kilometrage' AS Type, '' AS Informations, KilometrageReleve AS Kilometrage, '' AS Document, '' AS Montant FROM kilometrage WHERE ImmatriculationVehicule = :ParamId AND DateKilometrage BETWEEN :ParamDate1 AND :ParamDate2
			UNION
			SELECT DateAssurance AS DatePassage, 'Assurance' AS Type, NomAssurance AS Informations, '' as Kilometrage, '' as Document, MontantAssurance as Montant FROM assurance WHERE  ImmatriculationVehicule = :ParamId AND DateAssurance BETWEEN :ParamDate1 AND :ParamDate2

			 ORDER BY DatePassage asc");
		$reponse->bindParam('ParamId',$id);
		$reponse->bindParam('ParamDate1',$date1);
		$reponse->bindParam('ParamDate2',$date2);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function Entretien_Liste_parIdEntretien($id)
	{
		$reponse = $this->monPdo->prepare('SELECT passer_entretien.*, entretien.*, element.* FROM passer_entretien, entretien, element WHERE passer_entretien.NoEntretien = entretien.NoEntretien AND passer_entretien.NoElement = element.NoElement AND idEntretien = :ParamId');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetch(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}
	
	function Occurence_Element_Liste_parImmatriculation($id)
	{
		$reponse = $this->monPdo->prepare('SELECT occurence_element.*, element.* FROM occurence_element, element WHERE  occurence_element.NoElement = element.NoElement AND ImmatriculationVehicule = :ParamId ORDER BY element.NoElement asc');
		$reponse->bindParam('ParamId',$id);
		$reponse->execute();
		$tableauReponse = $reponse->fetchAll(PDO::FETCH_ASSOC);
		return $tableauReponse;
	}

	function User_Modif($id, $pseudo, $droit, $actif, $proprietaire)
	{
		$req = $this->monPdo->prepare(" UPDATE utilisateur
										SET NoDroit = :ParamDroit, 
											PseudoUtilisateur = :ParamPseudo, 
											ActifUtilisateur = :ParamActif,
											NoProprietaire = :ParamProprietaire
										WHERE NoUtilisateur = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamPseudo',$pseudo);
		$req->bindParam('ParamDroit',$droit);
		$req->bindParam('ParamActif',$actif);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$reponse = $req->execute();
		return $reponse;
	}

	function Droit_Modif($id, $Nom, $vehicule, $proprietaire, $conducteur, $service, $typecontrole, $operation, $utilisateur, $reservation)
	{
		$req = $this->monPdo->prepare(" UPDATE droit
										SET NomDroit = :ParamNom, 
											DroitVehicule = :ParamVehicule, 
											DroitProprietaire = :ParamProprietaire, 
											DroitConducteur = :ParamConducteur, 
											DroitService = :ParamService, 
											DroitTypeControle = :ParamType, 
											DroitOperation = :ParamOperation,
											DroitUtilisateur = :ParamUtilisateur,
											ReserverVehicule = :ParamReservation
										WHERE NoDroit = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamNom',$Nom);
		$req->bindParam('ParamVehicule',$vehicule);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamService',$service);
		$req->bindParam('ParamType',$typecontrole);
		$req->bindParam('ParamOperation',$operation);
		$req->bindParam('ParamUtilisateur',$utilisateur);
		$req->bindParam('ParamReservation',$reservation);
		$reponse = $req->execute();
		return $reponse;
	}

	function Occurence_Element_Modif($id, $element, $km, $mois)
	{
		$req = $this->monPdo->prepare(" UPDATE occurence_element
										SET OccurenceKmElement = :ParamKm, 
											OccurenceMoisElement = :ParamMois
										WHERE NoElement = :ParamElement
										AND ImmatriculationVehicule = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamElement',$element);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamMois',$mois);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utiliser_Carte_Essence_Modif($nobadge, $conducteur, $date, $km, $litre, $prix, $station)
	{
		$req = $this->monPdo->prepare(" UPDATE utiliser_carte_essence
										SET NoCarte = :ParamId, 
											NoConducteur = :ParamConducteur,
											DateUtilisation = :ParamDate,
											KilometrageCarte = :ParamKm,
											Litre = :ParamLitre,
											Montant = :ParamPrix,
											Station = :ParamStation
										WHERE NoConducteur = :ParamConducteur
										AND NoCarte = :ParamId
										AND DateUtilisation = :ParamDate
										AND KilometrageCarte = :ParamKm");
		$req->bindParam('ParamId',$nobadge);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamLitre',$litre);
		$req->bindParam('ParamPrix',$prix);
		$req->bindParam('ParamStation',$station);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utiliser_Telepeage_Modif($nobadge, $date, $montant)
	{
		$req = $this->monPdo->prepare(" UPDATE utiliser_telepeage
										SET NoTelepeage = :ParamId, 
											DateTelepeage = :ParamDate,
											Montant = :ParamMontant
										WHERE NoTelepeage = :ParamId
										AND DateTelepeage = :ParamDate");
		$req->bindParam('ParamId',$nobadge);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Carte_Essence_Modif($id, $nom, $vehicule, $fournisseur, $dateRenouvellement)
	{
		$req = $this->monPdo->prepare(" UPDATE carte_essence
										SET NomCarte = :ParamNom, 
											ImmatriculationVehicule = :ParamImmat,
											FournisseurCarte = :ParamFournisseur, 
											DateRenouvellementCarte = :ParamDateRenouvellement
										WHERE NoCarte = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamImmat',$vehicule);
		$req->bindParam('ParamFournisseur',$fournisseur);
		$req->bindParam('ParamDateRenouvellement',$dateRenouvellement);
		$reponse = $req->execute();
		return $reponse;
	}

	function Telepeage_Modif($id, $nom, $vehicule, $fournisseur, $abonnement)
	{
		$req = $this->monPdo->prepare(" UPDATE telepeage
										SET NomTelepeage = :ParamNom, 
											ImmatriculationVehicule = :ParamImmat,
											 FournisseurTelepeage = :ParamFournisseur, 
											AbonnementTelepeage = :ParamAbonnement
										WHERE NoTelepeage = :ParamId");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamImmat',$vehicule);
		$req->bindParam('ParamFournisseur',$fournisseur);
		$req->bindParam('ParamAbonnement',$abonnement);
		$reponse = $req->execute();
		return $reponse;
	}

	function Proprietaire_Modif($id, $nom, $adresse, $ville, $cp, $tel, $fax, $residence)
	{
		$req = $this->monPdo->prepare(" UPDATE proprietaire
										SET NomProprietaire = :ParamNom, 
											AdresseProprietaire = :ParamAdresse, 
											VilleProprietaire = :ParamVille,
											CPProprietaire = :ParamCP,
											TelephoneProprietaire = :ParamTelephone, 
											FaxProprietaire = :ParamFax,
											NoResidence = :ParamResidence
										WHERE NoProprietaire = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamAdresse',$adresse);
		$req->bindParam('ParamVille',$ville);
		$req->bindParam('ParamCP',$cp);
		$req->bindParam('ParamTelephone',$tel);
		$req->bindParam('ParamFax',$fax);
		$req->bindParam('ParamResidence',$residence);
		$reponse = $req->execute();
		return $reponse;
	}
	function Residence_Modif($id, $nom, $adresse, $ville, $cp, $tel, $fax, $logo)
	{
		$req = $this->monPdo->prepare(" UPDATE residence_administrative
										SET NomResidence = :ParamNom, 
											AdresseResidence = :ParamAdresse, 
											VilleResidence = :ParamVille,
											CPResidence = :ParamCP,
											TelephoneResidence = :ParamTelephone, 
											FaxResidence = :ParamFax,
											LogoResidence = :ParamLogo
										WHERE NoResidence = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamAdresse',$adresse);
		$req->bindParam('ParamVille',$ville);
		$req->bindParam('ParamCP',$cp);
		$req->bindParam('ParamTelephone',$tel);
		$req->bindParam('ParamFax',$fax);
		$req->bindParam('ParamLogo',$logo);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utilise_Modif($immat, $datedebutAvant, $datedebut, $datefin, $destination, $conducteur, $nbpassagers)
	{
		$req = $this->monPdo->prepare("UPDATE `utilise` SET `NoConducteur` = :ParamConducteur, `DateDebutUtilisation` = :ParamDateDebut, `DateFinUtilisation` = :ParamDateFin, `Destination` = :ParamDestination, `NbPersonnes` = :ParamNbPassagers WHERE `utilise`.`ImmatriculationVehicule` = :ParamImmat AND `utilise`.`DateDebutUtilisation` = :ParamDateDebutAvant;");
		$req->bindParam('ParamImmat',$immat);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamDateDebutAvant',$datedebutAvant);
		$req->bindParam('ParamDateDebut',$datedebut);
		$req->bindParam('ParamDateFin',$datefin);
		$req->bindParam('ParamDestination',$destination);
		$req->bindParam('ParamNbPassagers',$nbpassagers);
		$reponse = $req->execute();
		return $reponse;
	}

	function Reserver_Modif($id, $datedebut, $datefin, $destination, $conducteur, $dateEtHeure, $NouvelleImmat, $passagers)
	{
		$req = $this->monPdo->prepare("UPDATE `reserver` SET `NoConducteur` = :ParamConducteur, `DateDebutUtilisation` = :ParamDateDebut, `DateFinUtilisation` = :ParamDateFin, `Destination` = :ParamDestination, ImmatriculationVehicule = :ParamNewImmat, `NbPersonnes` = :ParamNbPassagers WHERE `reserver`.`ImmatriculationVehicule` = :ParamImmat AND `reserver`.`DateHeureReservation` = :ParamDateReservation;");
		$req->bindParam('ParamImmat',$id);
		$req->bindParam('ParamNewImmat',$NouvelleImmat);
		$req->bindParam('ParamConducteur',$conducteur);
		$req->bindParam('ParamDateReservation',$dateEtHeure);
		$req->bindParam('ParamDateDebut',$datedebut);
		$req->bindParam('ParamDateFin',$datefin);
		$req->bindParam('ParamDestination',$destination);
		$req->bindParam('ParamNbPassagers',$passagers);
		$reponse = $req->execute();
		return $reponse;
	}

	function Utilise_ActiviteTelepeage($id, $statutT)
	{
		$req = $this->monPdo->prepare("UPDATE `utilise` SET `StatutT` = :ParamT WHERE `utilise`.`idDeplacement` = :ParamId ");
		$req->bindParam('ParamId',$id);
		$req->bindParam('ParamT',$statutT);
		$reponse = $req->execute();
		return $reponse;
	}

	function Passer_Controle_Technique_Modif($id, $date, $km, $document, $controle, $ok, $montant)
	{
		$req = $this->monPdo->prepare("UPDATE `passer_controle_technique` 
										SET `DatePassageControle` = :ParamDate,
										`KilometrageControle` = :ParamKm,
										`DocumentControle` = :ParamDocument,
										`NoControle` = :ParamControle,
										`OkControle` = :ParamOk,
										`MontantControle` = :ParamMontant
										WHERE `passer_controle_technique`.`ImmatriculationVehicule` = :ParamImmat
										AND `passer_controle_technique`.`NoControle` = :ParamControle
										AND DatePassageControle = :ParamDate;");
		$req->bindParam('ParamImmat',$id);
		$req->bindParam('ParamDate',$date);
		$req->bindParam('ParamKm',$km);
		$req->bindParam('ParamDocument',$document);
		$req->bindParam('ParamControle',$controle);
		$req->bindParam('ParamOk',$ok);
		$req->bindParam('ParamMontant',$montant);
		$reponse = $req->execute();
		return $reponse;
	}

	function Vehicule_Modif($id, $immatriculation, $date1Immat, $constructeur, $modele, $couleur, $carburant, $type, $nbPlace, $nbPorte, $rapport, $puissance, $kilometrage, $proprietaire, $colorAff, $lieu, $prix, $location, $actif)
	{
		$req = $this->monPdo->prepare("UPDATE `vehicule` 
										SET `ImmatriculationVehicule` = :ParamImmat, 
										 `DatePremiereImmatriculationVehicule` = :ParamDate, 
										 `ConstructeurVehicule` = :ParamConstruct, 
										 `ModeleVehicule` = :ParamModele, 
										 `CouleurVehicule` = :ParamCouleur, 
										 `TypeCarburantVehicule` = :ParamCarburant, 
										 `TypeVehicule` = :ParamType, 
										 `NbPlaceVehicule` = :ParamNbPlace, 
										 `NbPorteVehicule` = :ParamNbPorte, 
										 `TypeRapportVehicule` = :ParamRapport, 
										 `PuissanceVehicule` = :ParamPuissance, 
										 `NoProprietaire` =  :ParamProprietaire,
										  KilometrageVehicule = :ParamKm,
										  CouleurAffichageVehicule = :ParamCouleurAff,
										  LieuVehicule = :ParamLieu,
										  PrixAchatVehicule = :ParamPrix,
										  LocationVehicule = :ParamLocation,
										  ActifVehicule = :ParamActif
										 WHERE ImmatriculationVehicule = :ParamImmat" );
		$req->bindParam('ParamImmat',$immatriculation);
		$req->bindParam('ParamDate',$date1Immat);
		$req->bindParam('ParamConstruct',$constructeur);
		$req->bindParam('ParamModele',$modele);
		$req->bindParam('ParamCouleur',$couleur);
		$req->bindParam('ParamCarburant',$carburant);
		$req->bindParam('ParamType',$type);
		$req->bindParam('ParamNbPlace',$nbPlace);
		$req->bindParam('ParamNbPorte',$nbPorte);
		$req->bindParam('ParamRapport',$rapport);
		$req->bindParam('ParamPuissance',$puissance);
		$req->bindParam('ParamKm',$kilometrage);
		$req->bindParam('ParamProprietaire',$proprietaire);
		$req->bindParam('ParamCouleurAff',$colorAff);
		$req->bindParam('ParamLieu',$lieu);
		$req->bindParam('ParamPrix',$prix);
		$req->bindParam('ParamLocation',$location);
		$req->bindParam('ParamActif',$actif);
		$reponse = $req->execute();
		return $reponse;
	}

	function Vehicule_Modif_LibreService($immatriculation, $libreservice)
	{
		$req = $this->monPdo->prepare("UPDATE `vehicule` 
										SET LibreServiceVehicule = :ParamLS
										 WHERE ImmatriculationVehicule = :ParamImmat" );
		$req->bindParam('ParamImmat',$immatriculation);
		$req->bindParam('ParamLS',$libreservice);
		$reponse = $req->execute();
		return $reponse;
	}


	function Conducteur_Modif($id, $nom, $prenom, $actif, $adresse, $cp, $ville, $telephone, $portable, $service, $permis, $doc, $user)
	{
		$req = $this->monPdo->prepare(" UPDATE conducteur
										SET NomConducteur = :ParamNom, 
											PrenomConducteur = :ParamPrenom, 
											ActifConducteur = :ParamActif,
											AdresseConducteur = :ParamAdresse,
											CPConducteur = :ParamCP, 
											VilleConducteur = :ParamVille,
											TelephoneConducteur = :ParamTelephone, 
											PortableConducteur = :ParamPortable, 
											NoService = :ParamService,
											PermisConducteur = :ParamPermis,
											ScanPermis = :ParamDoc,
											NoUtilisateur = :ParamUser
										WHERE NoConducteur = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamPrenom',$prenom);
		$req->bindParam('ParamActif',$actif);
		$req->bindParam('ParamAdresse',$adresse);
		$req->bindParam('ParamVille',$ville);
		$req->bindParam('ParamCP',$cp);
		$req->bindParam('ParamTelephone',$telephone);
		$req->bindParam('ParamPortable',$portable);
		$req->bindParam('ParamPermis',$permis);
		$req->bindParam('ParamService',$service);
		$req->bindParam('ParamDoc',$doc);
		$req->bindParam('ParamUser',$user);
		$reponse = $req->execute();
		return $reponse;
	}

	function Service_Modif($id, $nom, $proprietaire)
	{
		$req = $this->monPdo->prepare(" UPDATE service
										SET NomService = :ParamNom,
										 	NoProp = :ParamProp
										WHERE NoService = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$req->bindParam('ParamProp',$proprietaire);
		$reponse = $req->execute();
		return $reponse;
	}

	function Entretien_Modif($id, $nom)
	{
		$req = $this->monPdo->prepare(" UPDATE entretien
										SET TypeEntretien = :ParamNom
										WHERE NoEntretien = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$reponse = $req->execute();
		return $reponse;
	}
	
	function Element_Modif($id, $libelle)
	{
		$req = $this->monPdo->prepare(" UPDATE element
										SET LibelleElement = :ParamNom
										WHERE NoElement = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$libelle);
		$reponse = $req->execute();
		return $reponse;
	}

	function Controle_Technique_Modif($id, $nom)
	{
		$req = $this->monPdo->prepare(" UPDATE controle_technique
										SET TypeControle = :ParamNom
										WHERE NoControle = :ParamNo");
		$req->bindParam('ParamNo',$id);
		$req->bindParam('ParamNom',$nom);
		$reponse = $req->execute();
		return $reponse;
	}
}
?>