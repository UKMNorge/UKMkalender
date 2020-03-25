<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Database\SQL\Query;

require_once('UKM/Autoloader.php');

require_once('ICS/Calendar.php');
require_once('ICS/Event.php');
require_once('ICS/Alarm.php');


$eventName = 'Eventtittel';

$cal = new ICS\Calendar($calName);

$pl = new Arrangement(intval(get_option("pl_id")));
$fylkeName = $pl->get('fylke_name');
$fylkeId = $pl->get('fylke_id');

$cal->setDescription('Årshjul ' . $fylkeName . ' UKM');

// Add events, generate from database
// Kjør nytt query?
$sql = new Query("SELECT * FROM `ukm_kalender` WHERE `fylke` = ".$fylkeId." ORDER BY `title`");
$res = $sql->run();

if ($res) {
	// For each event:
	while( $row = Query::fetch($res) ) {

		$row['title'] = $row['title'];
		$row['description'] = $row['description'];
		$row['location'] = $row['location'];
		
		$event = new ICS\Event($eventName);
		$start = new DateTime($row['start']);
		$stop = new DateTime($row['stop']);

		$event->setTitle($row['title']);
		$event->setDescription($row['description']);
		$event->setLocation($row['location']);
		$event->setStart($start); // TODO: Endre til datetime
		$event->setStop($stop);	// 

		$varsel = varsel($row['varsling']);
		#var_dump($varsel);
		if($varsel) {
			#echo 'Varsel eksporteres';
			$alarm = new ICS\Alarm();
			$alarm->setAction("DISPLAY")
				->setTriggerType("PRIOR")
				->setDescription("alarm")
				->setTrigger($varsel);

			$event->addAlarm($alarm);
		}

		$cal->addEvent($event);	
	}
	#echo '<pre>';
	#var_dump($cal);
	#echo '</pre>';
	//var_dump($INFOS['savePath']); // Debug
	$cal->write($INFOS['savePath'], ''); //TODO:
}
// Skriv ut kalender til fil, gi adresse til fil tilbake.

// Beregn varslingsminutter basert på verdi fra databasen.
function varsel($val) {
	$varsling = false;
	switch ($val) {
		case 'sdkl8': # Samme dag klokka 8
			$varsling = 480; # 60 min * 8 timer = 480 minutter ekstra
			break;
		case 'sdkl12': # Samme dag klokka 12
			$varsling = 720; # 60 min * 12 timer = 720 minutter ekstra
			break;
		case 'df': # Dagen før klokka 12.
			$varsling = -720; # 60 min * 12 timer * -1 for fortid.
			break;
		case 'uf':  # Èn uke før, kl. 12 på dagen.
			$varsling = -9360; # -(60 min * 24 timer * 7 dager) + (60min * 12 timer) = -9360 minutter
			break;
		case 'none':
		default:
			$varsling = false;
			break;
	}
	return $varsling;
}

?>