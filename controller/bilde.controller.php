<?php
global $parent_file;

$TWIGdata = [
	'UKM_HOSTNAME' => UKM_HOSTNAME,
	'saved' => [],
];

// Hvis brukeren har trykt lagre og vi har data.
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['banner_image'] ) ) {
	if( empty( $_POST['banner_image'] ) || $_POST['banner_image'] == 'false' ) {
		delete_option('UKM_banner_image');
	} else {
		update_option('UKM_banner_image', $_POST['banner_image'] );
	}
	$TWIGdata['saved'][] = 'Lagret!';
}

$TWIGdata['image'] = get_option('UKM_banner_image');
echo TWIG('bilde.html.twig', $TWIGdata, PLUGIN_NETTSIDE_DIR );
