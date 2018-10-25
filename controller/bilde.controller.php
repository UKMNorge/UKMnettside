<?php
require_once('UKM/monstring.class.php');
global $parent_file;

$TWIGdata = [
	'UKM_HOSTNAME' => UKM_HOSTNAME,
	'saved' => [],
];

// Hvis brukeren har trykt lagre og vi har data.
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['banner_image'] ) ) {
	if( empty( $_POST['banner_image'] ) || $_POST['banner_image'] == 'false' ) {
		delete_option('UKM_banner_image');
		delete_option('UKM_banner_image_large');
	} else {
		update_option('UKM_banner_image', $_POST['banner_image'] );
		update_option('UKM_banner_image_position_y', $_POST['banner_image_position_y'] );
		if( isset( $_POST['banner_image_id'] ) && !empty( $_POST['banner_image_id'] ) ) {
			$wp_image = wp_get_attachment_metadata( $_POST['banner_image_id'] );
			if( isset( $wp_image['sizes']['forsidebilde'] ) ) {
				$override = wp_get_attachment_image_src( $_POST['banner_image_id'], 'forsidebilde');
				if( is_array( $override ) && is_string( $override[0] ) && !empty( $override[0] ) ) {
					update_option('UKM_banner_image_large', $override[0]);
				}
			}
		}
	}
	$TWIGdata['saved'][] = 'Lagret!';
}

$TWIGdata['image'] = get_option('UKM_banner_image');
$TWIGdata['image_position_y'] = get_option('UKM_banner_image_position_y') == false ? 'top' : get_option('UKM_banner_image_position_y');
$TWIGdata['image_id'] = null;
$TWIGdata['monstring'] = new monstring_v2( get_option('pl_id') );
echo TWIG('bilde.html.twig', $TWIGdata, PLUGIN_NETTSIDE_DIR );
