<?php
/**
* Oppretter forside-editor-siden. 
* Noted issues:
* - For å starte å skrive må man trykke på den ene tomme linjen, ikke det andre blanket området  - uten at det finnes en blinkende karet som hjelper deg med å se den.
* 
*/

$monstring = new monstring_v2(get_option('pl_id'));
$forside = get_page_by_path('info');

UKMnettside::addViewData('UKM_HOSTNAME',UKM_HOSTNAME);
UKMnettside::addViewData('monstringsLink', $monstring->getLink());

$content = null;
if( null != $forside ) {
	$content = $forside->post_content;
}

// Hvis brukeren har trykt "Slett" eller "Lagre" etter å ha fjernet alt.
if ("POST" == $_SERVER['REQUEST_METHOD']) {
	if( isset( $_POST['deletePage'] ) || empty($_POST['forside_editor'] ) ) {
		// Dersom editoren er tom, men vi har en side - slett siden. Skip trash.
		$deleted = wp_delete_post($forside->ID, true);
		if( !$deleted ) {
			UKMnettside::addViewData(
				"errors",
				"Klarte ikke å slette forside-innholdet! Kontakt support"
			);
		} else {
			UKMnettside::addViewData(
				"saved",
				"Fjernet innholdet."
			);
		}
		$content = null;
	}
}

// Hvis brukeren har trykt lagre og vi har data.
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['forside_editor'] ) && !empty($_POST['forside_editor']) ) {
	// Hvis vi har sendt inn data, vis det i editoren uavhengig om vi får til å lagre det eller ikke, sånn at folk  slipper å miste endringer.
	$content = $_POST['forside_editor'];
	$new_content = array
		(
			'post_title' => "Forside", # Tittel på siden
			'post_name' => 'info', # SLUG
			'post_type' => 'page',
			'post_content' =>  $_POST['forside_editor'],
			'post_status' => 'publish'
		);
	
	// Hvis vi ikke har en forside fra før og det som er forsøkt lagret faktisk har tekst
	if( NULL == $forside && !empty($_POST['forside_editor']) ) {
		// Ingen informasjon er lagret tidligere, opprett siden.
		$front = wp_insert_post($new_content, true);
		if( null == $front || is_wp_error($front) ){
			UKMnettside::addViewData(
				'errors', 
				is_wp_error($front) ? 
					"Klarte ikke å lagre innhold som ny side! Feilmelding: ". 
					$front->get_error_message($code) : 
					"Klarte ikke å lagre innhold som ny side!"
			);
		} else {
			UKMnettside::addViewData(
				'saved',
				"Opprettet ny informasjonsside!"
			);
		}
	} else {
		// Eller oppdater den vi har fra før om det er sendt inn data og vi faktisk har en side å lagre til
		$new_content['ID'] = $forside->ID;
		$front = wp_update_post($new_content, true);
		
		if( null == $front || is_wp_error($front) ) {
			UKMnettside::addViewData(
				'errors',
				is_wp_error($front) ? 
					"Klarte ikke å lagre oppdatert innhold! Feilmelding: ". 
					$front->get_error_message($code) : 
					"Klarte ikke å oppdatere forsiden!"
			);
		} else {
			UKMnettside::addViewData(
				'saved',
				"Oppdaterte informasjonsside din!"
			);
		}
	}
	
	update_option('UKMnettside_info_last_updated', time());

	// Last inn innholdet på nytt dersom lagring funka - for å laste inn bilder skikkelig.
	
	if ( empty( UKMnettside::getViewData()['errors'] ) ) {
		$forside = get_page_by_path('info');
		$content = $forside->post_content;
	}
}

UKMnettside::addViewData('content', !empty($content));

echo TWIG(
	'forside_pre_editor.html.twig', 
	UKMnettside::getViewData(), 
	UKMnettside::getPluginPath()
);
wp_editor($content, 'forside_editor', $settings = array() );
echo TWIG(
	'forside_post_editor.html.twig',
	UKMnettside::getViewData(),
	UKMnettside::getPluginPath()
);
?>