<?php session_start(); 
require_once('bdd.php');
$conectBDD = new BDD();
$liste = $conectBDD->User_Liste_parPseudo($_SESSION['pseudo']);
if ($liste['DroitService'] == 0)
{
	header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>

	<title>Statistiques</title>
	
</head>
<?php
	require_once('bdd.php');
	include("menu.php");
	$conectBDD = new BDD();		

	if($_SESSION['NoResidence'] == 0)
    $listeVehicule = $conectBDD->Vehicule_Liste();
  else
  {
    if($liste['NoDroit'] == 1)
      $listeVehicule = $conectBDD->Vehicule_Liste_parResidence($_SESSION['NoResidence']);
  }

	//var_dump($listeVehicule);
	?>
<body>
<div id="top-margin" class = "top-margin">
<h1 class="title">STATISTIQUES</h1>
<form method="post" style="display: inline-block;">

<?php
/*
var_dump(!isset($_POST['Date1']));
var_dump(!isset($_POST['Date2']));
var_dump(!isset($_SESSION['Date1']));
var_dump(!isset($_SESSION['Date2']));
*/
if(((!isset($_POST['Date1'])) and (!isset($_POST['Date2']))) and ((!isset($_SESSION['Date1'])) and (!isset($_SESSION['Date2']))))
{
  ?>
  du
  <input type="text" required name="Date1" style="width: 200px; display: inline-block;" class="input-text form-control" aria-describedby="basic-addon1" id="datepicker2" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value="' . date('Y') . '-01-01"'; ?>>
  au
  <input type="text" required name="Date2" style="width: 200px; display: inline-block;"  class="input-text form-control" aria-describedby="basic-addon1" id="datepicker" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value="' . date('Y') . '-12-31"'; ?>>
  <button class="btn btn-primary" ><p><div class="glyphicon glyphicon-ok"></div> Valider</p></button>
  </form> 
  <?php
}
else
{
  if(isset($_POST['Date1']))
    $_SESSION['Date1'] = $_POST['Date1'];
  if(isset($_POST['Date2']))
    @$_SESSION['Date2'] = $_POST['Date2'];
  ?>
  du
  <input type="text" required name="Date1" style="width: 200px; display: inline-block;" class="input-text form-control" aria-describedby="basic-addon1" id="datepicker2" autocomplete="off" placeholder="Sélectionnez une date" <?php echo 'value="' . $_SESSION['Date1'] . '"'; ?>>
  au
  <input type="text" required name="Date2" style="width: 200px; display: inline-block;"  class="input-text form-control" aria-describedby="basic-addon1" id="datepicker" autocomplete="off" placeholder="Sélectionnez une date"<?php echo 'value="' . $_SESSION['Date2'] . '"'; ?>>
  <button class="btn btn-primary" ><p><div class="glyphicon glyphicon-ok"></div> Valider</p></button>
  </form> 
  <br>
<br>
<h2>Général</h2>
<div class="graph-container">
<div id="graphUtiliseVehiculeParService" class="graph-row"></div>
<div id="graphUtilisationVehicules" class="graph-row"></div>
<div id="graphKmVehicule" class="graph-row"></div>
<div id="graphCout" class="graph-row"></div>
<div id="graphCoutKm" class="graph-row"></div>
</div>


<br>
<h2>Par véhicule</h2>
<div class="graph-container">
<?php
	foreach ($listeVehicule as $unVehi) 
	{
		$infosUsageVehicule = $conectBDD->Statistique_General_ListeUtilisationVehiculeParImmat($unVehi['ImmatriculationVehicule'], $_SESSION['Date1'], $_SESSION['Date2']);
		if($infosUsageVehicule)
			echo '<div id="Utilisation' . $unVehi["ImmatriculationVehicule"] . '" class="graph-row"></div>';
	}
}
?>


</div>
</div>
  
</body>
<footer>
	<?php
		include("footer.php");
	?>
</footer>

</html>


<script type="text/javascript">
	var chartUtilisationVehicule = Highcharts.chart('graphUtiliseVehiculeParService', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Utilisation des véhicules par services'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} h</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} h',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
	<?php
	$premier = true;
  if($_SESSION['NoResidence'] == 0)
	 $listeService = $conectBDD->Statistique_General_ListeUtilisationService($_SESSION['Date1'], $_SESSION['Date2']);
  else
    $listeService = $conectBDD->Statistique_General_ListeUtilisationServiceParResidence($_SESSION['Date1'], $_SESSION['Date2'], $_SESSION['NoResidence']);
	foreach ($listeService as $unService) 
	{
		if(!$premier)
		{
			
			echo ',';
		}
		$premier = false;
	?>
		{
		name: <?php echo "'".$unService['NomService']."'";?>,
		y: <?php echo $unService['Nb'];?>,
      	sliced: true,
      	selected: true
      	}
  	<?php
 	}
  	?>
    ]
  }]
});

var chartDureeDeplacement = Highcharts.chart('graphUtilisationVehicules', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Durée totale de déplacement des véhicules'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} h</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} h',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
	<?php
	$premier = true;
	
  if($_SESSION['NoResidence'] == 0)
    $listeUsageVehicule = $conectBDD->Statistique_General_ListeUtilisationVehicule($_SESSION['Date1'], $_SESSION['Date2']);
  else
    $listeUsageVehicule = $conectBDD->Statistique_General_ListeUtilisationVehicule_parResidence($_SESSION['Date1'], $_SESSION['Date2'], $_SESSION['NoResidence']);

	foreach ($listeUsageVehicule as $unVehicule) 
	{
		if(!$premier)
		{
			
			echo ',';
		}
		$premier = false;
	?>
		{
		name: <?php echo "'".$unVehicule["ConstructeurVehicule"]." ".$unVehicule["ModeleVehicule"]." (".$unVehicule["ImmatriculationVehicule"].")'";?>,
		y: <?php echo $unVehicule['Nb'];?>,
      	sliced: true,
      	selected: true
      	}
  	<?php
 	}
  	?>
    ]
  }]
});

	var chartCoutVehicule = Highcharts.chart('graphCout', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Couts par véhicules'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} €</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} €',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
	<?php
	$premier = true;
  if($_SESSION['NoResidence'] == 0)
	 $listeCoutVehicule = $conectBDD->Cout_Liste_Entre2Dates($_SESSION['Date1'], $_SESSION['Date2']);
  else
    $listeCoutVehicule = $conectBDD->Cout_Liste_Entre2Dates_ParResidence($_SESSION['Date1'], $_SESSION['Date2'], $_SESSION['NoResidence']);
  
	foreach ($listeCoutVehicule as $unVehicule) 
	{
		if(!$premier)
		{
			
			echo ',';
		}
		$premier = false;
    $infosVehicule = $conectBDD->Vehicule_Liste_parImmatriculation($unVehicule["ImmatriculationVehicule"]);
	?>
		{
		name: <?php echo "'".$infosVehicule["ConstructeurVehicule"]." ".$infosVehicule["ModeleVehicule"]." (".$unVehicule["ImmatriculationVehicule"].")'";?>,
    y: <?php echo $unVehicule['Montant'];?>,
  	sliced: true,
  	selected: true
  	}
  	<?php
 	}
  	?>
    ]
  }]
});

  var chartCoutKmVehicule = Highcharts.chart('graphCoutKm', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Couts Kilométrique par véhicules (entre les 2 derniers relevés kilométriques)'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} €/km</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} €/km',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
  <?php
  $premier = true;
  if($_SESSION['NoResidence'] == 0)
   $listeCoutVehicule = $conectBDD->Cout_Liste_Entre2Dates($_SESSION['Date1'], $_SESSION['Date2']);
  else
    $listeCoutVehicule = $conectBDD->Cout_Liste_Entre2Dates_ParResidence($_SESSION['Date1'], $_SESSION['Date2'], $_SESSION['NoResidence']);
  
  foreach ($listeVehicule as $unVehicule) 
  {
    $infosVehicule = $conectBDD->Vehicule_Liste_parImmatriculation($unVehicule["ImmatriculationVehicule"]);
    if($infosVehicule['TypeVehicule'] != 'Velo')
    {
      $infosKmVehicule = $conectBDD->Kilometrage_Liste_parImmatriculationASC($unVehicule['ImmatriculationVehicule']);
      

      if($infosKmVehicule == null)
      {
        $diffKm = 0;
      }
      else
      {
        $premierKm = true;
        $km1 = 0;
        $dateReleve1 = '1900-01-01';
        $dateReleve2 = '1900-01-01';
        $km2 = 0;
        foreach ($infosKmVehicule as $unKm)
        {
          if($premierKm)
          {
            $km1 = $unKm['KilometrageReleve'];
            $dateReleve1 = $unKm['DateKilometrage'];
            $premierKm = false;
          }
          else
          {
            $km2 = $unKm['KilometrageReleve'];
            $dateReleve2 = $unKm['DateKilometrage'];
            break;
          }
        }
        if($km2 == 0)
        {
          $dateReleve2 = $dateReleve1;
          $dateReleve1 = $infosVehicule['DatePremiereImmatriculationVehicule'];
          $diffKm = $km1 - $infosVehicule['KilometrageVehicule'];
        }
        else
        {
          $diffKm = $km2 - $km1;
        }
      }

    }
      

      $CoutVehicule = $conectBDD->Cout_Liste_Entre2Dates_ParVehicule($unVehicule['ImmatriculationVehicule'], $dateReleve1, $dateReleve2);

      if($diffKm == 0)
        $coutKilometrique = 0;
      else
        $coutKilometrique = $CoutVehicule['Montant'] / $diffKm;
    if($coutKilometrique != 0)
    {
      if(!$premier)
      {
        echo ',';
      }
      $premier = false;
      ?>
    
      {
      name: <?php echo "'".$infosVehicule["ConstructeurVehicule"]." ".$infosVehicule["ModeleVehicule"]." (".$unVehicule["ImmatriculationVehicule"].")'";?>,
      y: <?php echo $coutKilometrique;?>,
      sliced: true,
      selected: true
      }
      <?php
    }
  }
    ?>
    ]
  }]
});

  var charts = Highcharts.chart('graphKmVehicule', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Distance effectuée entre les 2 derniers relevés kilométriques'
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} kms</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} kms',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
  <?php
  $premier = true;
  
  foreach ($listeVehicule as $unVehicule) 
  {
    if($unVehicule['TypeVehicule'] != 'Velo')
    {
      $listeKmVehicule = $conectBDD->Kilometrage_Liste_parImmatriculationASC($unVehicule['ImmatriculationVehicule']);

      if($listeKmVehicule == null)
      {
        $diffKm = 0;
      }
      else
      {
        $premierKm = true;
        $km1 = 0;
        $km2 = 0;
        foreach ($listeKmVehicule as $unKm)
        {
          if($premierKm)
          {
            $km1 = $unKm['KilometrageReleve'];
            $premierKm = false;
          }
          else
          {
            $km2 = $unKm['KilometrageReleve'];
            break;
          }
        }
        if($km2 == 0)
        {
          $diffKm = $km1 - $unVehicule['KilometrageVehicule'];
        }
        else
        {
          $diffKm = $km2 - $km1;
        }
      }
      if(!$premier)
      {
        echo ',';
      }
      $premier = false;
    ?>
      {
      name: <?php echo "'".$unVehicule["ConstructeurVehicule"]." ".$unVehicule["ModeleVehicule"]." (".$unVehicule["ImmatriculationVehicule"].")'";?>,
      y: <?php echo $diffKm;?>,
          sliced: true,
          selected: true
          }
      <?php
    }
  }
  ?>
  ]
  }]
});

<?php
$unVehicule = null;
foreach ($listeVehicule as $unVehicule) 
{
  $infosUsageVehicule = $conectBDD->Statistique_General_ListeUtilisationServiceParImmat($unVehicule['ImmatriculationVehicule'], $_SESSION['Date1'], $_SESSION['Date2']);
  if($infosUsageVehicule)
  {
	?>
	var chartUtilisationParVehicule = Highcharts.chart(<?php echo "'Utilisation" . $unVehicule["ImmatriculationVehicule"] . "'";?>, {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: <?php echo "'Utilisation du véhicule ".$unVehicule["ConstructeurVehicule"]." ".$unVehicule["ModeleVehicule"]." (".$unVehicule["ImmatriculationVehicule"].") par services'";?>
  },
  tooltip: {
    pointFormat: '{series.name} <b>{point.y:.1f} h</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.y:.1f} h',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: '',
    colorByPoint: true,
    data: [
	<?php
	$premier = true;
	 $listeUsageVehicule = $conectBDD->Statistique_General_ListeUtilisationServiceParImmat($unVehicule["ImmatriculationVehicule"], $_SESSION['Date1'], $_SESSION['Date2']);
  
		foreach ($listeUsageVehicule as $unUsageVehicule) 
		{
			if(!$premier)
			{
				echo ',';
			}
			$premier = false;
		?>
			{
			name: <?php echo "'".$unUsageVehicule['NomService']."'";?>,
			y: <?php echo $unUsageVehicule['Nb'];?>,
	      	sliced: true,
	      	selected: true
	      	}
	      	
	
	  	<?php
	 	}
	  	?>
	    ]
	  }]

	});
  <?php
  }
}
?>
function redrawHighcharts() {
    for (var i = 0; i < Highcharts.charts.length; i++) {
        Highcharts.charts[i].reflow();
    }
}

$(document).ready(function(){
             redrawHighcharts();
            });

var observer = new MutationObserver(function(mutations) {
  redrawHighcharts();
  });
  var target = document.querySelector('.top-margin');
  observer.observe(target, {
    attributes: true
  });

</script>
