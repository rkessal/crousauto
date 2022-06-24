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
	<title>Modification d'un type de contrôle technique</title>

  	<?php
	include("menu.php");
	require_once('bdd.php');
	$conectBDD = new BDD();
?>
</head>


<body>
<div id="top-margin" class="top-margin">
<form method="POST">

<h1 class="title">Modification d'un type de contrôle technique</h1>
<br>
	<?php
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	
	$id = (int)$_SESSION['id'];
	$InfosElement = $conectBDD->Controle_Technique_Infos_parId($id);
	$libelle = $InfosElement['TypeControle'];
	if(isset($_POST["Valider"]))
	{
		$libelle = $_POST['libelle'];

		$res = $conectBDD->Controle_Technique_Modif($id, $libelle);
		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	Le Type de controle technique a bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_type_ct.php"> 
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
	$id = (int)$_SESSION['id'];
	$InfosElement = $conectBDD->Controle_Technique_Infos_parId($id);
	$libelle = $InfosElement['TypeControle'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Libellé</span>
  			<?php echo '<input type="text" name="libelle" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $libelle . '">'; ?>
  		</div>
		<br>
		
		<br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button></form>
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