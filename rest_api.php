<?php

add_action( 'rest_api_init', ['UKMwpAPI', 'registerEndpoints'] );
    
class UKMwpAPI {

    public static function registerEndpoints() {
        /**
         * ENDPOINT 3: Sider i informasjonsmenyen
         */
        $register = register_rest_route(
			'UKM', 
			'/informasjon/', 
			[
				'methods' => 'GET',
				'callback' => ['UKMwpAPI', 'informasjonsMeny'],
				'args' => []
			]
        );

        /**
         * ENDPOINT 1 Info om en gitt post
         */
        $register = register_rest_route(
            'UKM',
            '/post/(?P<id>\d+)',
            [
                'methods' => 'GET',
				'callback' => ['UKMwpAPI', 'post'],
                'args' => ['id']
            ]
        );

        /**
         * ENDPOINT 2: Innholdet i en post
         */
        $register = register_rest_route(
            'UKM',
            '/content/(?P<id>\d+)',
            [
                'methods' => 'GET',
				'callback' => ['UKMwpAPI', 'postContent'],
                'args' => ['id']
            ]
        );
    }


    /**
     * ENDPOINT 1: Info om en gitt post
     * /UKM/post/$id
     */
    public static function post( $request ) {
        $postData = self::_getPostFromId( $request->get_param('id') );

        $data = new stdClass();
        $data->id           = $postData->ID;
        $data->date         = $postData->date;
        $data->title        = $postData->title;
        $data->lead         = $postData->lead;
        $data->url          = $postData->url;
        $data->image        = $postData->image->url;//'https://placehold.it/300x169/';
        $data->contenturl   = 'https://ukm.no/testfylke/wp-json/UKM/content/'. $item->id;

        return $data;
    }

    /**
     * ENDPOINT 2: Innholdet i en post
     * /UKM/content/$id
     */
    public static function postContent( $request ) {
        $postData = self::_getPostFromId( $request->get_param('id') );

        $data = new stdClass();
        $data->id       = $postData->ID;
        $data->lead     = $postData->lead;
        $data->content  = $postData->content_wo_lead;
        
        return $data;
    }

    /**
     * ENDPOINT 3: Sider i informasjonsmenyen
     * /UKM/informasjon
     */
    public static function informasjonsMeny() {
        if( !get_option('UKM_menu') ) {
            return 'false';
        }

        $menu = wp_get_nav_menu_object( get_option('UKM_menu') );
        $menu_items = wp_get_nav_menu_items( $menu );

        $items = [];
        foreach( $menu_items as $menu_item ) {
            $item = new stdClass();
            $item->id           = $menu_item->object_id;
            $item->title        = $menu_item->title;
            $item->lead         = 'Ingress kommer her etter hvert';
            $item->url          = $menu_item->url;
            $item->image        = 'https://placehold.it/300x169/';
            $item->contenturl   = 'https://ukm.no/testfylke/wp-json/UKM/content/'. $menu_item->object_id;

            $items[] = $item;
        }

        return $items;
    }

    /**
     * HELPER: Hent gitt post fra ID
     */
    public static function _getPostFromId( $post_id ) {
        $post = get_post( $post_id );
        setup_postdata( $post );
        return new WPOO_Post( $post );
    }
}