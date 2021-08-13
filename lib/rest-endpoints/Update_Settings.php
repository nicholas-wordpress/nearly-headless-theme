<?php

namespace Theme\Rest_Endpoints;


use Theme\Abstracts\Theme_Endpoint;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Update_Settings extends Theme_Endpoint {

	public $name           = 'Update Settings Endpoint';
	public $description    = 'Updates theme settings';
	public $rest_namespace = 'theme/v1';
	public $args           = [ 'methods' => 'POST' ];
	public $route          = '/settings/update';

	function endpoint( WP_REST_Request $request ) {
		$compatibility_mode_urls = $request->get_param( 'compatibility_mode_urls' );
		$flush_cache             = (bool) $request->get_param( 'flush_cache' );

		if ( isset( $compatibility_mode_urls ) ) {
			theme()->options()->get( 'compatibility_mode_urls' )->update( $compatibility_mode_urls );
		}
		if ( true === $flush_cache ) {
			theme()->options()->get( 'theme_last_updated' )->update( current_time( 'U', 1 ) );
		}

		return [ 'updated' => true, 'compatibility_mode_urls' => $compatibility_mode_urls, 'flush_cache' => $flush_cache ];
	}

	function has_permission( WP_REST_Request $request ) {
		return current_user_can( 'administrator' );
	}

}