<?php
/**
 * Sorry developer, but your code is in another castle.
 *
 * Underpin manages everything inside the templates directory, using the template system.
 *
 * You're probably looking for /templates/index/loop.php
 */
?>
<?php
get_header();
echo Nicholas\nicholas()->templates()->get_template( 'index', 'index' );
get_footer();