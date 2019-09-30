<?php
	
function get_posts_categories($ID){
	$cats = array();
	foreach(wp_get_post_categories($ID) as $cat){
		$cats[] = get_category($cat)->slug;
	}
	return $cats;
}
	
function get_posts_tags($ID){
	$tags = array();
	foreach(wp_get_post_tags($ID) as $tag){
		$tags[] = $tag->name;
	}
	return $tags;
}

function gist($matches){
	$url = "https://silvandiepen.site/wp-json/github/gist?id=".$matches[1]."&file=".$matches[2];

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	
	$data= json_decode($content);
	return "```".$data->language."\n".$data->content."\n```";
}

function replace_github($content){
    $regex = '/\[github gist="(.*)" file="(.*)"\]/';    
	return preg_replace_callback($regex, 'gist', $content);

}
	
function get_posts_all(){
	$posts = array();
	$i = 0;
	foreach(get_posts(array('numberposts' => 999)) as $post){
		if($post->post_status === "publish"){		
			$posts[$i]->id = $post->ID;
			$posts[$i]->title = $post->post_title;
			$posts[$i]->name = $post->post_name;
			$i++;
			
 		}
	}
	return $posts;
}
	
function get_posts_tag(){
	$posts = array();
	$i = 0;
	foreach(get_posts(array(
		'tag' => $_GET['tag'],
		'post_status' => 'publish'
    )) as $post){
		if($post->post_status === "publish"){		
			$posts[$i]->id = $post->ID;
			$posts[$i]->title = $post->post_title;
			$posts[$i]->name = $post->post_name;
// 			$posts[$i]->content = replace_github($post->post_content);
			$posts[$i]->excerpt = wpautop($post->post_excerpt);
			$posts[$i]->date = $post->post_date;
			$posts[$i]->date_modified = $post->post_modified;
			$posts[$i]->title = $post->post_title;
			$posts[$i]->status = $post->post_status;
			$posts[$i]->categories = get_posts_categories($post->ID);
			$posts[$i]->tags = get_posts_tags($post->ID);
			$posts[$i]->fields = get_fields($post->ID);
			$posts[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
 		}
	}
	return $posts;
}
function get_post_by_slug(){
	$posts = array();
	$i = 0;
	foreach(get_posts(array(
		'name' => $_GET['slug'],
		'posts_per_page' => 1,
		'post_status' => 'publish'
    )) as $post){
		if($post->post_status === "publish"){		
			$posts[$i]->id = $post->ID;
			$posts[$i]->title = $post->post_title;
			$posts[$i]->name = $post->post_name;
			$posts[$i]->content = replace_github($post->post_content);
			$posts[$i]->excerpt = wpautop($post->post_excerpt);
			$posts[$i]->date = $post->post_date;
			$posts[$i]->date_modified = $post->post_modified;
			$posts[$i]->title = $post->post_title;
			$posts[$i]->status = $post->post_status;
			$posts[$i]->categories = get_posts_categories($post->ID);
			$posts[$i]->tags = get_posts_tags($post->ID);
			$posts[$i]->fields = get_fields($post->ID);
			$posts[$i]->featured_image = get_the_post_thumbnail_url($post->ID);
			$i++;
			
 		}
	}
	return $posts[0];
}


add_action( 'rest_api_init', function () {

	register_rest_route( 'wp/v2/posts', '/all', array(
        'methods' => 'GET',
        'callback' => 'get_posts_all',
    ) );
        
	register_rest_route( 'wp/v2/posts', '/post', array(
        'methods' => 'GET',
        'callback' => 'get_post_by_slug',
    ) );
	register_rest_route( 'wp/v2/posts', '/tag', array(
        'methods' => 'GET',
        'callback' => 'get_post_by_slug',
    ) );
        
});