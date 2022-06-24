<?php
class ladp{

	public function __construct()
	{
    		
	}
	public function _destruct(){
	}
	

	public function get_groups($user, $password) //retourne les groupes dans lequel est l'utilisateur(array)
	{
		
            
		// Active Directory server
		$ldap_host = "192.168.70.106";
		// Active Directory DN, base path for our querying user
		$ldap_dn = "crousbesancon.lan";
	 	$ldap_base_dn = 'DC=crousbesancon , DC=lan';
		// Active Directory user for querying
		$query_user = $user."@".$ldap_dn;
		// Connect to AD
		$ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
		@ldap_bind($ldap,$query_user,$password);
		// Search AD
		/*set_error_handler(	function($errno, $errstr, $errfile, $errline) 
			{
				@throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
			});*/
 		
		@$results = ldap_search($ldap,$ldap_base_dn,"(samaccountname=".$user.")",array("displayname","memberof","primarygroupid")); //,"telephoneNumber","otherTelephone"
		@$entries = ldap_get_entries($ldap, 	$results);
		// No information found, bad user
		if($entries['count'] == 0) return false;
		// Get groups and primary group token
		@$output = $entries[0]['memberof'];
		$token = $entries[0]['primarygroupid'][0];
		$_SESSION['nom']=$entries[0]['displayname'][0];
		// Remove extraneous first entry
		array_shift($output);
		// We need to look up the primary group, get list of all groups
		$results2 = ldap_search($ldap,$ldap_base_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
		$entries2 = ldap_get_entries($ldap, $results2);
		// Remove extraneous first entry
		array_shift($entries2);
		// Loop through and find group with a matching primary group token
		foreach($entries2 as $e) {
			if($e['primarygrouptoken'][0] == $token) 
			{
				// Primary group found, add it to output array
				$output[] = $e['distinguishedname'][0];
				// Break loop
				break;
			}
		}
		$output;
		$lesgroupes=[];
		foreach ($output as $unResultat) 
		{
			$elementsResultats=explode(',', $unResultat);
			$chaine = $elementsResultats[0];
			$chaine = substr($chaine, 3);
			$lesgroupes[]=$chaine;
		}
		return $lesgroupes;
    	restore_error_handler();
	}
	

	public function newCateg($name)//ajoute la catégorie spécifiée en paramètre
	{

		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$existant=0;
		$max=count($lescategs)-1;
		 for ($i=0; $i < $max; $i=$i+2) 
		 { 
		   	if ($lescategs[$i] == $name || $lescategs[$i] == "\n".$name) 
		   	{
		   		$existant=1;
		   	}
		 }
		 
		 if($existant==0){
		 	$current = $current.$name.";;\n";
		 	file_put_contents($file, $current);
		 	return true;
		 }
		 else
		 {
		 	return false;
		 }
	}


	public function addSoftwareRights($categ,$software,$acces) //attribue un programme a une categorie
	{

		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		$donneefinales="";
		 for ($i=0; $i < $max; $i=$i+2) 
		 { 
		 	$cats=explode("?", $lescategs[$i]);
		 	$donneefinales=$donneefinales.$lescategs[$i].";";
		   	if ($cats[0] == $categ || $cats[0] == "\n".$categ) 
		   	{
		   		$donneefinales=$donneefinales.$lescategs[$i+1]."#".$software."[".$acces."];";
		   	}
		   	else
		   	{
		   		$donneefinales=$donneefinales.$lescategs[$i+1].";";
		   	}
		 }		 
		 $donneefinales=$donneefinales."\n";
		 file_put_contents($file, $donneefinales);
	}


	public function delSoftwareRights($categ, $software) // efface un logiciel d'une categorie
	{	
    	$nomlog=explode("?", $_POST['software'])[0];
    	$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		$lesSoftwares=[];
		 for ($i=0; $i < $max; $i=$i+2)
		 { 
		   	if ($lescategs[$i] == $categ || $lescategs[$i] == "\n".$categ)
		   	{
		   		$a=$lescategs[$i+1];
		   		$lesSoftwares=explode("#", $a );
		   		$maxs = count($lesSoftwares);
		   		for ($ii=0; $ii < $maxs; $ii++) 
		   		{
		   			$aaa=explode("?", $lesSoftwares[$ii]);
                	if(count($aaa)==2)
                    {
		   				if($aaa[0] == $nomlog)
		   				{
		   					unlink (explode("[", $aaa[1])[0]);
		   				}
                    }
		   		}		   	
		    }
		 }	
		$max=count($lescategs)-1;
		$donneefinales="";
		$lesSoftwares=[];
		 for ($i=0; $i < $max; $i=$i+2)
		 { 
		 	$cats=explode("?", $lescategs[$i]);
		 	$donneefinales=$donneefinales.$lescategs[$i].";";
		   	if ($cats[0] == $categ || $cats[0] == "\n".$categ)
		   	{
		   		$a=$lescategs[$i+1];
		   		$lesSoftwares=explode("#", $a );
		   		$maxs = count($lesSoftwares);
		   		for ($ii=0; $ii < $maxs; $ii=$ii+1) 
		   		{
		   			if($lesSoftwares[$ii] == $software)
		   			{
		   			}
		   			else
		   			{
		   				if ($ii != 0) 
		   				{
		   					$donneefinales=$donneefinales."#";
		   				}
		   					$donneefinales=$donneefinales.$lesSoftwares[$ii];
		   			}
		   		}
		   	}
		   	else
		   	{
		   		$donneefinales=$donneefinales.$lescategs[$i+1];
		   	}
		   	$donneefinales=$donneefinales.";";
		 }	
		 $donneefinales=$donneefinales."\n";
		 	file_put_contents($file, $donneefinales);
	}


	public function delCateg($name) //efface la catégorie spécifié en paramètre
	{

		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";", $current);
		$max=count($lescategs)-1;
		$res="";
	 	for ($i=0; $i < $max; $i=$i+2) 
	 	{
	 		$cats=explode("?", $lescategs[$i]);
	  	 	if ($cats[0] == $name ||$cats[0] == "\n".$name) 
	  	 	{
	   		}
	   		else
	   		{
	   			$res=$res.$lescategs[$i].";".$lescategs[$i+1].";";
	   		}
	 	}
	 	$res=$res."\n";
	 	file_put_contents($file, $res);
	}


	public function getMenu($categs) // doit afficher un menu//à compléter
	{
		$lescats=[];
		foreach ($categs as $cat) 
		{
			$file = '/var/www/donnee/base.txt';
			$current = file_get_contents($file);
			$lescategs = explode(";", $current);
			$max=count($lescategs)-1;
			
		 	for ($i=0; $i < $max; $i=$i+2) 
		 	{
		 		$cats=explode("?", $lescategs[$i]);
		  	 	if ($cats[0] == $cat ||$cats[0] == "\n".$cat) {
		   			$lescats[]=$cat;
		   		}
		 	}
		};
		return $lescats;


	}


	public function getCategs() // retourne un tableau des catégories existantes
	{

		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lignes = explode(";\n", $current);
		$demilignes=[];
		foreach ($lignes as $ligne) 
		{
			$uneligne = explode(";", $ligne);
			$cat=explode("?", $uneligne[0]);
			$demilignes[]=$cat[0];
		}
		return $demilignes;
	}


	public function newMessage($titre, $contenu, $auteur) //ajoute un message si le titre n'existe pas deja
	{

		$file = '/var/www/donnee/message.txt';
		$current = file_get_contents($file);
		$lestitres = explode("_;" , $current);
		$existant=0;
		$max=count($lestitres)-1;
		 for ($i=0; $i < $max; $i=$i+3) 
		 { 
		   	if ($lestitres[$i] == $titre || $lestitres[$i] == "\n".$titre) 
		   	{
		   		$existant=1;
		   	}
		 }
		 if($existant==0)
		 {
         	date_default_timezone_set('Europe/Paris');
         	$date = date('Y-m-d', time());
		 	$current = $current.$titre."_;".$contenu."_;".$auteur."?".$date."_;\n";
		 	file_put_contents($file, $current);
		 	return true;
		 }
		 else
		 {
		 	return false;
		 }
	}


	public function delMessage($titre) // efface le message passe en parametre
	{
		echo $titre;
		$file = '/var/www/donnee/message.txt';
		$current = file_get_contents($file);
		$lesmess = explode("_;", $current);
		$max=count($lesmess)-1;
		$res="";
	 	for ($i=0; $i < $max; $i=$i+3) {
	  	 	if ($lesmess[$i] == $titre ||$lesmess[$i] == "\n".$titre) 
	  	 	{
	   		}
	   		else
	   		{
	   			$res=$res.$lesmess[$i]."_;".$lesmess[$i+1]."_;".$lesmess[$i+2]."_;";
	   		}
	 	}
	 	$res=$res."\n";
	 	file_put_contents($file, $res);
	}


	public function getMessages()  // renvoie les messages
	
	{
		try
		{
		$file = '/var/www/donnee/message.txt';
		$current = file_get_contents($file);
		$lignes = explode("_;\n", $current);
		$decoupage=[];
		foreach ($lignes as $ligne) 
		{
			$uneligne = explode("_;", $ligne);
			if(isset($uneligne[2])){
            $decoupage[]=$uneligne[0];
            $decoupage[]=$uneligne[1];
            $auteur=explode("?",$uneligne[2]);            
            $decoupage[]=$auteur[0];}
		}
		return $decoupage;
		}
		catch(Exception $e)
		{
			echo $e;
		}
		
	}


	public function getMessagesTitre()  // renvoie les messages
	{

		$file = '/var/www/donnee/message.txt';
		$current = file_get_contents($file);
		$lignes = explode("_;\n", $current);
		$decoupage=[];
		foreach ($lignes as $ligne) 
		{
			$uneligne = explode("_;", $ligne);
			$decoupage[]=$uneligne[0];
		}
		return $decoupage;
	}

	public function checkMessage()
    {
     	$file = '/var/www/donnee/message.txt';
		$current = file_get_contents($file);
		$lignes = explode("_;\n", $current);
		$decoupage=[];
		foreach ($lignes as $ligne) 
		{
			$uneligne = explode("_;", $ligne);
			if(isset($uneligne[2])){
            $auteur=explode("?",$uneligne[2]);
            $now   = time();
			$date2 = strtotime($auteur[1]);
 			if (!function_exists('dateDiff'))
            {
			function dateDiff($date1, $date2){
 			   $diff = abs($date1 - $date2); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
 			   $retour = array();
 
 			   $tmp = $diff;
			    $retour['second'] = $tmp % 60;
 
  			  $tmp = floor( ($tmp - $retour['second']) /60 );
  			  $retour['minute'] = $tmp % 60;
 
   			 $tmp = floor( ($tmp - $retour['minute'])/60 );
   			 $retour['hour'] = $tmp % 24;
 
   			 $tmp = floor( ($tmp - $retour['hour'])  /24 );
  			  $retour['day'] = $tmp;
 
 			   return $retour['day'];
			}
            }
            $diff=dateDiff($now,$date2);
            if($diff<= 7)
            {
            	$decoupage[]=$uneligne[0];
            	$decoupage[]=$uneligne[1];                       
            	$decoupage[]=$uneligne[2];
            }
            
            }
		}
    	$res="";
    	for($i=0;$i<count($decoupage);$i=$i+3)
        {
        	$res=$res.$decoupage[$i]."_;".$decoupage[$i+1]."_;".$decoupage[$i+2]."_;\n";
        }
    	file_put_contents($file, $res);
    }
	public function getSoftwareRights($categ) //retourne les programmes autorises pour le groupe renseigne
	
	{
		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		 for ($i=0; $i < $max; $i=$i+2)
		 { 
		 	$categs=explode("?", $lescategs[$i]);
		   	if ($categs[0] == $categ || $categs[0] == "\n".$categ)
		   	{
		   		
		   		return 	explode("#", $lescategs[$i+1] );
		   	}
		 }
	}


	public function getSessionName()
	{

//$headers = apache_request_headers(); 	// Récupération des l'entêtes client

try{if (@$_SERVER['HTTP_VIA'] != NULL){ // nous verifions si un proxy est utilisé : parceque l'identification par ntlm ne peut pas passer par un proxy
	echo "Proxy bypass!";
}
elseif($headers['Authorization'] == NULL){				//si l'entete autorisation est inexistante
		header( "HTTP/1.0 401 Unauthorized" );			//envoi au client le mode d'identification
		header( "WWW-Authenticate: NTLM" );			//dans notre cas le NTLM
		exit;							//on quitte

	}}
	catch(Exception $e){
		"echo Cmauvais";
	}

	if(isset($headers['Authorization'])) 				//dans le cas d'une authorisation (identification)
	{		
		if(substr($headers['Authorization'],0,5) == 'NTLM '){ 	// on vérifit que le client soit en NTLM
	
			$chaine=$headers['Authorization']; 					
			$chaine=substr($chaine, 5); 			// recuperation du base64-encoded type1 message
			$chained64=base64_decode($chaine);		// decodage base64 dans $chained64
			
			if(ord($chained64{8}) == 1){					
			// 		  |_ byte signifiant l'etape du processus d'identification (etape 3)		
		
			// verification du drapeau NTLM "0xb2" à l'offset 13 dans le message type-1-message (comp ie 5.5+) :
				if (ord($chained64[13]) != 178){
					echo "NTLM Flag error!";
					exit;
				}
	
				$retAuth = "NTLMSSP".chr(000).chr(002).chr(000).chr(000).chr(000).chr(000).chr(000).chr(000);
				$retAuth .= chr(000).chr(040).chr(000).chr(000).chr(000).chr(001).chr(130).chr(000).chr(000);
				$retAuth .= chr(000).chr(002).chr(002).chr(002).chr(000).chr(000).chr(000).chr(000).chr(000);
				$retAuth .= chr(000).chr(000).chr(000).chr(000).chr(000).chr(000).chr(000);
				
				$retAuth64 =base64_encode($retAuth);		// encode en base64
				$retAuth64 = trim($retAuth64); 			// enleve les espaces de debut et de fin
				header( "HTTP/1.0 401 Unauthorized" ); 		// envoi le nouveau header
				header( "WWW-Authenticate: NTLM $retAuth64" );	// avec l'identification supplémentaire
				exit;
			
			}
			
			else if(ord($chained64{8}) == 3){
			// 		       |_ byte signifiant l'etape du processus d'identification (etape 5)
	
				// on recupere le domaine
				$lenght_domain = (ord($chained64[31])*256 + ord($chained64[30])); // longueur du domain
				$offset_domain = (ord($chained64[33])*256 + ord($chained64[32])); // position du domain.	
				$domain = str_replace("\0","",substr($chained64, $offset_domain, $lenght_domain)); // decoupage du du domain
				
				//le login
				$lenght_login = (ord($chained64[39])*256 + ord($chained64[38])); // longueur du login.
				$offset_login = (ord($chained64[41])*256 + ord($chained64[40])); // position du login.
				$login = str_replace("\0","",substr($chained64, $offset_login, $lenght_login)); // decoupage du login
			
				if ( $login != NULL){
					// stockage des données dans des variable de session
					//$_SESSION['Login']=$login;
					return $login;
					//header("Location: newpage.php");
					exit;
				}
				else{
					echo "NT Login empty!";
				}
					
		
			}
		}
		}
	}

	function delImage($categ,$log) //efface l'image de la categorie et efface le fichier
	{
		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		$donneefinales="";
		$lesSoftwares=[];
		 for ($i=0; $i < $max; $i=$i+2)
		 { 
		 	$donneefinales=$donneefinales.$lescategs[$i].";";
		   	if ($lescategs[$i] == $categ || $lescategs[$i] == "\n".$categ)
		   	{
		   		$a=$lescategs[$i+1];
		   		$lesSoftwares=explode("#", $a );
		   		$maxs = count($lesSoftwares);
		   		for ($ii=0; $ii < $maxs; $ii++) 
		   		{
		   			$aaa=explode("?", $lesSoftwares[$ii]);
		   			if($aaa[0] == $log)
		   			{
		   				$c=explode("[", $lesSoftwares[$ii]);
		   				$donneefinales=$donneefinales."#".$aaa[0].'['.$c[1];
		   				unlink (explode("[", $aaa[1])[0]);
		   			}
		   			else
		   			{
		   				if ($ii != 0) 
		   				{
		   					$donneefinales=$donneefinales."#".$lesSoftwares[$ii];
		   							   			}
		   		}
		   	}		   	
		   }
		   	else
		   	{
		   		$donneefinales=$donneefinales.$lescategs[$i+1];
		   	}
		   	$donneefinales=$donneefinales.";";
		 }	
		 $donneefinales=$donneefinales."\n";
		 file_put_contents($file, $donneefinales);
	}
	function addImageCat($fichier,$categ,$log) //attribue une image au programme
	{
		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		$donneefinales="";
		$lesSoftwares=[];
		 for ($i=0; $i < $max; $i=$i+2)
		 { 
		 	$donneefinales=$donneefinales.$lescategs[$i].";";
		   	if ($lescategs[$i] == $categ || $lescategs[$i] == "\n".$categ)
		   	{
		   		$a=$lescategs[$i+1];
		   		$lesSoftwares=explode("#", $a );
		   		$maxs = count($lesSoftwares);
		   		for ($ii=0; $ii < $maxs; $ii++) 
		   		{
		   			$aaa=explode("?", $lesSoftwares[$ii]);
		   			$c=explode("[", $lesSoftwares[$ii]);
		   			if($aaa[0] == $log)
		   			{
		   				$donneefinales=$donneefinales."#".$c[0].'?'.$fichier.'['.$c[1];
		   			}
		   			else
		   			{
		   				if($c[0] == $log)
			   			{
			   				$donneefinales=$donneefinales."#".$c[0].'?'.$fichier.'['.$c[1];
			   			}
			   			else
			   			{
			   				if ($ii != 0) 
			   				{
			   					$donneefinales=$donneefinales."#".$lesSoftwares[$ii];
			   				}
			   			}
			   		}
		   		}		   	
		    }
		   	else
		   	{
		   		$donneefinales=$donneefinales.$lescategs[$i+1];
		   	}
		   	$donneefinales=$donneefinales.";";
		 }	
		 $donneefinales=$donneefinales."\n";
		 	file_put_contents($file, $donneefinales);
	}
	function getImageLog($cat,$log)
	{
		$file = '/var/www/donnee/base.txt';
		$current = file_get_contents($file);
		$lescategs = explode(";" , $current);
		$max=count($lescategs)-1;
		 for ($i=0; $i < $max; $i=$i+2) 
		 { 
		   		if (stristr($lescategs[0], $cat)!= false) {
		   			$logs=explode("#", $lescategs[$i+1]);
		   			for($a=0; $a < count($logs);$a++)
		   			{
		   				if (stristr($logs[$a], $log)) {
		   				$ress=explode("?", $logs[$a]);
		   				$res=explode("[",$ress[1]);
		   				return $res[0];
		   				}
		   			}
		   			
		   			
		   		}
		   		
		   	
		 }
	}
	function rechercherUser($nom)
	{	
		function _removeAccents ($text) {
        $alphabet = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
        );
 
        $text = strtr ($text, $alphabet);
 
        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $text);
 
        return $text;
    }
    	$nom=_removeAccents($nom);
		$target=explode(" ", $nom);
		$targetvide=0;
		foreach ($target as $tgt) {
			if ($tgt != "") {
				$targetvide=1;
			}
		}
		$ldap_host = "192.168.70.106";
        // Active Directory DN, base path for our querying user
		$ldap_dn = "crousbesancon.lan";
		$ldap_base_dn = 'DC=crousbesancon , DC=lan';
        // Active Directory user for querying
		$query_user = "edvin.smajic"."@".$ldap_dn;
        // Connect to AD
		$ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
		@ldap_bind($ldap,$query_user,"AZERty12");
        // Search AD
        // $filter = 

        /////////////////////////////////////////////////////////////////////
		$results = ldap_search($ldap,$ldap_base_dn,"(&(&(&(objectCategory=person)(objectClass=user))))",array("displayname","samaccountname","employeenumber"));
		$member_list = ldap_get_entries($ldap, $results);
		$res=[];
	    // foreach($target as $mot){
	    // }
	    // }
	    if($targetvide == 1)
	    {
			for ($i=0; $i < count($member_list)-1; $i++) {
				if(isset($member_list[$i]['displayname'][0]))
				{
					$ok=0;
					foreach ($target as $tgt) {
						if(stristr(_removeAccents($member_list[$i]['displayname'][0]), $tgt) === false)
						{
							$ok=0;
							 break;
						}
						else
						{
							$ok=1;
						}
					}
					if($ok==1)
					{	
						if (isset($member_list[$i]['employeenumber'][0])) {
							$res[]="<a href='index.php?ac=menu&uc=search&login=".$member_list[$i]['samaccountname'][0]."&displayname=".$member_list[$i]["displayname"][0];
						}
					}
			   	}
			}
		}
	    return $res;
	}

	function infoUser($id)
	{
		$target=explode(" ", $id);
		$ldap_host = "192.168.70.106";
        // Active Directory DN, base path for our querying user
		$ldap_dn = "crousbesancon.lan";
		$ldap_base_dn = 'DC=crousbesancon , DC=lan';
        // Active Directory user for querying
		$query_user = "jerome.benetruy"."@".$ldap_dn;
        // Connect to AD
		$ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
		@ldap_bind($ldap,$query_user,"Azerty123");
        // Search AD
        // $filter = 

        /////////////////////////////////////////////////////////////////////
		$results = ldap_search($ldap,$ldap_base_dn,"(samaccountname=".$id.")",array("displayname","telephonenumber","othertelephone","mail"));
		$member_list = ldap_get_entries($ldap, $results);
	    return $member_list;
	}
	
	function encrypt($string)
	{
		if (!function_exists('addpadding'))
		{
			function addpadding($string, $blocksize = 32)
			{
			    $len = strlen($string);
		   	    $pad = $blocksize - ($len % $blocksize);
		       	$string .= str_repeat(chr($pad), $pad);
		       	return $string;
			}
		}
		$thepadding = addpadding($string);
	    $key = base64_decode("PSVJQRk9QTEpNVU1DWUZCRVFGV1VVT0ZOV1RRU1NaWR=");
	    $iv = base64_decode("YWlFLVEZZUFNaWlhPQ01ZT0lLWU5HTFJQVFNCRUJZVA=");
	    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $thepadding, MCRYPT_MODE_CBC, $iv));
	}
	function tvNew($ip, $idtv)
	{
		$file = '/var/www/donnee/teamviewer.txt';
		$current = file_get_contents($file);
		$current = $current.$_SESSION['nom'].";".$ip.";\r";
    	file_put_contents($file, $current);
	}
	function tvDel($line)
	{
		$file = '/var/www/donnee/teamviewer.txt';
		$current = file_get_contents($file);
		$lines = explode("\r", $current);
		$max=count($lines)-1;
		$res="";
	 	for ($i=0; $i < $max; $i=$i+3) {
	  	 	if ($lines[$i] == $line) 
	  	 	{
	   		}
	   		else
	   		{
	   			$res=$res.$lines[$i]."\r";
	   		}
	 	}
	 	file_put_contents($file, $res);
	 }
	 function tvGet()
	 {
	 	$file = '/var/www/donnee/teamviewer.txt';
		$current = file_get_contents($file);
		$res=explode("\r", $current);
		return $res;
	 }


}
?>