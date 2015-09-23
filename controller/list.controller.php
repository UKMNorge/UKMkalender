<?php

$sql = new SQL("SELECT * FROM `ukm_kalender` ORDER BY `start`");
$res = $sql->run();
if( $res ) {
	while( $row = mysql_fetch_assoc( $res ) ) {
		$row['title'] = utf8_encode($row['title']);
		$row['description'] = utf8_encode($row['description']);
		$row['location'] = utf8_encode($row['location']);
		if (strtotime($row['start']) < time()) { // Hvis gamlere enn nåtid
			$INFOS['calendar_events']['Tidligere'][] = $row;
		}	
		else {
			$INFOS['calendar_events']['Kommende'][] = $row;
		}
	}
}