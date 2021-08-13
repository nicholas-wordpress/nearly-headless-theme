<?php

namespace Theme\Rest_Endpoints;


use Theme\Abstracts\Theme_Endpoint;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Comment_Output extends Theme_Endpoint {

	public $name        = 'Comment Endpoint';
	public $description = 'Fetches data about the specified page';
	public $route       = '/comment-output';

	function endpoint( WP_REST_Request $request ) {

		$path = $request->get_param( 'path' );

		if ( empty( $path ) ) {
			return new \WP_Error( 'invalid_path', 'The provided path is invalid' );
		}

		$result = with_path( $path, function ( $path ) {
			return theme()->templates()->get_template( 'comments', 'comments' );
		} );

		return [ 'output' => $result ];
	}

	function has_permission( WP_REST_Request $request ) {
		return true;
	}

}