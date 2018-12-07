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
        
        $register = register_rest_route(
            'UKM',
            '/post/(?P<id>\d+)',
            [
                'methods' => 'GET',
                'callback' => 'UKMnettside_api_post',
                'args' => ['id']
            ]
        );

        $register = register_rest_route(
            'UKM',
            '/content/(?P<id>\d+)',
            [
                'methods' => 'GET',
                'callback' => 'UKMnettside_api_content',
                'args' => ['id']
            ]
        );
	}
);