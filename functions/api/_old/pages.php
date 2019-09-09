<?php
	
	
	
	
	
function get_pages_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}

function get_template_name($ID){
	$slug = get_page_template_slug($ID);
	$slug = str_replace(".php","",$slug);
	$slug = str_replace("page-","",$slug);
	if(empty($slug)){
		$slug = 'default';
	}
	return $slug;
}
	
function get_pages_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}
	
	
function get_pages_all(){
	$pages = array();
	$i = 0;
	foreach(get_pages(array('numberpages' => 999)) as $post){
		if($post->post_status === "publish"){		
// 			$pages[$i] = $post;
			$pages[$i]->id = $post->ID;
			$pages[$i]->title = $post->post_title;
			$pages[$i]->name = $post->post_name;
			$pages[$i]->uri = get_page_uri($post->ID);
			$pages[$i]->content = wpautop($post->post_content);
			$pages[$i]->excerpt = wpautop($post->post_excerpt);
			$pages[$i]->date = $post->post_date;
			$pages[$i]->date_modified = $post->post_modified;
			$pages[$i]->title = $post->post_title;
			$pages[$i]->status = $post->post_status;
			$pages[$i]->categories = get_pages_categories($post->ID);
			$pages[$i]->tags = get_pages_tags($post->ID);
			$pages[$i]->template = get_template_name($post->ID);
			$pages[$i]->fields = get_fields($post->ID);
			$pages[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
			
 		}
	}
	return $pages;
}
	
function get_pages_tag(){
	$pages = array();
	$i = 0;
	foreach(get_pages(array(
		'tag' => $_GET['tag'],
		'post_status' => 'publish'
    )) as $post){
		if($post->post_status === "publish"){		
			$pages[$i]->id = $post->ID;
			$pages[$i]->title = $post->post_title;
			$pages[$i]->name = $post->post_name;
			$pages[$i]->uri = get_page_uri($post->ID);
			$pages[$i]->content = wpautop($post->post_content);
			$pages[$i]->excerpt = wpautop($post->post_excerpt);
			$pages[$i]->date = $post->post_date;
			$pages[$i]->date_modified = $post->post_modified;
			$pages[$i]->title = $post->post_title;
			$pages[$i]->status = $post->post_status;
			$pages[$i]->categories = get_pages_categories($post->ID);
			$pages[$i]->tags = get_pages_tags($post->ID);
			$pages[$i]->template = get_template_name($post->ID);			
			$pages[$i]->fields = get_extra_fields($post->ID);
			$pages[$i]->children = get_pages_children($post->ID);
			$pages[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
 		}
	}
	return $pages;
}
function get_page_slug(){
	$page = array();
	$post = get_page_by_path($_GET['slug']);
	
	if($post->post_status === "publish"){	

		
		$page['id'] = $post->ID;
		$page['title'] = $post->post_title;
		$page['name'] = $post->post_name;
		$page['uri'] = get_page_uri($post->ID);
		$page['content'] = wpautop($post->post_content);
		$page['excerpt'] = wpautop($post->post_excerpt);
		$page['date'] = $post->post_date;
		$page['date_modified'] = $post->post_modified;
		$page['title'] = $post->post_title;
		$page['status'] = $post->post_status;
		$page['categories'] = get_pages_categories($post->ID);
		$page['tags'] = get_pages_tags($post->ID);
		$page['template'] = get_template_name($post->ID);
		$page['fields'] = get_extra_fields($post->ID);
		$page['children'] = get_pages_children($post->ID);
		$page['featured_image'] = get_the_post_thumbnail_url($post->ID);
	}
	return $page;
}
function get_page_path(){	
	$post = get_page_by_path($_GET['path']);
		
	if($post->post_status === "publish"){	
		$page['id'] = $post->ID;
		$page['title'] = $post->post_title;
		$page['name'] = $post->post_name;
		$page['uri'] = get_page_uri($post->ID);
		$page['content'] = wpautop($post->post_content);
		$page['excerpt'] = wpautop($post->post_excerpt);
		$page['date'] = $post->post_date;
		$page['date_modified'] = $post->post_modified;
		$page['title'] = $post->post_title;
		$page['status'] = $post->post_status;
		$page['categories'] = get_pages_categories($post->ID);
		$page['tags'] = get_pages_tags($post->ID);
		$page['template'] = get_template_name($post->ID);
		$page['fields'] = get_extra_fields($post->ID);
		$page['children'] = get_pages_children($post->ID);
		$page['featured_image'] = get_the_post_thumbnail_url($post->ID);
	}
	return $page;
}

function get_pages_children($ID){
	$children =  array();
	$args = array(
		'post_parent' => $ID,
		'post_type'   => 'any', 
		'numberposts' => -1,
		'post_status' => 'any' 
	);
	$children_array = get_children( $args );
	
	if(count($children_array) == 0){
		$children_array = (get_children(wp_get_post_parent_id($ID)));
	}
	
	
	foreach($children_array as &$value){
		if($value->post_type !== 'attachment'){
			$child = new stdClass();

			$child->ID = $value->ID;
			$child->name = $value->post_name;
			$child->title = $value->post_title;
			$child->date = $value->post_date;
			$child->excerpt = $value->post_excerpt;
			$child->uri = get_page_uri($value->ID);
			$child->featured_image = get_the_post_thumbnail_url($value->ID);			

			$children[] = $child;		
		}
	}
	
	
	return $children;
}


function get_extra_fields($ID){
	$fields = get_fields($ID);
		
	foreach ($fields['sections'] as &$value) {
   		if($value['package']){
	   		$value['package']['readme'] = get_github_readme_data($value['package']['package_repo'],$value['package']['package_user']);
   		}   		
	}
	
	return $fields;
}

add_action( 'rest_api_init', function () {

	register_rest_route( 'pages', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_pages_all',
    ) );
        

	register_rest_route( 'pages', '/page', array(
        'methods' => 'GET',
        'callback' => 'get_page_slug',
    ) );	
    register_rest_route( 'pages', '/path', array(
        'methods' => 'GET',
        'callback' => 'get_page_path',
    ) );
    	register_rest_route( 'pages', '/tag', array(
        'methods' => 'GET',
        'callback' => 'get_post_by_slug',
    ) );
        
});