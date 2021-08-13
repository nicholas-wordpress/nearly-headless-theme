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

if ( true === use_compatibility_mode() ): ?>
	<?= theme()->templates()->get_template( 'compatibility-mode', 'index' ) ?>
<?php else: ?>
	<template x-if="$store.type === 'archive' || $store.type === 'paged'">
		<main id="content">
			<?= theme()->templates()->get_template( 'index', 'archive' ) ?>
		</main>
	</template>

	<template x-if="$store.type === 'singular'">
		<main id="content">
			<?= theme()->templates()->get_template( 'index', 'singular' ) ?>
			<template x-if="$store.commentsOpen">
				<?= theme()->templates()->get_template( 'index', 'comments' ) ?>
			</template>
		</main>
	</template>

	<template x-if="$store.type === '404'">
		<main id="content">
			<?= theme()->templates()->get_template( 'index', '404' ) ?>
		</main>
	</template>
<?php endif; ?>