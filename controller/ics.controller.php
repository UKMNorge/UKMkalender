<?php
require_once('ICS/Calendar.php');
require_once('ICS/Event.php');
require_once('UKM/sql.class.php');


$eventName = 'Eventtittel';

$cal = new ICS\Calendar($calName);

$cal->setDescription('Testkalender for fylkenes årshjul');

// Add events, generate from database
// Kjør nytt query?
$sql = new SQL("SELECT * FROM `ukm_kalender` ORDER BY `title`");
$res = $sql->run();

if ($res) {
	// For each event:
	while( $row = mysql_fetch_assoc($res) ) {

		$row['title'] = utf8_encode($row['title']);
		$row['description'] = utf8_encode($row['description']);
		$row['location'] = utf8_encode($row['location']);
		
		$event = new ICS\Event($eventName);
		$start = new DateTime($row['start']);
		$stop = new DateTime($row['stop']);

		$event->setTitle($row['title']);
		$event->setDescription($row['description']);
		$event->setLocation($row['location']);
		$event->setStart($start); // TODO: Endre til datetime
		$event->setStop($stop);	// 

		$cal->addEvent($event);	
	}
	$cal->write($INFOS['savePath'], ''); //TODO:
}
// Skriv ut kalender til fil, gi adresse til fil tilbake.



?>