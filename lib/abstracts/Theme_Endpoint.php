<?php

namespace Theme\Abstracts;


use Underpin_Rest_Endpoints\Abstracts\Rest_Endpoint;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Theme_Endpoint extends Rest_Endpoint{
	public $rest_namespace = 'theme/v1';

}