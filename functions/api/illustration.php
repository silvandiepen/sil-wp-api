<?php
function get_illustration_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}
	
function get_illustration_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}
	
	
function get_illustration_all(){
	$illustration = array();
	
	
	$posts = get_posts([
	  'post_type' => 'illustration',
	  'post_status' => 'publish',
	  'numberposts' => -1
	  // 'order'    => 'ASC'
	]);
	$i = 0;
	foreach($posts as $post){
		if($post->post_status === "publish"){		
// 			$illustration[$i] = $post;
			$illustration[$i]->id = $post->ID;
			$illustration[$i]->title = $post->post_title;
			$illustration[$i]->name = $post->post_name;
			$illustration[$i]->content = wpautop($post->post_content);
			$illustration[$i]->excerpt = wpautop($post->post_excerpt);
			$illustration[$i]->date = $post->post_date;
			$illustration[$i]->date_modified = $post->post_modified;
			$illustration[$i]->title = $post->post_title;
			$illustration[$i]->status = $post->post_status;
			$illustration[$i]->categories = get_illustration_categories($post->ID);
			$illustration[$i]->tags = get_illustration_tags($post->ID);
			$illustration[$i]->fields = get_fields($post->ID);
			$illustration[$i]->images = get_field('illustrations',$post->ID);
			$illustration[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
			
 		}
	}
	return $illustration;
}
	

function get_illustration_by_slug(){
	$package = array();	
	$args = array(
	  'post_name' => $_GET['slug'],
	  'name' => $_GET['slug'],
	  'post_type' => 'illustration',
	  'post_status' => 'publish'
	);
	$post = get_posts($args)[0];
	
	if($post->post_status === "publish"){	
		$package['id'] = $post->ID;
		$package['title'] = $post->post_title;
		$package['name'] = $post->post_name;

		$package['content'] = wpautop($post->post_content);
		$package['excerpt'] = wpautop($post->post_excerpt);
		$package['date'] = $post->post_date;
		$package['date_modified'] = $post->post_modified;
		$package['title'] = $post->post_title;
		$package['status'] = $post->post_status;
		$package['categories'] = get_illustration_categories($post->ID);
		$package['tags'] = get_illustration_tags($post->ID);
		$package['fields'] = get_fields($post->ID);
		$package['images'] = get_field('illustrations',$post->ID);
		$package['featured_image'] = get_the_post_thumbnail_url($post->ID);
		
	}
	return $package;

}

add_action( 'rest_api_init', function () {

	register_rest_route( 'illustration', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_illustration_all',
    ) );
        
	register_rest_route( 'illustration', '/set', array(
        'methods' => 'GET',
        'callback' => 'get_illustration_by_slug',
    ) );

        
});