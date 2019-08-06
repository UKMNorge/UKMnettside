<?php  
/* 
Plugin Name: UKM Nettside
Plugin URI: http://www.ukm-norge.no
Description: Styrer forsiden/nettsiden til lokal- eller fylkesmønstringen
Author: UKM Norge / M Mandal 
Version: 2.0 
Author URI: http://www.ukm-norge.no
*/


require_once('UKM/wp_modul.class.php');
define('PLUGIN_NETTSIDE_DIR', dirname(__FILE__) ); // TODO: skrives ut til fordel for UKMnettside::getPath()

class UKMnettside extends UKMWPmodul
{
	public static $action = 'forsidebilde';
	public static $path_plugin = null;

	public static function hook()
	{
		add_action( 'init', ['UKMnettside','registrer_forsidemeny'] );
		add_filter('UKMWPDASH_messages', ['UKMnettside','meldinger']);	

		if( in_array( get_option('site_type'), ['kommune','fylke']) || get_option('spesial_meny') ) {
			add_action('admin_menu', ['UKMnettside','meny']);
		}
	}

	/**
	 * Registrer forside-menyen som kan lages
	 *
	 * @return void
	 */
	public static function registrer_forsidemeny() {
		register_nav_menu('ukm-meny', 'Din forside-meny');
	}

	/**
	 * Render informasjonsside-admin
	 *
	 * @return void
	 */
	public static function renderInfoAdmin() {
		static::setAction('infoside');
		require_once( static::getPluginPath() .'controller/infoside.controller.php');
	}

	/**
	 * Admin-meny
	 *
	 * @return void
	 */
	public static function meny() {
		// Forsidebilde
		$page_bilde = add_submenu_page(
			'edit.php',
			'Forsidebilde',
			'Forsidebilde',
			'edit_posts',
			'UKMnettside_forside',
			['UKMnettside', 'renderAdmin']
		);

		// Informasjonsside
		$page_info = add_submenu_page(
			'edit.php',
			'Informasjonsside',
			'Informasjonsside',
			'edit_posts',
			'UKMnettside_infoside',
			['UKMnettside', 'renderInfoAdmin']
		);

		// Meny
		$page_meny = add_submenu_page(
			'edit.php',
			'Meny',
			'Meny',
			'edit_posts',
			'nav-menus.php'
		);

		foreach( ['meny','info','bilde'] as $key ) {
			add_action( 
				'admin_print_styles-' . ${'page_'.$key}, 
				['UKMnettside','scripts_and_styles']
			);
		}
	}

	/**
	 * Legg til melding til brukeren om at informasjonssiden må være oppdatert
	 *
	 * @param Array $meldinger
	 * @return Array $meldinger
	 */
	public static function meldinger( $meldinger ) {
		$forside = get_page_by_path('info');
		if( null == $forside ) {
			return $meldinger;
		}
		
		if( get_option('UKMnettside_info_last_updated' ) < (int) mktime(0,0,0,8,1, get_site_option('season')-1 ) ) {
			$meldinger[] = array('level' 	=> 'alert-warning',
									'header' 	=> 'Sjekk at informasjonssiden din er oppdatert',
									'link' 		=> 'admin.php?page=UKMnettside_info',
									);
		}
	
		return $meldinger;
	}

	/**
	 * Hook inn scripts og styles for de ulike sidene
	 *
	 * @return void
	 */
	public static function scripts_and_styles() {
		wp_enqueue_media();
		
		wp_enqueue_script('UKMnettside_script',  plugin_dir_url( __FILE__ )  . 'ukmnettside.js' );
		wp_enqueue_style( 'UKMnettside_style', plugin_dir_url( __FILE__ ) .'ukmnettside.css');
		
		wp_enqueue_script('bootstrap3_js');
		wp_enqueue_style('bootstrap3_css');
	}
}

UKMnettside::init(__DIR__);
UKMnettside::hook();