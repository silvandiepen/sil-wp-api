<?php

	// Add to post type 
	add_post_type_support( 'post', 'excerpt' );
	
	
	// Theme support:
	add_theme_support( 'post-thumbnails' );

	// Disable Gutenberg
	add_filter('use_block_editor_for_post', '__return_false');


	// Include API and Custom post types
	include('functions/index.php');


function customLogin() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/admin/login.css' );
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/admin/admin.css' );
}
function customAdmin() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/admin/guyn.css' );
}
add_action( 'login_enqueue_scripts', 'customLogin' );
add_action( 'admin_enqueue_scripts', 'customAdmin' );


$guynColors = ['gray-dark','gray','gray-light','ice-dark','ice','ice-light','dark-dark','dark','dark-light','plum-dark','plum','plum-light','purple-dark','purple','purple-light','magenta-dark','magenta','magenta-light','pink-dark','pink','pink-light','red-dark','red','red-light','orange-dark','orange','orange-light','yellow-dark','yellow','yellow-light','lime-dark','lime','lime-light','green-dark','green','green-light','army-dark','army','army-light','turquoise-dark','turquoise','turquoise-light','cyan-dark','cyan','cyan-light','cloud-dark','cloud','cloud-light','skyblue-dark','skyblue','skyblue-light','blue-dark','blue','blue-light','brown-dark','brown','brown-light','beige-dark','beige','beige-light','white','black'];

add_action('admin_head', 'guyn_color_options');
function guyn_color_options() {
	global $guynColors;
	echo '<style>
	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list{
		display:flex;
		flex-wrap: wrap;
	}
	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list li,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list li,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list li,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list li{
		width: 1rem; height: 1rem;
		flex-shrink: 0;
	}
	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list li label{
		display: block; text-indent: -999em; text-align: left; position: relative;
		width: 1rem; height: 1rem;
	}

	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list li input,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list li input,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list li input,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list li input{
		border: none;
		border: 2px solid transparent;
		position: absolute; left: 0; top: 0; 
		border-radius: 4px;
		box-shadow: none;
		width: 1rem; height: 1rem;
		transform: scale(0.75);
		transition: 0.3s ease-in-out;
		box-shadow: 0 0 1rem 0 rgba(0,0,0,0);
	}
	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list li input:checked,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list li input:checked,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list li input:checked,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list li input:checked{
		transform: scale(1.25);	
		box-shadow: 0 0 1rem 0 rgba(0,0,0,0.25);
	}

	';
		
	foreach($guynColors as $i => $color) {
		$color = str_replace('-','',$color);
		echo '.acf-field[data-type="radio"][data-name="section_color"] input[value="'.$color.'"],
				.acf-field[data-type="radio"][data-name="color"] input[value="'.$color.'"],
				.acf-field[data-type="radio"][data-name="background"] input[value="'.$color.'"],
				.acf-field[data-type="radio"][data-name="page_color"] input[value="'.$color.'"]{
			background-color: var(--guyn-'.$guynColors[$i].');
		}';
	}
	
/*
	foreach($guynColors as $i => $color) {
		echo '.acf-field[data-type="radio"][data-name="section_color"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="color"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="background"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="page_color"] input[id*="-'.$guynColors[$i].'"]{
			background-color: var(--guyn-'.$guynColors[$i].');
		}';
	}
*/
	echo '</style>';
}


// ACF LAYOUT TITLE


function my_acf_flexible_content_layout_title( $title, $field, $layout, $i ) {
	
	// remove layout title from text
	$title = '';
	
	
	// load sub field image
	// note you may need to add extra CSS to the page t style these elements
	$title .= '<div class="thumbnail">';
	
	if( $image = get_sub_field('image') ) {
		
		$title .= '<img src="' . $image['sizes']['thumbnail'] . '" height="36px" />';
		
	}
	
	$title .= '</div>';
	
	
	// load text sub field
	if( $text = get_sub_field('title') ) {
		
		$title .= '<h4>' . $text . '</h4>';
		
	}
	if( $text = get_sub_field('section_id') ) {
		
		$title .= '<h4>' . $text . '</h4>';
		
	}
	
	
	// return
	return $title;
	
}

// name
add_filter('acf/fields/flexible_content/layout_title', 'my_acf_flexible_content_layout_title', 10, 4);

add_action('admin_head', 'acf_custom_title');
function acf_custom_title() {
	echo '<style>
		.acf-flexible-content .layout .acf-fc-layout-handle{ display: flex !important; }
		.acf-fc-layout-handle h4{ margin: 0 1rem; }
		
	</style>';
}

if (!headers_sent()) {
	if(isset($_SERVER['HTTP_ORIGIN'])){
		
		$http_origin = $_SERVER['HTTP_ORIGIN'];
		
		if ($http_origin == "http://localhost:3000" || $http_origin == "http://localhost:3001" || $http_origin == "https://silvandiepen.nl" || $http_origin == "https://www.silvandiepen.nl" || $http_origin == "https://staging.lenouveauchef.com"){
			header("Access-Control-Allow-Origin: $http_origin");
		}

	} 
}

?>