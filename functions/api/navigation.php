<?php

function get_navbar_items($items, $parent = 0) {
	
	// Define a menu group
	$menu = [];

	// Get all items for this menu
	$i = 0;
	
	// Go through the items and add them, start with 0 and go through all children.
	foreach($items as $item) {
		if($item->menu_item_parent == $parent){
			$a = new stdClass();
			$a->title = $item->title;
			$a->url = $item->url;
			$a->name = $item->post_name;
			$a->parent = (int)$item->menu_item_parent;
			$a->order = (int)$item->menu_order;
			if($item->classes[0] !== ""){
				$a->classes = $item->classes;
			}
// 			$a->all = $item;
			
			// Get the children by calling this same function. 
// 			$a->children = get_navbar_items($items, $item->menu_item_parent, true);
			$menu[] = $a;
		}
	}
	
	// Return the menu
	return $menu;
}

function get_all_nav() {   
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
	
	$all_menus = [];
	
	
	foreach ($menus as $item) {
		
		$menu = new stdClass();
		$menu->name = $item->slug;
		$menu->menu = get_navbar_items(wp_get_nav_menu_items($item->slug));
		$all_menus[] = $menu;
	}
    
    return $all_menus;

}

add_action( 'rest_api_init', function () {

	register_rest_route( 'navigation', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_all_nav',
    ) );
              
});