<?php session_start();
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if (true)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Mon compte</title>

  	<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();
	$info = $conectBDD->User_Infos_parPseudo($_SESSION['pseudo']);
?>
</head>
<body>
<form method="POST">

<div id="top-margin" class="top-margin">
<h1>Paramètre de mon compte</h1>
<br>
	<?php
	if(isset($_POST["Valider"]))
	{
		$pseudo = $info['PseudoUtilisateur'];
		$pass = $_POST['pass'];
		$confpass = $_POST['confpass'];
		$Ancienpass = $_POST['AncienPass'];

		$conectBDD = new BDD();
		$res = $conectBDD->Profil_Connexion($pseudo, $Ancienpass);
		if($res)
		{
			if($pass == $confpass)
			{
				if(strlen($pass)>=8)
				{
					$pass = password_hash($pass,PASSWORD_DEFAULT);
					$res = $conectBDD->User_ReiniMdp($pseudo, $pass);
					if($res!=false)
					{
						?>
						<div class="alert alert-success" role="alert">
						  	Le changement a bien été prise en compte.
						</div>
						<?php
					}	
					else
					{
						?>
						<div class="alert alert-danger" role="alert">
						  	Erreur.
						</div>
						<?php
					}	
				}
				else
				{
					?>
					<div class="alert alert-danger" role="alert">
					  	Le mot de passe doit avoir au moins 8 caractères.
					</div>
					<?php
				}		
			}
			else
			{
				?>
				<div class="alert alert-danger" role="alert">
				  	Les deux mots de passes saisies ne sont pas identiques.
				</div>
				<?php
			}		
		}
		else
		{
			?>
			<div class="alert alert-danger" role="alert">
			  	L'ancien mot de passe est incorect.
			</div>
			<?php
		}	
		
	}
	?>
	<br>
		<center><b>Changement de Mot de Passe</b></center><br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 350px" id="basic-addon1">Ancien Mot de Passe</span>
  			<input type="password" name="AncienPass" required style="width: 200px" class="form-control" aria-describedby="basic-addon1">
		</div><br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 350px" id="basic-addon1">Nouveau Mot de Passe</span>
  			<input type="password" name="pass" required style="width: 200px" class="form-control" aria-describedby="basic-addon1">
		</div><br>
		<div class="input-group" style="text-align: center">
  			<span class="input-group-addon" style="width: 350px" id="basic-addon1">Confirmation du Nouveau Mot de Passe</span>
  			<input type="password" name="confpass" required style="width: 200px" class="form-control" aria-describedby="basic-addon1">
		</div><br>
		<br><br>
		<input class="btn btn-primary" style="width:220px" type="submit" name="Valider">
		

</form>
</div>
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>
</html>