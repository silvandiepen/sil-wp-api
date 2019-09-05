<?php
function get_packages_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}
	
function get_packages_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}
	
	
function get_packages_all(){
	$packages = array();
	
	
	$posts = get_posts([
	  'post_type' => 'package',
	  'post_status' => 'publish',
	  'numberposts' => -1
	  // 'order'    => 'ASC'
	]);
	$i = 0;
	foreach($posts as $post){
		if($post->post_status === "publish"){		
// 			$packages[$i] = $post;
			$packages[$i]->id = $post->ID;
			$packages[$i]->title = $post->post_title;
			$packages[$i]->name = $post->post_name;
			$packages[$i]->content = wpautop($post->post_content);
			$packages[$i]->excerpt = wpautop($post->post_excerpt);
			$packages[$i]->date = $post->post_date;
			$packages[$i]->date_modified = $post->post_modified;
			$packages[$i]->title = $post->post_title;
			$packages[$i]->status = $post->post_status;
			$packages[$i]->categories = get_packages_categories($post->ID);
			$packages[$i]->tags = get_packages_tags($post->ID);
			$packages[$i]->fields = get_fields($post->ID);
			$packages[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$packages[$i]->github_repo = get_field('github_repo',$post->ID);
			$packages[$i]->github_user = get_field('github_user',$post->ID);
			$packages[$i]->github_readme = get_github_readme($post->ID);
			$packages[$i]->github_repository = get_github_repo($post->ID);
			$i++;
			
 		}
	}
	return $packages;
}
	
function get_packages_by_tag(){
	$packages = array();
	$i = 0;
	foreach(get_packages(array(
		'tag' => $_GET['tag'],
		'post_status' => 'publish'
    )) as $post){
		if($post->post_status === "publish"){		
			$packages[$i]->id = $post->ID;
			$packages[$i]->title = $post->post_title;
			$packages[$i]->name = $post->post_name;
			$packages[$i]->content = wpautop($post->post_content);
			$packages[$i]->excerpt = wpautop($post->post_excerpt);
			$packages[$i]->date = $post->post_date;
			$packages[$i]->date_modified = $post->post_modified;
			$packages[$i]->title = $post->post_title;
			$packages[$i]->status = $post->post_status;
			$packages[$i]->categories = get_packages_categories($post->ID);
			$packages[$i]->tags = get_packages_tags($post->ID);
			$packages[$i]->fields = get_fields($post->ID);
			$packages[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
 		}
	}
	return $packages;
}

/* gets url */
function get_github_repo($ID) {
	$repo = get_field('github_repo',$ID);
	$usr = get_field('github_user',$ID);
	$url = "https://api.github.com/repos/".$usr."/".$repo;
// return $url;
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return json_decode($content);
}
function get_github_readme($ID) {
	$repo = get_field('github_repo',$ID);
	$usr = get_field('github_user',$ID);
	$url = "https://api.github.com/repos/".$usr."/".$repo."/readme";
// return $url;
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return base64_decode(json_decode($content)->content);
}

function get_package_by_slug(){
	$package = array();	
	$args = array(
	  'post_name' => $_GET['slug'],
	  'name' => $_GET['slug'],
	  'post_type' => 'package',
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
		$package['categories'] = get_packages_categories($post->ID);
		$package['tags'] = get_packages_tags($post->ID);
		$package['fields'] = get_fields($post->ID);
		$package['featured_image'] = get_the_post_thumbnail_url($post->ID);
				
		$package['github']['repo'] = get_field('github_repo',$post->ID);
		$package['github']['user'] = get_field('github_user',$post->ID);
		$package['github']['readme'] = get_github_readme($post->ID);
		$package['github']['repository'] = get_github_repo($post->ID);
		
	}
	return $package;

}

add_action( 'rest_api_init', function () {

	register_rest_route( 'packages', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_packages_all',
    ) );
        

	register_rest_route( 'packages', '/package', array(
        'methods' => 'GET',
        'callback' => 'get_package_by_slug',
    ) );
    	register_rest_route( 'packages', '/tag', array(
        'methods' => 'GET',
        'callback' => 'get_post_by_slug',
    ) );

        
});