<?php

require_once('nettside.php');

add_action( 
	'rest_api_init', 
	function () {
		$register = register_rest_route(
			'UKM', 
			'/informasjon/', 
			[
				'methods' => 'GET',
				'callback' => 'UKMnettside_api',
				'args' => []
			]
		);
	}
);