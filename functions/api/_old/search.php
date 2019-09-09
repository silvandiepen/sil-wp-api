<?php
	
function get_search_posts(){
	
	$posts = array();
	$query_args = array( 's' => $_GET['search'] );
	$query = new WP_Query( $query_args );
	
	$i = 0;
	foreach($query->posts as $post){
		$posts[$i]->ID = $post->ID;
		$posts[$i]->date = $post->post_date;
		$posts[$i]->excerpt = $post->post_excerpt;
		$posts[$i]->title = $post->post_title;
		$posts[$i]->slug = $post->post_name;
		$posts[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
	}	
	return $posts; 
// 	return $query->posts; 
}
	
add_action( 'rest_api_init', function () {

	register_rest_route( 'search', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_search_posts',
    ) );
        
});