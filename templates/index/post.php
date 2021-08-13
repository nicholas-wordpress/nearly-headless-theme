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

$title   = $template->get_param( 'title', '' );
$content = $template->get_param( 'content', '' );
?>
<article x-data="theme.Post(index)">
	<h1 x-html="title"><?= $title ?></h1>
	<div class="content" x-html="content"><?= $content ?></div>
</article>