<?php
/**
 * Index Loop Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */
use function Nicholas\nicholas;

if ( ! nicholas()->templates()->is_valid_template( $template ) ) {
	return;
}

$title   = $template->get_param( 'title', '' );
$excerpt = $template->get_param( 'excerpt', '' );
$url     = $template->get_param( 'link', '' );
?>
<article x-data="theme.Post(index)">
	<h2><a x-html="title" x-bind:href="link" href="<?= $url ?>"><?= $title ?></a></h2>
	<div class="excerpt" x-html="excerpt"><?= $excerpt ?></div>
</article>