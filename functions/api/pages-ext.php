<?php

function get_pages_categories($ID) {
	$cats = array();
	foreach (wp_get_post_categories($ID) as $cat) {
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}

function get_template_name($ID) {
	$slug = get_page_template_slug($ID);
	$slug = str_replace(".php", "", $slug);
	$slug = str_replace("page-", "", $slug);
	if (empty($slug)) {
		$slug = 'default';
	}
	return $slug;
}

function get_pages_tags($ID) {
	$tags = array();
	foreach (wp_get_post_tags($ID) as $tag) {
		$tags[] = $tag->name;
	}
	return $tags;
}


function get_pages_children($ID) {
	$children =  array();
	$args = array(
		'post_parent' => $ID,
		'post_type'   => 'page',
		'numberposts' => -1,
		'post_status' => 'publish'
	);

	foreach (get_children($args) as $value) {
		$children[] = array(
			'id' => $value->ID,
			'slug' => $value->post_name,
			'title' => $value->post_title,
			'date' => $value->post_date,
			'excerpt' => $value->post_excerpt,
			'uri' => get_page_uri($value->ID),
			'featured_image' => get_the_post_thumbnail_url($value->ID)
		);
	}
	return $children;
}

function get_all_image_sizes($attachment_id = 0) {
    $sizes = get_intermediate_image_sizes();
    if(!$attachment_id) $attachment_id = get_post_thumbnail_id();

    $images = array();
    foreach ( $sizes as $size ) {
        $images[] = wp_get_attachment_image_src( $attachment_id, $size );
    }

    return $images;
}


// add endpoint wich returns page based on uri/path
add_action('rest_api_init', function () {
	register_rest_route('wp/v2/pages', '/path', array(
		'methods' => 'GET',
		'callback' => 'get_page_path',
	));
});
// add endpoint wich returns page based on uri/path
add_action('rest_api_init', function () {
	register_rest_route('pages', '/path', array(
		'methods' => 'GET',
		'callback' => 'get_page_path',
	));
});




function get_extra_fields($ID){
	$fields = get_fields($ID);
	$i = 0;
	foreach ($fields as $key => $value) {
		if($key == 'package_name'){
			$usr = 'silvandiepen';
			if(!empty($fields['github_user'])) { 
				$usr = $fields['github_user'];
	   		}
	   		$fields['package_readme'] = get_github_readme_data($fields['github_repo'],$usr);
   		}   		
	}
	
	return $fields;
}


function get_page_path() {
	// Create request from pages endpoint by frontpage id.
	$request  = new \WP_REST_Request( 'GET', '/wp/v2/pages/'.url_to_postid($_GET['path']));

	// Parse request to get data.
	$api_response = rest_do_request( $request );

	return $api_response->get_data();
}

