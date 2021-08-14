<?php
/**
 * Index Loop Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */

use Nicholas\Nicholas;
use function Nicholas\nicholas;

if ( ! nicholas()->templates()->is_valid_template( $template ) ) {
	return;
}
?>
<main id="content">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			if ( is_singular() ) {
				echo nicholas()->templates()->get_template( 'index', 'post', [
					'content' => Nicholas::get_buffer( 'the_content' ),
					'title'   => Nicholas::get_buffer( 'the_title' ),
				] );

				echo nicholas()->templates()->get_template( 'index', 'comments' );
			} else {
				echo nicholas()->templates()->get_template( 'index', 'archive-post', [
					'excerpt' => Nicholas::get_buffer( 'the_excerpt' ),
					'title'   => Nicholas::get_buffer( 'the_title' ),
					'link'    => get_post_permalink(),
				] );
			}
		}
		if ( ! is_singular() ) {
			echo nicholas()->templates()->get_template( 'index', 'archive-pagination', [
				'pagination' => Nicholas::get_buffer( 'the_posts_pagination' ),
			] );
		}
	}
	?>
</main>
