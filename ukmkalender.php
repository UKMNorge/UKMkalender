<?php  
/* 
Plugin Name: UKM Kalender
Plugin URI: http://www.ukm-norge.no
Description: Kalenderfunksjon for fylkene
Author: UKM Norge / A Hustad
Version: 1.0 
Author URI: http://mariusmandal.no
*/

require_once('UKM/sql.class.php');
## HOOK MENU AND SCRIPTS
if(is_admin()) {
	add_action('UKM_admin_menu', 'UKMkalender_menu');

	add_filter('UKMWPDASH_calendar', 'UKMkalender_dash');
}

function link_it($text) {
	return preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a href=\"\\0\">\\0</a>", 
              $text);

}

## CREATE A MENU
function UKMkalender_menu() {
	//UKM_add_menu_page('resources','Kalender', 'Kalender', 'admin', 'UKMkalender', 'UKMkalender', 'http://ico.ukm.no/calendar-menu.png',21);
	UKM_add_menu_page('resources','Kalender', 'Kalender', 'editor', 'UKMkalender', 'UKMkalender', 'http://ico.ukm.no/calendar-menu.png',21);
	UKM_add_scripts_and_styles( 'UKMkalender', 'UKMkalender_script', 5000 );
}

## INCLUDE SCRIPTS
function UKMkalender_script() {
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
#	wp_enqueue_style( 'UKMkalender_style', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.css');
	wp_enqueue_script( 'UKMKalender_script', plugin_dir_url( __FILE__ ) .'ukmkalender.js');
}

function UKMkalender_dash( $KALENDER ) {
	require_once('functions.php');
	$pl = new monstring(get_option("pl_id"));
	$fylkeId = $pl->get('fylke_id');
	$antallHendelser = 3; // Antall hendelser som skal listes opp i meldinger.

	// Hent neste 3 hendelser fra SQL-database
	$sql = new SQL("SELECT * FROM `ukm_kalender` WHERE `fylke` = ".$fylkeId." AND start>=CURDATE() ORDER BY `start` LIMIT " . $antallHendelser);
	$res = $sql->run();

	//$counter = sizeof($MESSAGES) + $antallHendelser;
	if ($res) {
		// For each event:
		while( $row = mysql_fetch_assoc($res) ) {

			$row['title'] = utf8_encode($row['title']);
			$row['description'] = utf8_encode($row['description']);
			$row['location'] = utf8_encode($row['location']);
			
			$start = strtotime($row['start']);
			$location = link_it($row['location']);
			$description = link_it($row['description']);
			
			$row['start'] = ucfirst(dato($row['start'], 'l d. F'));

			$messageText = '<b>Dato:</b> ' . $row['start'] . 
							'<br><b>Sted:</b> ' . $location .
							'<br><b>Beskrivelse:</b> ' . $description;
			
			if( $start < (time()+3600*168) && $start > date("c") ) {
				$alertLevel = 'alert-warning';
			}
			else {
				$alertLevel = '';
			}

			$KALENDER_tmp[] = array('level' 	=> $alertLevel,
								'header'	=> $row['title'],
								'body'		=> $messageText,
								'date'		=> $row['start']
								);
		}
		if( is_array( $KALENDER_tmp ) ) {
			ksort($KALENDER_tmp);
			$KALENDER = array_merge($KALENDER, $KALENDER_tmp);
		}
	}
	return $KALENDER;
}

function UKMkalender() {
	$pl = new monstring(get_option("pl_id"));

	$INFOS = array();
	
	$INFOS['season'] = get_option('season');
	$INFOS['site_type'] = get_option('site_type');
	$INFOS['tab_active'] = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

	//$calName = 'Testkalender'; // Bør baseres på fylke etterhvert?
	$calName = $pl->_sanitize_nordic($pl->get('fylke_name'));
	//$INFOS['savePath'] = dirname(__FILE__). '/files/' . $calName .'.ics';
	if (UKM_HOSTNAME == "ukm.no") {
		$INFOS['savePath'] = '/home/ukmno/public_subdomains/kalender/'.$calName.'.ics';
		$INFOS['saveURL'] = 'webcal://kalender.ukm.no/'.$calName.'.ics';
	}
	else {
		$INFOS['savePath'] = dirname(__FILE__). '/files/' . $calName .'.ics';
		$INFOS['saveURL'] = 'webcal://' . $_SERVER['SERVER_NAME'] . $INFOS['savePath'];
	}

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		// Gjør lagring
		require_once('controller/save.controller.php');
		// Skriv ICS-fil
		require_once('controller/ics.controller.php');
		// Gå over til listevisninga
		$INFOS['tab_active'] = 'list';
	}
	elseif( $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['edit'] == 'true') {
		// Kjør oppdatering av form-verdier fra MySQL-databasen.
		require_once('controller/form.controller.php');
	}
	elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['delete'] == 'true') {
		// Kjør controller som sletter databaseoppføring.
		require_once('controller/delete.controller.php');
		// Gå over til listevisninga
		$INFOS['tab_active'] = 'list';
	}
	
	switch( $INFOS['tab_active'] ) {
		case 'info':
			require_once('controller/info.controller.php');
			break;
		case 'create':
			require_once('controller/form.controller.php');
			break;
		default:
			require_once('controller/list.controller.php');
			break;
	}
	//var_dump($INFOS); // Debug-help
	echo TWIG($INFOS['tab_active'].'.twig.html', $INFOS , dirname(__FILE__), true);

}