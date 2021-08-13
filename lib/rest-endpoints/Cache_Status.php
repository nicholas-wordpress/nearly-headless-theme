<?php


namespace Theme\Rest_Endpoints;


use Theme\Abstracts\Theme_Endpoint;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cache_Status extends Theme_Endpoint {

	public $name           = 'Cache Status';
	public $description    = 'Fetches info about the cache';
	public $route          = '/cache-status';

	function endpoint( WP_REST_Request $request ) {

		$last_updated = new \WP_Query( [
			'posts_per_page'      => 1,
			'post_type'           => get_post_types(),
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'orderby'             => 'modified',
		] );


		return rest_ensure_response( [
			'theme_last_updated' => theme()->options()->get( 'theme_last_updated' )->get(),
			'post_last_updated'  => date( 'U', strtotime( $last_updated->posts[0]->post_modified_gmt ) ),
		] );
	}

	function has_permission( WP_REST_Request $request ) {
		return true;
	}

}