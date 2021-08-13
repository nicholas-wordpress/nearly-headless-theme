<?php

namespace Theme\Scripts;


use Underpin_Scripts\Abstracts\Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin extends Script {

	public function __construct() {
		$this->handle      = 'admin';
		$this->src         = get_template_directory_uri() . '/build/admin.js';
		$this->deps        = theme()->dir() . 'build/admin.asset.php';
		$this->name        = 'Admin Script';
		$this->description = 'Admin Customizations';
		parent::__construct();
	}

	public function do_actions() {
		parent::do_actions();
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( "admin_menu", [ $this, 'setup_admin_page' ] );
	}

	public function setup_admin_page() {
		add_submenu_page(
			'options-general.php',
			'Theme Settings',
			'Theme Settings',
			'administrator',
			'theme-settings',
			function () {
				echo '<div id="app"></div>';
			} );
	}

	public function enqueue() {
		$is_theme_settings = get_current_screen()->base === 'settings_page_theme-settings';

		if ( $is_theme_settings ) {
			parent::enqueue();
			// Root URL
			wp_add_inline_script(
				$this->handle,
				sprintf( 'admin.fetch.use( admin.fetch.createRootURLMiddleware( "%s" ) )', rest_url() )
			);

			// Nonce
			wp_add_inline_script(
				$this->handle,
				sprintf( 'admin.fetch.use( admin.fetch.createNonceMiddleware( "%s" ) )', wp_create_nonce( 'wp_rest' ) )
			);

		}
	}

}