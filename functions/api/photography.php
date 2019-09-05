<?php
function get_photography_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}
	
function get_photography_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}
	
	
function get_photography_all(){
	$photography = array();
	
	
	$posts = get_posts([
	  'post_type' => 'photography',
	  'post_status' => 'publish',
	  'numberposts' => -1
	  // 'order'    => 'ASC'
	]);
	$i = 0;
	foreach($posts as $post){
		if($post->post_status === "publish"){		
// 			$photography[$i] = $post;
			$photography[$i]->id = $post->ID;
			$photography[$i]->title = $post->post_title;
			$photography[$i]->name = $post->post_name;
			$photography[$i]->content = wpautop($post->post_content);
			$photography[$i]->excerpt = wpautop($post->post_excerpt);
			$photography[$i]->date = $post->post_date;
			$photography[$i]->date_modified = $post->post_modified;
			$photography[$i]->title = $post->post_title;
			$photography[$i]->status = $post->post_status;
			$photography[$i]->categories = get_photography_categories($post->ID);
			$photography[$i]->tags = get_photography_tags($post->ID);
			$photography[$i]->fields = get_fields($post->ID);
			$photography[$i]->images = get_field('photos',$post->ID);
			$photography[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
			
 		}
	}
	return $photography;
}
	

function get_photography_by_slug(){
	$package = array();	
	$args = array(
	  'post_name' => $_GET['slug'],
	  'name' => $_GET['slug'],
	  'post_type' => 'photography',
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
		$package['categories'] = get_photography_categories($post->ID);
		$package['tags'] = get_photography_tags($post->ID);
		$package['fields'] = get_fields($post->ID);
		$package['images'] = get_field('photos',$post->ID);
		$package['featured_image'] = get_the_post_thumbnail_url($post->ID);
		
	}
	return $package;

}

add_action( 'rest_api_init', function () {

	register_rest_route( 'photography', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_photography_all',
    ) );
        
	register_rest_route( 'photography', '/album', array(
        'methods' => 'GET',
        'callback' => 'get_photography_by_slug',
    ) );

        
});