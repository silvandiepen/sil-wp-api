<?php
function get_projects_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}
	
function get_projects_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}
	
	
function get_projects_all(){
	$projects = array();
	
	
	$posts = get_posts([
	  'post_type' => 'project',
	  'post_status' => 'publish',
	  'numberposts' => -1
	  // 'order'    => 'ASC'
	]);
	$i = 0;
	foreach($posts as $post){
		if($post->post_status === "publish"){		
// 			$projects[$i] = $post;
			$projects[$i]->id = $post->ID;
			$projects[$i]->title = $post->post_title;
			$projects[$i]->name = $post->post_name;
			$projects[$i]->content = wpautop($post->post_content);
			$projects[$i]->excerpt = wpautop($post->post_excerpt);
			$projects[$i]->date = $post->post_date;
			$projects[$i]->date_modified = $post->post_modified;
			$projects[$i]->title = $post->post_title;
			$projects[$i]->status = $post->post_status;
			$projects[$i]->categories = get_projects_categories($post->ID);
			$projects[$i]->tags = get_projects_tags($post->ID);
			$projects[$i]->fields = get_fields($post->ID);
			$projects[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
			
 		}
	}
	return $projects;
}
	
function get_projects_by_tag(){
	$projects = array();
	$i = 0;
	foreach(get_projects(array(
		'tag' => $_GET['tag'],
		'post_status' => 'publish'
    )) as $post){
		if($post->post_status === "publish"){		
			$projects[$i]->id = $post->ID;
			$projects[$i]->title = $post->post_title;
			$projects[$i]->name = $post->post_name;
			$projects[$i]->content = wpautop($post->post_content);
			$projects[$i]->excerpt = wpautop($post->post_excerpt);
			$projects[$i]->date = $post->post_date;
			$projects[$i]->date_modified = $post->post_modified;
			$projects[$i]->title = $post->post_title;
			$projects[$i]->status = $post->post_status;
			$projects[$i]->categories = get_projects_categories($post->ID);
			$projects[$i]->tags = get_projects_tags($post->ID);
			$projects[$i]->fields = get_fields($post->ID);
			$projects[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
 		}
	}
	return $projects;
}
function get_project_by_slug(){
	$project = array();	
	$args = array(
	  'post_name' => $_GET['slug'],
	  'name' => $_GET['slug'],
	  'post_type' => 'project',
	  'post_status' => 'publish'
	);
	$post = get_posts($args)[0];

	if($post->post_status === "publish"){	
		$project['id'] = $post->ID;
		$project['title'] = $post->post_title;
		$project['name'] = $post->post_name;

		$project['content'] = wpautop($post->post_content);
		$project['excerpt'] = wpautop($post->post_excerpt);
		$project['date'] = $post->post_date;
		$project['date_modified'] = $post->post_modified;
		$project['title'] = $post->post_title;
		$project['status'] = $post->post_status;
		$project['categories'] = get_projects_categories($post->ID);
		$project['tags'] = get_projects_tags($post->ID);
		$project['fields'] = get_fields($post->ID);
		$project['featured_image'] = get_the_post_thumbnail_url($post->ID);
	}
	return $project;

}

add_action( 'rest_api_init', function () {

	register_rest_route( 'projects', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_projects_all',
    ) );
        

	register_rest_route( 'projects', '/project', array(
        'methods' => 'GET',
        'callback' => 'get_project_by_slug',
    ) );
    	register_rest_route( 'projects', '/tag', array(
        'methods' => 'GET',
        'callback' => 'get_post_by_slug',
    ) );

        
});