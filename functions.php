<?php
/**
 * Theme functions file.
 * Contains useful functions, and sets up the theme.
 */

use Theme\Theme;
use Underpin\Abstracts\Underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Require composer autoloader. If you forget to install composer, wp_die will fire.
$autoload = trailingslashit( get_template_directory() ) . 'vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( 'Composer autoload not found. Did you forget to run composer install in your theme?' );
}

require_once( $autoload );

// Require the Theme's Underpin instance.
require_once( trailingslashit( get_template_directory() ) . 'lib/Theme.php' );

/**
 * Fetches the instance of the theme.
 * This function makes it possible to access everything else in this theme.
 * It will automatically initiate the plugin, if necessary.
 * It also handles autoloading for any class in the plugin.
 *
 * Check out lib/Theme.php - most of the magic happens there.
 *
 * @since 1.0.0
 *
 * @return Underpin|Theme The bootstrap for this theme.
 */
function theme() {
	return ( new Theme )->get( __FILE__ );
}

// Rock and roll
theme();

/**
 * Simulates a REST API request.
 *
 * @since 1.0.0
 *
 * @param string $type     The type of request.
 * @param string $endpoint The REST API endpoint.
 * @param array  $params   List of params to pass to the endpoint.
 *
 * @return array|WP_Error|null
 */
function request( string $type, string $endpoint, array $params = [] ) {
	$type    = strtoupper( $type );
	$request = new \WP_REST_Request( $type, $endpoint );
	$request->set_query_params( $params );
	$response = rest_do_request( $request );

	if ( $response->is_error() ) {
		$error = $response->as_error();

		theme()->logger()->log_wp_error( 'error', $error, [
			'ref'     => $endpoint,
			'context' => 'endpoint',
			'params'  => $params,
		] );

		return $error;
	}

	return [ 'body' => $response->get_data(), 'headers' => $response->get_headers() ];
}

/**
 * Returns true if this page should be loaded using compatibility mode.
 *
 * @since 1.0.0
 *
 * @return bool true if compatibility mode should be used, otherwise false.
 */
function use_compatibility_mode() {

	// If compatibility mode was forced via GET, return.
	if ( isset( $_REQUEST['compatibility-mode'] ) ) {
		return true;
	}

	$result = wp_cache_get( 'theme_use_compatibility_mode' );

	if ( false === $result ) {
		$result = false;

		// Get the current path.
		$current_path = wp_parse_url( $_SERVER['REQUEST_URI'] )['path'];

		foreach ( get_compatibility_mode_urls() as $url ) {
			$url = wp_parse_url( $url )['path'];

			// If the paths match, this should use compatibility mode.
			if ( $url === $current_path ) {
				$result = true;
				break;
			}
		}
		wp_cache_add( 'theme_compatibility_mode_urls', $result );
	}

	return $result;
}

/**
 * Retrieves the list of compatibility mode URLs.
 *
 * @since 1.0.0
 *
 * @return false|mixed
 */
function get_compatibility_mode_urls() {
	$urls = wp_cache_get( 'theme_compatibility_mode_urls' );

	if ( false === $urls ) {
		$compat_mode_args = [
			'post_type'  => 'any',
			'meta_query' => [
				'relation' => 'AND',
				[
					'key'     => 'use_compatibility_mode',
					'value'   => true,
					'compare' => '=',
				],
			],
		];

		$compat_mode_urls = get_urls_for_query( $compat_mode_args );

		/**
		 * Filters the query arguments that retrieves compatibility mode URLs
		 *
		 * @since 1.0.0
		 *
		 * @param array $args query arguments. See WP_Query.
		 */
		$args = apply_filters( 'compatibility_mode_query', [
			'post_type' => 'any',
			's'         => '"providerNameSlug":"twitter"', // Twitter embeds
		] );

		$filtered_urls = get_urls_for_query( $args );

		$urls = theme()->options()->get( 'compatibility_mode_urls' )->get();

		if ( empty( $urls ) ) {
			$urls = [];
		}

		/**
		 * Filters the resulting list of URLs to force compatibility mode.
		 *
		 * @since 1.0.0
		 *
		 * @param [string] $urls list of URLs to enforce compatibility mode
		 */
		$urls = apply_filters( 'compatibility_mode_urls', array_merge( $compat_mode_urls, $urls, $filtered_urls ) );

		// No need to send repeated URLs
		$urls = array_unique( $urls );

		// Cache this so we don't have to-do it again.
		wp_cache_add( 'theme_compatibility_mode_urls', maybe_serialize( $urls ) );
	} else {
		$urls = maybe_unserialize( $urls );
	}

	// Reset keys. This ensures REST responses don't mistake this for an object instead of an array.
	return array_values( $urls );
}

/**
 * Retrieves the URLs from a WP_Query result set.
 *
 * @since 1.0.0
 *
 * @param array $args Query arguments. See WP_Query.
 *
 * @return array list of permalink URLs.
 */
function get_urls_for_query( $args ) {
	$defaults = [
		'fields'         => 'ids',
		'posts_per_page' => -1,
	];

	$args      = wp_parse_args( $args, $defaults );
	$query     = new \WP_Query( $args );
	$post_urls = [];

	foreach ( $query->posts as $post_id ) {
		$post_urls[] = get_the_permalink( $post_id );
	}

	return $post_urls;
}

/**
 * Fetches an echo'd callback as a string.
 *
 * @since 1.0.0
 *
 * @param callable $callback The function to call
 *
 * @return string The output
 */
function get_buffer( callable $callback ) {
	ob_start();
	$callback();
	$result = ob_get_clean();

	return false === $result ? '' : $result;
}

/**
 * Runs a callback in the context of the specified path.
 *
 * @since 1.0.0
 *
 * @param string   $path     The local URL path to call.
 * @param callable $callback The callback to run.
 *
 * @return mixed The result of callback, in the context of the specified path.
 */
function with_path( string $path, callable $callback ) {
	global $wp, $post, $wp_query;

	// Store the original WP instance and request URI to reset later
	$old_wp      = $wp;
	$old_post    = $post;
	$request_uri = $_SERVER['REQUEST_URI'];

	// Trick WordPress into thinking we're on a different URL
	$uri                    = trailingslashit( $path );
	$_SERVER['REQUEST_URI'] = $uri;
	$wp                     = new WP();
	$wp->parse_request();
	query_posts( $wp->query_vars );
	$post = get_post( $wp_query->posts[0] );

	$result = $callback( $path );

	// Put everything back. Nothing to see here!
	$wp                     = $old_wp;
	$_SERVER['REQUEST_URI'] = $request_uri;
	$post                   = $old_post;
	wp_reset_query();

	return $result;
}