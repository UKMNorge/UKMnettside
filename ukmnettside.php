<?php  
/* 
Plugin Name: UKM Nettside
Plugin URI: http://www.ukm-norge.no
Description: Styrer forsiden/nettsiden til lokal- eller fylkesmønstringen
Author: UKM Norge / M Mandal 
Version: 2.0 
Author URI: http://www.ukm-norge.no
*/

use UKMNorge\Wordpress\Modul;

require_once('UKM/Autoloader.php');

class UKMnettside extends Modul
{
	public static $action = 'forside';
	public static $path_plugin = null;

	public static function hook()
	{
		add_action( 'init', ['UKMnettside','registrer_forsidemeny'] );
		add_filter('UKMWPDASH_messages', ['UKMnettside','meldinger']);	
        add_action('admin_menu', ['UKMnettside','meny'], 1);
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
    
    public static function renderForside() {
        static::setAction('forside');
        static::renderAdmin();
    }

	/**
	 * Admin-meny
	 *
	 * @return void
	 */
	public static function meny() {

        ## OBS: UKMDesign registrerer også ett menyelement her som kaller UKMnettside::renderForside()
        add_action(
            'admin_print_styles-' . add_submenu_page(
                'edit.php',
                'Nettside',
                'Nettside',
                'edit_posts',
                'UKMnettside',
                ['UKMnettside', 'renderAdmin']
            ),
            ['UKMnettside', 'scripts_and_styles']
        );
        
        if( in_array( get_option('site_type'), ['arrangement'] ) ) {
            // Meny
            $page_meny = add_submenu_page(
                'edit.php',
                'Meny',
                'Meny',
                'edit_posts',
                'nav-menus.php'
            );
        }
        
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
									'link' 		=> 'edit.php?page=UKMnettside&action=infoside',
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
        wp_enqueue_editor();
		
		wp_enqueue_script('UKMnettside_script',  PLUGIN_PATH .'UKMnettside/ukmnettside.js',[ 'wp-color-picker']);
        wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'UKMnettside_style', PLUGIN_PATH .'UKMnettside/ukmnettside.css');
		
		wp_enqueue_script('WPbootstrap3_js');
		wp_enqueue_style('WPbootstrap3_css');
	}
}

UKMnettside::init(__DIR__);
UKMnettside::hook();
