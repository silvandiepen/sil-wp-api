<?php
/*
		
function cache_pages( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'pages/all' ] ) || ! in_array( 'pages', $allowed_endpoints[ 'pages/all' ] ) ) {
        $allowed_endpoints[ 'pages/all' ][] = 'pages';
    }
    return $allowed_endpoints;
}
*/
function cache_pages( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'pages' ] ) || ! in_array( 'pages', $allowed_endpoints[ 'pages' ] ) ) {
        $allowed_endpoints[ 'pages' ][] = 'posts';
    }
    return $allowed_endpoints;
}
function cache_packages( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'packages/all' ] ) || ! in_array( 'packages', $allowed_endpoints[ 'packages/all' ] ) ) {
        $allowed_endpoints[ 'packages/all' ][] = 'packages';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'cache_pages', 10, 1);
add_filter( 'wp_rest_cache/allowed_endpoints', 'cache_packages', 10, 1);