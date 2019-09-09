<?php
/*
	
	$fields = new stdClass();
	$fields = {
		'uri':  			'get_page_uri',
		'children':			'get_pages_children',
		'siblings': 		['get_pages_children','wp_get_post_parent_id'],
		'title':			[['title']['rendered']],
		'content':			[['content']['rendered']],
		'guid': 			[['guid']['rendered']],
		'excerpt':			[['excerpt']['rendered']],
		'featured_image':	'get_image',
		'tags':	 			'get_image',
		'categories':	 	'get_pages_categories',
		'tags':	 			'get_pages_tags',
		'template':	 		'get_template_name'
	};
	
*/
register_rest_field( 'page',
	'uri',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_page_uri($array['id']);
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'children',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_pages_children($array['id']);
		},
		'update_callback' => null,
		'schema'          => null,
	)
);


register_rest_field( 'page',
	'siblings',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_pages_children(wp_get_post_parent_id($array['id']));
			
		},
		'update_callback' => null,
		'schema'          => null,
	)
);


register_rest_field( 'page',
	'title',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return $array['title']['rendered'];
			
		},
		'update_callback' => null,
		'schema'          => null,
	)
);


register_rest_field( 'page',
	'content',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return $array['content']['rendered'];
			
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'guid',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return $array['guid']['rendered'];
			
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'excerpt',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return $array['excerpt']['rendered'];
			
		},
		'update_callback' => null,
		'schema'          => null,
	)
);


register_rest_field( 'page',
	'featured_image',
	array(
		'get_callback'    => function($array, $attr, $request){ 
// 			return get_the_post_thumbnail($array['id']);
			return get_image($array['id']);
// 			return $array['id']
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'categories',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_pages_categories($array['id']);
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'tags',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_pages_tags($array['id']);
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

register_rest_field( 'page',
	'template',
	array(
		'get_callback'    => function($array, $attr, $request){ 
			return get_template_name($array['id']);
		},
		'update_callback' => null,
		'schema'          => null,
	)
);

// add endpoint wich returns page based on uri/path
add_action('rest_api_init', function () {
	register_rest_route('wp/v2/pages', '/path', array(
		'methods' => 'GET',
		'callback' => 'get_page_path',
	));
});
