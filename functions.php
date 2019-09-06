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


$guynColors = ['graydark','gray','graylight','icedark','ice','icelight','darkdark','dark','darklight','plumdark','plum','plumlight','purpledark','purple','purplelight','magentadark','magenta','magentalight','pinkdark','pink','pinklight','reddark','red','redlight','orangedark','orange','orangelight','yellowdark','yellow','yellowlight','limedark','lime','limelight','greendark','green','greenlight','armydark','army','armylight','turquoisedark','turquoise','turquoiselight','cyandark','cyan','cyanlight','clouddark','cloud','cloudlight','skybluedark','skyblue','skybluelight','bluedark','blue','bluelight','browndark','brown','brownlight','beigedark','beige','beigelight','white','black'];

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
		width: 2rem; height: 2rem;
		flex-shrink: 0;
		margin: 4px;
	}
	.acf-field[data-type="radio"][data-name="section_color"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="color"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="background"] .acf-radio-list li label,
	.acf-field[data-type="radio"][data-name="page_color"] .acf-radio-list li label{
		display: block; text-indent: -999em; text-align: left; position: relative;
		width: 2rem; height: 2rem;
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
		width: 2rem; height: 2rem;
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
		echo '.acf-field[data-type="radio"][data-name="section_color"] input[id^="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="section_color"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="color"] input[id^="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="color"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="background"] input[id^="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="background"] input[id*="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="page_color"] input[id^="-'.$guynColors[$i].'"],
				.acf-field[data-type="radio"][data-name="page_color"] input[id*="-'.$guynColors[$i].'"]{
			background-color: var(--guyn-'.$guynColors[$i].');
		}';
	}
	echo '</style>';
}