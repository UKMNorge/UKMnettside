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
		$item->id 		= $menu_item->ID;
		$item->title 	= $menu_item->title;
		$item->lead 	= 'Ingress kommer her etter hvert';
        $item->url		= $menu_item->url;
        $item->image    = 'https://placehold.it/300x169/';
        $item->contenturl = $menu_item->url .'?exportContent=true';
        
        $items[] = $item;
	}
	return $items;
}