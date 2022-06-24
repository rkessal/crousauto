<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (!$liste['ReserverVehicule'] and !$liste['DroitVehicule'])
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Conditions d'utilisations</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js?ver=1.3.2'></script>
</head>

<?php
	include("menu.php");
?>
<body>
<div id="top-margin" class="top-margin">
	<div class="page-container">
	<div class="sommaire ">
	<div id="sommaire" class="sommaire-content">
<h2>SOMMAIRE :</h2>
<h3>
<ol id="top-menu">
	<li class="active"><a href="#definition">Définitions</a></li>
	<li><a href="#securite-routiere" class="link">Sécurité routière</a></li>
	<li><a href="#permis-conduire" class="link">Permis de conduire</a></li>
	<li><a href="#infractions" class="link">Infractions au Code de la route</a></li>
	<li><a href="#entretien-vehicule" class="link">L'entretien du véhicule</a></li>
	<li><a href="#controle-technique-vehicule" class="link">Contrôle technique du véhicule</a></li>
	<li><a href="#papiers" class="link">Papiers</a></li>
</ol>
</h3>
</div>
</div>
<div class="charte-container">
<h1>Conditions d'utilisations</h1><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>
<br><br>
	<h1 id="definition">Définitions</h1>
		<p><b>Objet de la Charte Conducteur</b><br>
		L’objet de la Charte Conducteur est de préciser les modalités d’utilisation du véhicule et de s’assurer que le conducteur en accepte les modalités (ci-après « la Charte »).</p>
		<p><b>Entrée en vigueur et durée</b><br>
		La Charte est accepté par chaque conducteur lors de la réservation d’un véhicule. La direction se réserve le droit de modifier les conditions et les principes de la charte à tout moment.</p>

<br><br>
	<h1 id="securite-routiere">Sécurité routière</h1>
		<p><b>Respect du Code de la route et capacité de conduite</b><br>
		Le conducteur s’engage à respecter le Code de la route et être en pleine capacité de conduite.<br>
		Il est rappelé que certains traitements médicaux et la prise de médicaments peuvent altérer la capacité de conduite du conducteur. Il est de la responsabilité du conducteur de solliciter son médecin traitant et de suivre strictement ses recommandations.<br>
		Le conducteur s’engage par la présente charte à ne pas laisser conduire la voiture par une personne qui est sous influence de l’alcool ou de stupéfiants.</p>
		<p><b>Pratiques de conduite</b><br>
		Le véhicule ne pourra être utilisé pour toutes pratiques spécifiques (compétitions, rallyes …).<br>
		En plus du respect strict du code de la route, le responsable demande au conducteur d’adopter une conduite responsable et économique pour lui-même et pour son environnement.</p>
		<p><b>Téléphone au volant</b><br>
		L’utilisation d’un téléphone portable lors de la conduite d’un véhicule, même avec l'utilisation d'un système bluetooth, augmente les risques d’accident de la circulation.<br>
		Lorsque le véhicule est équipé d’un système bluetooth, il est rappelé que, conformément aux dispositions de l’article R 412-6 du Code de la route, « le conducteur doit se tenir constamment en état et en position d’exécuter sans délai toutes les manœuvres qui lui incombent ».<br>
		L’usage du kit mains libres en conduisant est interdit.</p>
		<p><b>Kit de sécurité</b><br>
		Depuis le 1er juillet 2008 la présence dans tous les véhicules d’un triangle de pré-signalisation et d’un gilet auto-réfléchissant, est obligatoire. Le responsable a prévu la mise à disposition du Kit de sécurité. Il est de la responsabilité du conducteur de s’assurer de sa présence et de se procurer un Kit de sécurité en cas de manquement.<br>
		Il est demandé au responsable de conserver son Kit de sécurité lors du renouvellement du véhicule.</p>
		<p><b>Etat du véhicule</b><br>
		Il est de la responsabilité du conducteur de s’assurer que le véhicule qu’il utilise est en état de fonctionnement. En cas de doute, il est de sa responsabilité de trouver une autre solution de mobilité, d’informer son responsable des réparations nécessaires dans le strict respect des procédures de fonctionnement qui lui sont indiqués par le Loueur, l’Assisteur, le constructeur automobile et par son responsable.</p>
		<p><b>Non-respect des règles de sécurités</b><br>
		Le responsable se réserve le droit de suspendre la mise à disposition d’un véhicule si ses règles de conduites n’étaient pas respectées.</p>

<br><br>
	<h1 id="permis-conduire">Permis de conduire</h1>
		<p><b>Validité du permis du conduire</b><br>
		Le conducteur reconnaît être en possession d’un permis de conduire valide l’habilitant à conduire le véhicule que son responsable lui met à disposition (Art. R221-1 du code de la route).</p>
		<p><b>Information sur la perte de permis de conduire</b><br>
		Le conducteur s’engage à informer son responsable, dans les 24h, lors d’un retrait de permis de conduire ne lui permettant plus de conduire son véhicule.</p>
		<p><b>Contrôle de validité du permis de conduire</b><br>
		Le responsable pourra être amené, à tout moment, à demander au conducteur de bien vouloir lui confirmer la validité de son permis de conduire.</p>

<br><br>
	<h1 id="infractions">Infractions au Code de la route</h1>
		<p><b>Responsabilité du conducteur</b><br>
		L’article L 121-1 du Code de la route stipule que le « Le conducteur d’un véhicule est responsable pénalement des infractions commises par lui dans la conduite dudit véhicule ».<br>
		Pour les contraventions liées au stationnement des véhicules, à l’acquittement des péages, aux vitesses maximales autorisées, au respect des distances de sécurité entre les véhicules, à l’usage de voies et chaussées réservées à certaines catégories de véhicules et aux signalisations imposant l’arrêt des véhicules, les articles L 121-2 et L 121-3 du Code de la route précisent que c’est « le titulaire du certificat d’immatriculation du véhicule [qui] est redevable pécuniairement de l’amende encourue… à moins qu’il n’apporte tous éléments permettant d’établir qu’il n’est pas l’auteur véritable de l’infraction ». </p>
		<p><b>Procédure de paiement suite information de l’administration par le responsable</b><br>
		Toute contravention reçue par le responsable est associée à un véhicule dont le conducteur a la responsabilité. Conformément à la réglementation, le responsable communiquera l’identité du salarié bénéficiaire auteur de l’infraction et complétera à cet effet le formulaire dit de « requête exonération » joint à l’avis de contravention. Le conducteur ayant commis l’infraction sera donc redevable de l’amende.<br>
		Le conducteur devra également supporter tous les coûts de pénalité liés à non-paiement ou à un retard de paiement ainsi que les frais de gestion de contravention facturés au responsable.</p>
		<p><b>Procédure de paiement direct des contraventions par le conducteur</b><br>
		Toute contravention reçue par le responsable est associée à un véhicule dont le conducteur a la responsabilité. A la réception de la contravention, le responsable en informe immédiatement le conducteur par mail et lui communique les documents reçus. Le conducteur dispose alors de 48h pour payer directement à l’administration le montant de la contravention.<br>
		Il transmet confirme à son le responsable dans le même délai une confirmation de paiement. 
		Le conducteur devra également supporter tous les coûts de pénalité liés au non-paiement ou à un retard de paiement ainsi que les éventuels frais de gestion de contravention facturés au responsable.</p>

<br><br>
	<h1 id="entretien-vehicule">L'entretien du véhicule</h1>
		<p><b>Contrôles d’usage</b><br>
		Le conducteur doit régulièrement effectuer les contrôles élémentaires relatifs à l’entretien du véhicule dont il a la responsabilité à savoir :<br>
		<ul>
			<li><p>vérification tous les 2.000 à 3.000 km des niveaux d’huile,  de liquide de refroidissement, de freinage, de lave-glace,</p></li>
			<li><p>vérification de la pression des pneumatiques (à faire tous les mois selon les recommandations constructeurs).</p></li>
		</ul></p>
		<p><b>Visites périodiques et révisions</b><br>
		Le responsable du véhicule est responsable de la réalisation des visites d’entretien, conformément au rythme prévu par le constructeur, et ce, chez un agent ou concessionnaire de la marque.<br></p>
		<p><b>Pneumatiques</b><br>
		Les pneumatiques constituent un élément de sécurité important du véhicule. Le responsable est dans l'obligation de faire changer les pneumatiques selon leur degré d’usure.</p>
		<p><b>Anomalie ou alerte</b><br>
		En cas de problèmes, si un voyant lumineux s’allume ou clignote (témoin d’eau ou d’huile), il est demandé de s’arrêter impérativement et faire immédiatement appel à l’assistance.<br>
		Malgré ces consignes, si le conducteur continue de rouler avec les témoins allumés, les frais de remise en état ou de changement de moteur, n’étant pas pris en charge par le constructeur automobile, le responsable pourra être amené à faire supporter le coût total des réparations au conducteur.</p>
		<p><b>Propreté et état général du véhicule</b><br>
		L’utilisation d’un véhicule engage l’image de marque de sa société, aussi il est demandé que le conducteur soit attentif à l’état de propreté du véhicule.<br>
		De même, il est aussi demandé au conducteur de maintenir un très bon état général du véhicule au niveau de la carrosserie.</p>

<br><br>
	<h1 id="controle-technique-vehicule">Contrôle technique du véhicule</h1>				
		<p><b>Rappel de la législation</b><br>
		Le contrôle technique s'applique aux voitures particulières et aux utilitaires légers d'un poids total inférieur ou égale à 3,5 tonnes et est obligatoire depuis 1992.<br>
		Les voitures particulières de plus de 4 ans sont soumises au contrôle technique qui sera renouvelé obligatoirement tous les 2 ans. Le contrôle doit être effectué dans les 6 mois qui précèdent le 4ème anniversaire de la 1ère mise en circulation du véhicule.<br>
		Depuis le 1er janvier 1999, tous les véhicules dont le genre est différent de VP (voir rubrique J.1 sur le certificat d'immatriculation), notamment les VUL, doivent effectuer une visite technique complémentaire portant sur le contrôle des émissions polluantes entre le 11ème et le 12ème mois suivant le dernier contrôle technique obligatoire.<br>
		Attention : cela comprend également les véhicules particuliers à deux places homologués en genre fiscal VUL (voir rubrique J.1 sur le certificat d'immatriculation qui comporte la dénomination « CTTE »).</p>
		<p><b>Responsabilité</b><br>
		Le responsable se doit d’organiser le contrôle réglementaire du véhicule qui lui est affecté dans le délai légal.
		Vous trouverez ici les réponses aux questions principales liées à son utilisation.<br>
		<b>Il est important que tous les événements du véhicule soient rapportés immédiatement au responsable d'UG concerné.<br>
		Nous vous rappelons qu’il est de <u>votre responsabilité</u> de prendre soin des véhicules.</b><br>
		En utilisant le service, vous acceptez <a href="http://circulaire.legifrance.gouv.fr/pdf/2017/05/cir_42274.pdf">les règles suivantes</a>.</p>
		
<br><br>
	<h1 id="papiers">Papiers</h1>						
		<p>Vous devez être en possession des éléments suivants :</p>
		<ul>
			<li><p>Carte grise</p></li>
			<li><p>Carte verte assurance</p></li>
			<li><p>Papillon assurance sur le pare-brise</p></li>
			<li><p>Constat amiable</p></li>
			<li><p>Carnet d’entretien du véhicule</p></li>
		</ul>
		<p>Si l’une de ces pièces vous manque, signalez-le à votre responsable d'UG. <b>Par sécurité, ne laissez pas vos papiers dans le véhicule.</b></p>

		<br><button class="btn btn-primary" onclick="history.back()"><div class="glyphicon glyphicon-circle-arrow-left"></div> Retour</button>


</div>
</div>

</div>


	<script type="text/javascript">




		$(function(){
			$('ol li a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
			$('ol li a').click(function(){
				$(this).parent().addClass('active').siblings().removeClass('active')	
			})
		})
		

	  	var $menu   = $(".sommaire"), 
        $window    = $(window),
        offset     = $menu.offset(),
        topPadding = 80;






        /*if ($(".page-container").width() < 1455)
		{
			$(".sommaire").css("float", "none");
		}

		else
		{
			$(".sommaire").css("float", "right");

	    	if ($(".page-container").width() < 1300)
			{

			}

			else
			{
				
			}
		} */
	</script>
</body>

<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>