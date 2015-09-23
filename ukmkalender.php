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

	add_filter('UKMWPDASH_messages', 'UKMkalender_dash');
}

function link_it($text) {
	return preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a href=\"\\0\">\\0</a>", 
              $text);

}

## CREATE A MENU
function UKMkalender_menu() {
	
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

function UKMkalender_dash( $MESSAGES ) {

	$antallHendelser = 3;

	// Hent neste 3 hendelser fra SQL-database
	$sql = new SQL("SELECT * FROM `ukm_kalender` WHERE `start`>NOW() ORDER BY `start` DESC LIMIT " . $antallHendelser);
	$res = $sql->run();

	$counter = sizeof($MESSAGES) + $antallHendelser;
	if ($res) {
		// For each event:
		while( $row = mysql_fetch_assoc($res) ) {

			$row['title'] = utf8_encode($row['title']);
			$row['description'] = utf8_encode($row['description']);
			$row['location'] = utf8_encode($row['location']);
			
			$start = strtotime($row['start']);
			$location = link_it($row['location']);

			$messageText = '<b>Dato:</b> ' . $row['start'] . 
							'<br><b>Sted:</b> ' . $location .
							'<br><b>Beskrivelse:</b> ' . $row['description'];
			
			if( $start < (time()+3600*168) && $start > time() ) {
				$alertLevel = 'alert-warning';
			}
			else {
				$alertLevel = 'alert-info';
			}

			$MESSAGES_tmp[$counter] = array('level' 	=> $alertLevel,
								'header'	=> $row['title'],
								'body'		=> $messageText
								);
			$counter--;
		}
		ksort($MESSAGES_tmp);
		$MESSAGES = array_merge($MESSAGES, $MESSAGES_tmp);
	}
	return $MESSAGES;
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
		$INFOS['saveURL'] = 'webdav://kalender.ukm.no/'.$calName.'.ics';
	}
	else {
		$INFOS['savePath'] = dirname(__FILE__). '/files/' . $calName .'.ics';
		$INFOS['saveURL'] = $_SERVER['SERVER_NAME'] . '/' . $INFOS['savePath'];
	}

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		// Gjør lagring
		require_once('controller/save.controller.php');
		// Skriv ICS-fil
		require_once('controller/ics.controller.php');
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