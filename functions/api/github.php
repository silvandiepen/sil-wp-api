<?php


	
function get_github_user_activity(){
	
	$user = $_GET['user'];
	$url = "https://api.github.com/users/".$user."/contributions";
	$url = "https://github-contributions-api.now.sh/v1/".$user;

	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return json_decode($content);
}


function get_github_gist(){
	
	$id = $_GET['id'];
	$url = "https://api.github.com/gists/".$id;
	
	$file = $_GET['file'];

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	
	if(json_decode($content)->files->$file){
		return json_decode($content)->files->$file;
	} else if(json_decode($content)) {
		return json_decode($content);
	} else {
		return null;
	}

}

function get_github_readme(){
	
	$repo = $_GET['repository'];
	$usr = $_GET['user'];
	
	return get_github_readme_data($repo,$usr);	
}

function get_github_readme_data($repo,$usr){
	$url = "https://api.github.com/repos/".$usr."/".$repo."/readme";
// 	return $url;
	
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,'silvandiepen');
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
		
	return base64_decode(json_decode($content)->content);

}


/* gets url */
/*
function get_github_repo($ID) {
	$ID = $_GET['id']
// 	return $ID;
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

function get_github_readme() {
	$ID = $_GET['id']
// 	return $ID;
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
*/




	
add_action( 'rest_api_init', function () {

	register_rest_route( 'github', '/activity', array(
        'methods' => 'GET',
        'callback' => 'get_github_user_activity',
    ) );
        
	register_rest_route( 'github', '/gist', array(
        'methods' => 'GET',
        'callback' => 'get_github_gist',
    ) );
        
	register_rest_route( 'github', '/readme', array(
        'methods' => 'GET',
        'callback' => 'get_github_readme',
    ) );
/*
        
        
	register_rest_route( 'github', '/repo', array(
        'methods' => 'GET',
        'callback' => 'get_github_repo',
    ) );
*/
        
});