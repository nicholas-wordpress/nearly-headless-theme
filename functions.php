<?php
/**
 * Theme functions file.
 * Contains useful functions, and sets up the theme.
 */

use Nicholas\Nicholas;
use function Nicholas\nicholas;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Require composer autoloader. If you forget to install composer, wp_die will fire.
$autoload = trailingslashit( get_template_directory() ) . 'vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( 'Composer autoload not found. Did you forget to run composer install in your theme?' );
}

require_once( $autoload );

// Enqueue theme script
add_action( 'theme/enqueue_app_scripts', function () {
	nicholas()->scripts()->get( 'theme' )->enqueue();
} );

// Add compatibility mode URLs
add_filter( 'theme/compatibility_mode_urls', function ( $urls ) {

	// Filter Twitter Embeds
	$filtered_urls = Nicholas::get_urls_for_query( [
		'post_type' => 'any',
		's'         => '"providerNameSlug":"twitter"', // Twitter embeds
	] );

	return array_merge( $urls, $filtered_urls );
} );

/**
 * Templates.
 *
 * Underpin comes with a powerful template loading system, and this boilerplate expands on that with a template loader.
 * Below, you will see a few basic pre-set loaders built inline.
 *
 * These can be built inline as shown below, but they can also be added as a class that extends
 * Theme/Abstracts/Template. In circumstances where your template needs to prefetch a lot of data before render, it's
 * probably better to create a class, add your data, and then pass that data to the template.
 *
 * For more information, check out Underpin loader documentation here: https://github.com/underpin-WP/underpin#loaders
 * You may also want to check out the Theme\Loaders\Templates loader to see how to register your own templates.
 *
 * Additionally, check out more information on how the template system works here:
 * https://github.com/underpin-WP/underpin#template-system-trait
 */

$template_path = trailingslashit( get_template_directory() ) . 'templates';

/**
 * Index Templates
 * Files located in /templates/index/
 */
nicholas()->templates()->add( 'index', [
	'description' => "Renders the home page.",
	'name'        => "Index Template.",
	'group'       => 'index',
	'root_path'    => $template_path,
	'templates'   => [
		'index'              => [ 'override_visibility' => 'public' ],
		'archive'            => [ 'override_visibility' => 'public' ],
		'comments'           => [ 'override_visibility' => 'public' ],
		'singular'           => [ 'override_visibility' => 'public' ],
		'archive-post'       => [ 'override_visibility' => 'public' ],
		'archive-pagination' => [ 'override_visibility' => 'public' ],
		'post'               => [ 'override_visibility' => 'public' ],
		'404'                => [ 'override_visibility' => 'public' ],
		'no-posts'           => [ 'override_visibility' => 'public' ],
	],
] );

/**
 * Header Template
 * Files located in /templates/header/
 */
nicholas()->templates()->add( 'header', [
	'description' => "Renders the header.",
	'name'        => "Header Template.",
	'root_path'    => $template_path,
	'group'       => 'header',
	'templates'   => [
		'header'   => [ 'override_visibility' => 'public' ],
		'noscript' => [ 'override_visibility' => 'public' ],
	],
] );

/**
 * Footer Template
 * Files located in /templates/footer/
 */
nicholas()->templates()->add( 'footer', [
	'description' => "Renders the home page.",
	'name'        => "Index Template.",
	'root_path'    => $template_path,
	'group'       => 'footer',
	'templates'   => [
		'footer' => [ 'override_visibility' => 'public' ],
	],
] );

/**
 * Comments Template
 * Files located in /templates/comments/
 */
nicholas()->templates()->add( 'comments', [
	'description' => "Renders comments.",
	'name'        => "Comments Template.",
	'root_path'    => $template_path,
	'group'       => 'comments',
	'templates'   => [
		'comments' => [ 'override_visibility' => 'public' ],
	],
] );

/**
 * Compatibility Mode Template
 * Files located in /templates/compatibility-mode/
 */
nicholas()->templates()->add( 'compatibility-mode', [
	'description' => "Renders the page in compatibility mode.",
	'name'        => "Compatibility Mode Index Template.",
	'root_path'    => $template_path,
	'group'       => 'compatibility-mode',
	'templates'   => [
		'index' => [ 'override_visibility' => 'public' ],
	],
] );