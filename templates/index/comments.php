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
<aside class="comment-wrapper" x-data="theme.Comments()">
	<div class="comments" x-html="$store.comments">
		<?php
		if ( use_compatibility_mode() ) {
			echo theme()->templates()->get_template( 'comments', 'comments' );
		}
		?>
	</div>
	<template x-if="true === isLoading">
		<p>Loading comments...</p>
	</template>
</aside>
