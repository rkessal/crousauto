<?php

function VerifAlerte($ImmatVehicule, $dateExactOuiNon)
{
	require_once('bdd.php');
	$conectBDD = new BDD();
	$vehicule = $conectBDD->Vehicule_Liste_parImmatriculation($ImmatVehicule);
	$listeALerte = null;
	$AffInfoVehicule = 0;
	$ControleTechnique = $conectBDD->Controle_Technique_Liste_parImmatriculation($vehicule['ImmatriculationVehicule']);
	$Entretien = $conectBDD->Entretien_Liste_parImmatriculation($vehicule['ImmatriculationVehicule']);
	$Element = $conectBDD->Element_Liste($vehicule['ImmatriculationVehicule']);
	$date = date('Y-m-d');
	$date = new DateTime($date);
	$date->add(new DateInterval('P30D'));
	$dateNow = $date->format('Y-m-d');
	//var_dump($dateNow);

	if($vehicule['LocationVehicule'] == 0)
	{
		//CONTROLE TECHNIQUE
		$DateDernierControle = null;
		foreach ($ControleTechnique as $uneLigne) {
			if($uneLigne['NoControle'] != 0)
			{
				$DateDernierControle = $uneLigne['DatePassageControle'];
				$DateDernier = new DateTime($DateDernierControle);
				$annee = $DateDernier->format('Y') + 2;
				$expiration = $annee . '-' . $DateDernier->format('m') . '-' . $DateDernier->format('d');
				break;
			}
			
		}
		
		if($DateDernierControle == null)
		{
			$DateDernier = new DateTime($vehicule['DatePremiereImmatriculationVehicule']);
			$annee = $DateDernier->format('Y') + 4;
			$expiration = $annee . '-' . $DateDernier->format('m') . '-' . $DateDernier->format('d');
		}

		if(($expiration <= $dateNow and $dateExactOuiNon == 0) or ($expiration == $dateNow and $dateExactOuiNon == 1))
		{
			if($AffInfoVehicule ==0)
			{
				$listeALerte .= 'le véhicule <b>' . $vehicule["ConstructeurVehicule"] . ' ' .$vehicule["ModeleVehicule"] . '</b> (Lieu : <b>' . $vehicule["LieuVehicule"] . '</b>)  immatriculé <b>' .$vehicule["ImmatriculationVehicule"] . '</b> doit effectuer le(s) opérations(s) suivante(s) : <ul>';
				$AffInfoVehicule = 1;
			}
			$listeALerte .= "<li> Passer le Contrôle Technique expirant le " . $DateDernier->format('d') . '/' . $DateDernier->format('m') . '/' . $annee . "</li>";
		}

		//CONTROLE ATP (FRIGO)
		if($vehicule['TypeVehicule'] == "Vehicule frigorifique")
		{
			$DateDernierControle = null;
			foreach ($ControleTechnique as $uneLigne) {
				if($uneLigne['NoControle'] == 0)
				{
					$DateDernierControle = $uneLigne['DatePassageControle'];
					$DateDernier = new DateTime($DateDernierControle);
					$annee = $DateDernier->format('Y') + 3;
					$expiration = $annee . '-' . $DateDernier->format('m') . '-' . $DateDernier->format('d');
					break;
				}
				
			}
			
			if($DateDernierControle == null)
			{
				$DateDernier = new DateTime($vehicule['DatePremiereImmatriculationVehicule']);
				$annee = $DateDernier->format('Y') + 6;
				$expiration = $annee . '-' . $DateDernier->format('m') . '-' . $DateDernier->format('d');
			}

			if(($expiration <= $dateNow and $dateExactOuiNon == 0) or ($expiration == $dateNow and $dateExactOuiNon == 1))
			{
				if($AffInfoVehicule ==0)
				{
					$listeALerte .= 'le véhicule <b>' . $vehicule["ConstructeurVehicule"] . ' ' .$vehicule["ModeleVehicule"] . '</b> (Lieu : <b>' . $vehicule["LieuVehicule"] . '</b>)  immatriculé <b>' .$vehicule["ImmatriculationVehicule"] . '</b> doit effectuer le(s) opérations(s) suivante(s) : <ul>';
					$AffInfoVehicule = 1;
				}
				$listeALerte .= "<li> Passer le Contrôle ATP (appareil frigorifique) expirant le " . $DateDernier->format('d') . '/' . $DateDernier->format('m') . '/' . $annee . "</li>";
			}
		}


		//ENTRETIEN
		foreach ($Element as $listeElement) {
			$OccurenceEntretienParElement = $conectBDD->Vehicule_Occurence_Entretien_Infos_parIdEtNoElement($vehicule['ImmatriculationVehicule'], $listeElement['NoElement']);
			if($OccurenceEntretienParElement)
			{
				$vehicule['DatePremiereImmatriculationVehicule'] = new DateTime($vehicule['DatePremiereImmatriculationVehicule']);
				$vehicule['DatePremiereImmatriculationVehicule'] = $vehicule['DatePremiereImmatriculationVehicule']->format('Y-m-d');

				$listeEntretien = $conectBDD->Entretien_Liste_parImmatriculation($vehicule['ImmatriculationVehicule']);
				$dernierPassage = null;
				foreach ($listeEntretien as $unEntretien) {
					$DateDernierEntretien = $unEntretien['DatePassageEntretien'];
					$kmDernierEntretien = (int)$unEntretien['OccurenceKmElement'];
					$dernierPassage = new DateTime($DateDernierEntretien);
					$annee = $DateDernier->format('Y') + 2;
					$dernierPassage = $dernierPassage->format('Y-m-d');
					break;
				}

				if($dernierPassage == null)
				{
					$dernierPassage = new DateTime(date('Y-m-d'));
					$dernierPassage->add(new DateInterval('P30D'));
					$dernierPassage = $dernierPassage->format('Y-m-d');
					$kmDernierEntretien = (int)$OccurenceEntretienParElement['OccurenceKmElement'];
				}

				$km = (int)$OccurenceEntretienParElement['OccurenceKmElement'];

				$OccurenceJoursElement = $OccurenceEntretienParElement['OccurenceMoisElement'] * 30;
				
				$nbJours = strtotime($dernierPassage) - strtotime($dateNow);
				
				if(($vehicule['KilometrageVehicule'] - $kmDernierEntretien >= $km) or ($nbJours >= $OccurenceJoursElement and $dateExactOuiNon == 0) or ($nbJours == $OccurenceJoursElement and $dateExactOuiNon == 1))// si 1 alors =
				{
					/*var_dump($vehicule['KilometrageVehicule'] - $kmDernierEntretien);
					var_dump($km);
					var_dump($nbJours);
					var_dump($OccurenceJoursElement);*/
					if($AffInfoVehicule == 0)
					{
						$listeALerte .= 'le véhicule <b>' . $vehicule["ConstructeurVehicule"] . ' ' .$vehicule["ModeleVehicule"] . '</b> (Lieu : <b>' . $vehicule["LieuVehicule"] . '</b>) immatriculé <b>' .$vehicule["ImmatriculationVehicule"] . '</b> doit effectuer le(s) opérations(s) suivante(s) : <ul>';
						$AffInfoVehicule = 1;
					}
					$listeALerte .= '<li> Faire le ' . $listeElement['LibelleElement'] . '</li>';
					
				}
			}
		}
	}
	if($listeALerte != null)
		$listeALerte .= '</ul>';

	if($vehicule['TypeVehicule'] == "Velo")
		return null;
	else
		return $listeALerte;

}

?>