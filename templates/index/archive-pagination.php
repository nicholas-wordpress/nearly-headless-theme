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
$pagination = $template->get_param( 'pagination', '' );
?>
<div x-html="$store.pagination"><?= $pagination ?></div>