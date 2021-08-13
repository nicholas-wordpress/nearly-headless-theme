<?php
/**
 * Theme
 *
 * Singleton instance of Underpin. Starts up the plugin, houses all loaders.
 * See https://github.com/underpin-WP/underpin
 *
 * @since 1.0.0
 */

namespace Theme;

use Theme\Loaders\Templates;
use Underpin\Abstracts\Underpin;
use Underpin\Factories\Loader_Registry_Item;
use Underpin_Meta\Loaders\Meta;
use Underpin_Options\Loaders\Options;
use Underpin_Scripts\Loaders\Scripts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @method Scripts scripts() Script loader. All actions related to scripts goe through here.
 * @method Loader_Registry_Item rest_endpoints() Rest Endpoint loader. all actions related to REST go through here.
 * @method Templates templates() Template loader. All actions related to loading templates go through here.
 * @method Meta meta() Meta loader. All custom metadata is registered, and accessed through this.
 * @method Options options() Options loader. All options are registered, and accessed through this.
 */
class Theme extends Underpin {

	/**
	 * The namespace for loaders. Used for loader autoloading.
	 *
	 * @since 1.0.0
	 *
	 * @var string Complete namespace for all loaders.
	 */
	protected $root_namespace = 'Theme';

	/**
	 * Translation Text domain.
	 *
	 * Used by translation method for translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $text_domain = 'theme';

	/**
	 * Minimum PHP Version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $minimum_php_version = '7.0';

	/**
	 * Minimum WordPress Version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $minimum_wp_version = '5.8';

	/**
	 * Current Version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $version = '1.0.0';

	protected function _setup() {
		$this->loaders()->add( 'templates', [ 'registry' => 'Theme\Loaders\Templates' ] );

		/**
		 * Register default scripts.
		 */
		$this->scripts()->add( 'theme', 'Theme\Scripts\Theme' );
		$this->scripts()->add( 'editor', 'Theme\Scripts\Editor' );
		$this->scripts()->add( 'admin', 'Theme\Scripts\Admin' );

		/**
		 * Register REST Endpoints
		 */
		$this->rest_endpoints()->add( 'page_data', 'Theme\Rest_Endpoints\Page_Data' );
		$this->rest_endpoints()->add( 'compatibility_mode_urls', 'Theme\Rest_Endpoints\Compatibility_Mode_Urls' );
		$this->rest_endpoints()->add( 'get_settings', 'Theme\Rest_Endpoints\Get_Settings' );
		$this->rest_endpoints()->add( 'update_settings', 'Theme\Rest_Endpoints\Update_Settings' );
		$this->rest_endpoints()->add( 'last_updated', 'Theme\Rest_Endpoints\Cache_Status' );
		$this->rest_endpoints()->add( 'last_updated', 'Theme\Rest_Endpoints\Comment_Output' );

		/**
		 * Register Options
		 */
		$this->options()->add( 'compatibility_mode_urls', 'Theme\Options\Compatibility_Mode_Urls' );
		$this->options()->add( 'theme_last_updated', [
			'key'           => 'theme_last_updated',
			'default_value' => '',
		] );

		/**
		 * Register Meta
		 */
		$this->meta()->add( 'use_compatibility_mode', [
			'key'                     => 'use_compatibility_mode',
			'description'             => 'Determines if this page should be loaded using compatibility mode',
			'name'                    => 'Use Compatibility Mode',
			'default_value'           => false,
			'type'                    => 'post',
			'field_type'              => 'boolean',
			'show_in_rest'            => true,
			'has_permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback'       => function ( $meta_value ) {
				settype( $meta_value, 'boolean' );

				return $meta_value;
			},
		] );

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

		/**
		 * Index Templates
		 * Files located in /templates/index/
		 */
		$this->templates()->add( 'index', [
			'description' => "Renders the home page.",
			'name'        => "Index Template.",
			'group'       => 'index',
			'templates'   => [
				'index'              => [ 'override_visibility' => 'public' ],
				'archive'            => [ 'override_visibility', 'public' ],
				'comments'           => [ 'override_visibility', 'public' ],
				'singular'           => [ 'override_visibility', 'public' ],
				'archive-post'       => [ 'override_visibility', 'public' ],
				'archive-pagination' => [ 'override_visibility', 'public' ],
				'post'               => [ 'override_visibility' => 'public' ],
				'404'                => [ 'override_visibility' => 'public' ],
				'no-posts'           => [ 'override_visibility' => 'public' ],
			],
		] );

		/**
		 * Header Template
		 * Files located in /templates/header/
		 */
		$this->templates()->add( 'header', [
			'description' => "Renders the header.",
			'name'        => "Header Template.",
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
		$this->templates()->add( 'footer', [
			'description' => "Renders the home page.",
			'name'        => "Index Template.",
			'group'       => 'footer',
			'templates'   => [
				'footer' => [ 'override_visibility' => 'public' ],
			],
		] );

		/**
		 * Comments Template
		 * Files located in /templates/comments/
		 */
		$this->templates()->add( 'comments', [
			'description' => "Renders comments.",
			'name'        => "Comments Template.",
			'group'       => 'comments',
			'templates'   => [
				'comments' => [ 'override_visibility' => 'public' ],
			],
		] );

		/**
		 * Compatibility Mode Template
		 * Files located in /templates/compatibility-mode/
		 */
		$this->templates()->add( 'compatibility-mode', [
			'description' => "Renders the page in compatibility mode.",
			'name'        => "Compatibility Mode Index Template.",
			'group'       => 'compatibility-mode',
			'templates'   => [
				'index' => [ 'override_visibility' => 'public' ],
			],
		] );

		// Maybe enqueue extra scripts for the app
		add_action( 'wp_enqueue_scripts', function () {
			if ( ! use_compatibility_mode() ) {
				if ( get_option( 'thread_comments' ) ) {
					wp_enqueue_script( 'comment-reply' );
				}

				/**
				 * Fires when a page is not loaded using compatibility mode.
				 * Use this hook to enqueue additional styles and scripts and reduce the number of compatibility mode pages
				 * on your site.
				 *
				 *
				 * @since 1.0.0
				 */
				do_action( 'theme/enqueue_app_scripts' );
			}
		} );
	}


}