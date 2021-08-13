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
<main id="content">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			if ( is_singular() ) {
				echo theme()->templates()->get_template( 'index', 'post', [
					'content' => get_buffer( 'the_content' ),
					'title'   => get_buffer( 'the_title' ),
				] );

				echo theme()->templates()->get_template( 'index', 'comments' );
			} else {
				echo theme()->templates()->get_template( 'index', 'archive-post', [
					'excerpt' => get_buffer( 'the_excerpt' ),
					'title'   => get_buffer( 'the_title' ),
					'link'    => get_post_permalink(),
				] );
			}
		}
		if ( ! is_singular() ) {
			echo theme()->templates()->get_template( 'index', 'archive-pagination', [
				'pagination' => get_buffer( 'the_posts_pagination' ),
			] );
		}
	}
	?>
</main>
