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
	<title>Modification d'une Opération</title>

</head>

<?php
	include("menu.php");
?>
<body>
<div id="top-margin" class="top-margin">
<form method="POST">
<?php
	require_once('bdd.php');
	$conectBDD = new BDD();
?>

<h1 class="title">Modification d'une Opération</h1>
<br>
	<?php
	if(isset($_POST['idModif']))
	{
		$_SESSION['id'] = $_POST['idModif'];
	}
	
	$id = (int)$_SESSION['id'];
	$InfosElement = $conectBDD->Element_Infos_parId($id);
	$libelle = $InfosElement['LibelleElement'];
	if(isset($_POST["Valider"]))
	{
		$libelle = $_POST['libelle'];

		$res = $conectBDD->Element_Modif($id, $libelle);
		if($res)
		{
			?>
			<div class="alert alert-success" role="alert">
			  	L'Element a bien été modifié.
			</div>
			<meta http-equiv="refresh" content=<?php echo '"'.$_SESSION['Loading'];?>;URL=gestion_operation.php"> 
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
	$InfosElement = $conectBDD->Element_Infos_parId($id);
	$libelle = $InfosElement['LibelleElement'];
	?>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 150px" id="basic-addon1">Libellé</span>
  			<?php echo '<input type="text" name="libelle" required style="width: 200px" class="input-text form-control" aria-describedby="basic-addon1" value="' . $libelle . '">'; ?>
  		</div>

		<br><br><button type="submit" name="Valider" class="btn btn-primary">
			<i class="glyphicon glyphicon-ok-sign"></i> Valider
		</button>
		<br></form>
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