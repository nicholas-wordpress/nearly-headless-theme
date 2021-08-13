<?php
/**
 * Index Loop Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */

if ( ! theme()->templates()->is_valid_template( $template ) ) {
	return;
}

?>

<h1>404 - Page Not Found</h1>
<p>The page you are trying to visit could not be found.</p>