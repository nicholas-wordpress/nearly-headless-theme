<?php

namespace Theme\Options;


use Underpin_Options\Abstracts\Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Compatibility_Mode_Urls extends Option {

	public $key           = 'compatibility_mode_urls';
	public $default_value = [];

	public function get() {
		$value = parent::get();

		if ( empty( $value ) ) {
			return [];
		}

		return (array) $value;
	}

}