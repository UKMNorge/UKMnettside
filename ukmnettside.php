<?php  
/* 
Plugin Name: UKM Nettside
Plugin URI: http://www.ukm-norge.no
Description: Styrer forsiden/nettsiden til lokal- eller fylkesmønstringen
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

if( is_admin() ) {
	define('PLUGIN_NETTSIDE_DIR', dirname(__FILE__) );
	if( in_array( get_option('site_type'), array('kommune','fylke')) ) {
		add_action('UKM_admin_menu', 'UKMnettside_meny');
	}
}

// REST-API din nettside
require_once('rest_api/_register.php');

function UKMnettside_meny() {
	// Legg til side for å redigere forsideinformasjonen.
	if(get_option('site_type') == 'fylke' || get_option('site_type') == 'kommune') {
		UKM_add_menu_page(
			'content', 
			"Din nettside", 
			"Din nettside", 
			'editor', 
			'UKMnettside', 
			'UKMnettside',
			'//ico.ukm.no/menu-menu.png',
			0
		);
		UKM_add_submenu_page(	
						'UKMnettside',
						'Forsidebilde',
						'Forsidebilde',
						'editor',
						'UKMnettside_bilde',
						'UKMnettside_bilde'
					);
		
		UKM_add_submenu_page(	
						'UKMnettside',
						'Informasjonsside',
						'Informasjonsside',
						'editor',
						'UKMnettside_info',
						'UKMnettside_info'
					);

	}
	
	if( get_option('spesial_meny') ) {
		UKM_add_submenu_page(	
						'UKMnettside',
						'Ekstra-meny',
						'Ekstra-meny',
						'editor',
						'nav-menus.php',
						null
					);
	}
	
	UKM_add_scripts_and_styles( 'UKMnettside_info', 'UKMnettside_script' );
	UKM_add_scripts_and_styles( 'UKMnettside_bilde', 'UKMnettside_script' );

}


function UKMnettside_info() {
	require_once('controller/info.controller.php');
}

function UKMnettside_bilde() {
	require_once('controller/bilde.controller.php');
}

function UKMnettside() {
	require_once('controller/nettside.controller.php');
}

## INCLUDE SCRIPTS
function UKMnettside_script() {
	wp_enqueue_media();
	
	wp_enqueue_script('UKMnettside_script',  plugin_dir_url( __FILE__ )  . 'ukmnettside.js' );
#	wp_enqueue_style( 'UKMnettside_style', plugin_dir_url( __FILE__ ) .'monstring.css');
	
	wp_enqueue_script('bootstrap3_js');
	wp_enqueue_style('bootstrap3_css');
}