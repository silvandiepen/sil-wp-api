<?php
	
	
function get_shoes_list(){
	$file = file_get_contents(dirname(__FILE__).'/shoes.json');
	return json_decode($file);
}
	

	
add_action( 'rest_api_init', function () {

	register_rest_route( 'shoes', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_shoes_list',
    ) );
});