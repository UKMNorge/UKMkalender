<?php

$sql = new SQL("SELECT * FROM `ukm_kalender` ORDER BY `title`");
$res = $sql->run();
if( $res ) {
	while( $row = mysql_fetch_assoc( $res ) ) {
		$INFOS['events'][] = $row;
	}
}