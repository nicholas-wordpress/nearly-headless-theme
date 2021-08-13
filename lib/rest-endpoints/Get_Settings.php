<?php

namespace Theme\Rest_Endpoints;


use Underpin_Rest_Endpoints\Abstracts\Rest_Endpoint;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Get_Settings extends Rest_Endpoint {

	public $name           = 'Get Settings Endpoint';
	public $description    = 'Fetches theme settings';
	public $route          = '/settings';

	function endpoint( WP_REST_Request $request ) {
		return rest_ensure_response( [
			'theme_last_updated'      => theme()->options()->get( 'theme_last_updated' )->get(),
			'compatibility_mode_urls' => theme()->options()->get( 'compatibility_mode_urls' )->get(),
		] );
	}

	function has_permission( WP_REST_Request $request ) {
		return current_user_can( 'administrator' );
	}

}