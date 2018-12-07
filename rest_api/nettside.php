<?php

function UKMnettside_api() {	
	if( !get_option('UKM_menu') ) {
		return 'false';
	}

	$menu = wp_get_nav_menu_object( get_option('UKM_menu') );	
	$menu_items = wp_get_nav_menu_items( $menu );
	
	$items = [];
	foreach( $menu_items as $menu_item ) {
		$item = new stdClass();
		$item->id   		= $menu_item->object_id;
		$item->title     	= $menu_item->title;
		$item->lead 	    = 'Ingress kommer her etter hvert';
        $item->url  		= $menu_item->url;
        $item->image        = 'https://placehold.it/300x169/';
        $item->contenturl   = 'https://ukm.no/testfylke/wp-json/UKM/content/'. $menu_item->object_id;
        
        $items[] = $item;
	}
	return $items;
}

function UKMnettside_api_post( $id ) {
    $post = get_post( $id );
	$post_id = $post->ID;
	setup_postdata($post);
    $wpoop = new WPOO_Post( $post );

    $item = new stdClass();
    $item->id   		= $wpoop->ID;
    $item->date         = $wpoop->date;
    $item->title     	= $wpoop->title;
    $item->lead 	    = $wpoop->lead;
    $item->url  		= $wpoop->url;
    $item->image        = $wpoop->image->url;//'https://placehold.it/300x169/';
    $item->contenturl   = 'https://ukm.no/testfylke/wp-json/UKM/content/'. $menu_item->ID;
}

function UKMnettside_api_content( $id ) {
    $post = get_post( $id );
	$post_id = $post->ID;
	setup_postdata($post);
    $wpoop = new WPOO_Post( $post );

    $data = new stdClass();
    $data->id           = $wpoop->ID;
    $data->content      = $wpoop->content_wo_lead;//
    
    return $data;
}