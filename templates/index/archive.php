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
	<template x-for="(post, index) in $store.posts" :key="index">
		<?= theme()->templates()->get_template( 'index', 'archive-post' ) ?>
	</template>
<?= theme()->templates()->get_template( 'index', 'archive-pagination' ); ?>