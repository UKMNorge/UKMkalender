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
}

## CREATE A MENU
function UKMkalender_menu() {
	global $UKMN;
	if( get_option('site_type') == 'fylke' ) {
		UKM_add_menu_page('resources','Kalender', 'Kalender', 'editor', 'UKMkalender', 'UKMkalender', 'http://ico.ukm.no/calendar-menu.png',21);
		UKM_add_scripts_and_styles( 'UKMkalender', 'UKMkalender_script' );
	}
}

## INCLUDE SCRIPTS
function UKMkalender_script() {
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
#	wp_enqueue_style( 'UKMkalender_style', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.css');
	wp_enqueue_script( 'UKMKalender_script', plugin_dir_url( __FILE__ ) .'ukmkalender.js');
}

function UKMkalender() {
	$INFOS = array();
	
	$INFOS['season'] = get_option('season');
	$INFOS['site_type'] = get_option('site_type');
	$INFOS['tab_active'] = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		// Gjør lagring
		require_once('controller/save.controller.php');

		$INFOS['tab_active'] = 'list';
	}
	
	switch( $INFOS['tab_active'] ) {
		case 'info':
			break;
		case 'create':
			require_once('controller/form.controller.php');
			break;
		default:
			require_once('controller/list.controller.php');
			break;
	}
	var_dump($INFOS);
	echo TWIG($INFOS['tab_active'].'.twig.html', $INFOS , dirname(__FILE__), true);

}