<?php  
/* 
Plugin Name: UKM Kalender
Plugin URI: http://www.ukm-norge.no
Description: Kalenderfunksjon for fylkene
Author: UKM Norge / A Hustad
Version: 1.0 
Author URI: http://mariusmandal.no
*/

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
#	wp_enqueue_script( 'UKMkalender_script', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.js');	
}

function UKMkalender() {
	$INFOS = array();
#	require_once('monstringer.controller.php');
	echo TWIG('dash.twig.html', $INFOS , dirname(__FILE__));

}