<?php
/**
 * Header Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */

if ( ! theme()->templates()->is_valid_template( $template ) || use_compatibility_mode() ) {
	return;
}

$url = add_query_arg( 'compatibility-mode', '1', get_home_url() . wp_parse_url( $_SERVER['REQUEST_URI'] )['path'] );
?>
<noscript>
	<style>
		#noscript-mask {
			width: 100%;
			height: 100%;
			position: fixed;
			z-index: 9001;
			background: white;
		}

		#noscript-mask h1 {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
		}
	</style>
	<div id="noscript-mask">
		<h1>Redirecting to non-javascript site...</h1>
	</div>
	<?php
	// Redirect to the current page, but force compatibility mode.
	?>
	<meta http-equiv="refresh" content="0.0;url=<?= $url ?>"/>
</noscript>
