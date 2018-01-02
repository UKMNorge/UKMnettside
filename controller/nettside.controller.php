<?php

$saved = [];
	
// Hvis brukeren har trykt lagre og vi har data.
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['selected_menu'] ) ) {
	if( empty( $_POST['selected_menu'] ) || $_POST['selected_menu'] == 'false' ) {
		delete_option('UKM_menu');
	} else {
		update_option('UKM_menu', $_POST['selected_menu'] );
	}
	$saved = ['Lagret!'];
}

$TWIGdata = [
	'har_ekstra_meny'	=> get_option('spesial_meny'),
	'menyer' 			=> get_terms( 'nav_menu', array( 'hide_empty' => true ) ),
	'valgt_meny'		=> get_option('UKM_menu'),
	'saved'				=> $saved,
];

echo TWIG('nettside.html.twig', $TWIGdata, PLUGIN_NETTSIDE_DIR );
