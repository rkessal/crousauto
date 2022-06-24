<?php

require_once('../../crous_reservation_salle/auth/bdd.php');
$conectBDD = new BDD();

$infosSalle = $conectBDD->Salle_Infos_parId(1);
$listeUtilisation = $conectBDD->Reservation_Liste_ParSalle($_SESSION['idSalle']);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='../fullcalendar.min.css' rel='stylesheet' />
<link href='../fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='../lib/moment.min.js'></script>
<script src='../lib/jquery.min.js'></script>
<script src='../fullcalendar.min.js'></script>
<script src='../locale/fr.js'></script>


<script>

  $(document).ready(function() {
	var d = new Date();

	var month = d.getMonth()+1;
	var day = d.getDate();

	var output = d.getFullYear() + '-' +
		(month<10 ? '0' : '') + month + '-' +
		(day<10 ? '0' : '') + day;

    $('#calendar').fullCalendar({
		
	  locale: 'fr',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek'
      },
	  
      defaultDate: output,
	  defaultView: 'agendaWeek',
	  minTime : "07:00:00",
	  maxTime : "19:00:00",
	  timeFormat: 'H:mm',
	  firstDay : 1,
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: [
	 
		<?php  
		foreach ($listeUtilisation as $UneLigne) {
				$motif = $UneLigne['motifReservation'];
				$idReservation = $UneLigne['idReservation'];
				$dateHeureDebut = $UneLigne['dateHeureDebut'];
				$dateHeureFin = $UneLigne['dateHeureFin'];
				$couleurAffichageUtilisateur = "#".$UneLigne['couleurAffichageUtilisateur'];
				
				$dateHeureDebut = new DateTime($dateHeureDebut);
				$dateDebut = $dateHeureDebut->format('Y-m-d');
				$heureDebut = $dateHeureDebut->format('H:i');
				
				$dateHeureFin = new DateTime($dateHeureFin);
				$datefin = $dateHeureFin->format('Y-m-d');
				$heurefin = $dateHeureFin->format('H:i');
				
				$dateHeureDebut = ''.$dateDebut.'T'.$heureDebut;
				$dateHeureFin = ''.$datefin.'T'.$heurefin;
				
				$allDay = "false";
				
				if ($heureDebut == "07:00" && $heurefin == "19:00")
				{
					$allDay = "true";
				}
				
				
				
				echo "
				{
				title : '".$motif."',
				start : '".$dateHeureDebut."',
				end : '".$dateHeureFin."',
				color : '".$couleurAffichageUtilisateur."',
				textColor: 'black',
				allDay : ".$allDay.",
				url : '../../crous_reservation_salle/auth/modif_reservation_salle.php?idReservation=".$idReservation."'
				},
				";
		}
		
		?>
        /*{
          title: 'All Day Event',
          start: '2019-01-01',
        },
        {
          title: 'Long Event',
          start: '2019-01-07',
          end: '2019-01-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-01-09T16:00:00'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2019-01-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2019-01-11',
          end: '2019-01-13'
        },
        {
          title: 'Meeting',
          start: '2019-01-12T10:30:00',
          end: '2019-01-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2019-01-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2019-01-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2019-01-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2019-01-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2019-01-13T07:00:00'
        },*/
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2019-01-28'
        }
      ]
	  
    });

  });

</script>
<style>

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }

</style>
</head>
<body>

  <div id='calendar'></div>

</body>
</html>
