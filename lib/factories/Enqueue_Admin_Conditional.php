<?php

namespace Theme\Factories;


use Underpin\Traits\Instance_Setter;
use Underpin_Scripts\Factories\Enqueue_Admin_Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enqueue_Admin_Conditional extends Enqueue_Admin_Script {

	use Instance_Setter;

	protected $should_enqueue_callback;

	public function __construct( $args ) {
		$this->set_values( $args );
	}

	public function do_actions() {
		add_action('admin_enqueue_scripts',function(){
			if ( true === $this->should_enqueue() ) {
				parent::do_actions();
			}
		},1);
	}

	protected function should_enqueue() {
		return $this->set_callable( $this->should_enqueue_callback );
	}

}