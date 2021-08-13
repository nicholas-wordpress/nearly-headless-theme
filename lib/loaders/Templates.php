<?php
/**
 *
 *
 * @since
 * @package
 */

namespace Theme\Loaders;

use Theme\Abstracts\Template;
use Underpin\Abstracts\Registries\Loader_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Templates
 *
 *
 * @since
 * @package
 */
class Templates extends Loader_Registry {

	/**
	 * The abstraction class name.
	 * This is used to validate that the items in this service locator are extended
	 * from the correct abstraction.
	 *
	 * @since 1.0.0
	 * @var string The name of the abstract class this service locator uses.
	 */
	protected $abstraction_class = 'Theme\Abstracts\Template';

	protected $default_factory = 'Theme\Factories\Template_Instance';

	protected function set_default_items() {}

	/**
	 * Fetches a single template by the provided key.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @return Template|\WP_Error
	 */
	public function get( $key ) {
		return parent::get( $key );
	}

	/**
	 * Gets the specified template from a registered item, if it is valid.
	 *
	 * @since 1.0.0
	 *
	 * @param $key           string The registered item key.
	 * @param $template      string The template name to get.
	 * @param $params        array of param values that can be used in the template via get_param().
	 * @return string The template contents, or an empty string. Error explaining what went wrong get logged.
	 */
	public function get_template( $key, $template, $params = [] ) {
		$item = $this->get( $key );

		// Validate the item didn't return a result.
		if ( is_wp_error( $item ) ) {
			theme()->logger()->log_wp_error( 'error', $item );

			return '';
		}

		return $item->get_template( $template, $params );
	}

	public function is_valid_template( $template ) {
		return $template instanceof $this->abstraction_class;
	}
}