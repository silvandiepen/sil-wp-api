<?php

function get_menu_item_children($items, $parent = 0){
	$newitems = [];
	foreach ($items as $key => $value){

		if($value->parent == $parent){
			$value->children = get_menu_item_children($items, $value->ID);	

			$newitems[] = $value;	
		}

	}
	if(count($newitems) < 1){
		return false;	
	} else {
		return $newitems; 
	}
}


function get_nav_menu_items_by_location( $location, $args = [] ) {
 
    // Get all locations
    $locations = get_nav_menu_locations();


    // Get object id by location
    $object = wp_get_nav_menu_object( $locations[$location] );
 
    // Get menu items by menu name
    $menu_items = wp_get_nav_menu_items( $object->name, $args );
    
    $items = [];
    
    foreach ($menu_items as $key => $value){
	    $item = new stdClass();
	    $item->ID = (int)$value->ID;
	    $item->title = $value->post_title;
	    $item->name = $value->post_name;
		$item->link = $value->url;
		$item->classes = count($value->classes)>1 ? $value->classes : false;
		$item->parent = (int)$value->menu_item_parent;
		$item->order = $value->menu_order;
		$item->fields = get_fields($value->ID);
	    $items[] = $item;
    }
 
    // Return menu post objects
    return get_menu_item_children($items);
}


function get_menus(){
	$registered_menus = get_registered_nav_menus();

	$menus = [];	

	foreach($registered_menus as $key => $value) {
		$menu = new stdClass();
		$menu->name = $key;
		$menu->title = $value;
		$menu->items = get_nav_menu_items_by_location($key);

	    $menus[] = $menu; 
	}
	
	return $menus;
}

function get_single_menu(){
	return get_nav_menu_items_by_location($_GET['menu']);
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'navigation', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_menus',
    ) );	
    register_rest_route( 'navigation', '/menu', array(
        'methods' => 'GET',
        'callback' => 'get_single_menu',
    ) );
              
});