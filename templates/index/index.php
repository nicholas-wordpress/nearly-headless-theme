<?php
/**
 * Index Loop Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */
use function Nicholas\nicholas;
use Nicholas\Nicholas;

if ( ! nicholas()->templates()->is_valid_template( $template ) ) {
	return;
}

if ( true === Nicholas::use_compatibility_mode() ): ?>
	<?= nicholas()->templates()->get_template( 'compatibility-mode', 'index' ) ?>
<?php else: ?>
	<template x-if="$store.type === 'archive' || $store.type === 'paged'">
		<main id="content">
			<?= nicholas()->templates()->get_template( 'index', 'archive' ) ?>
		</main>
	</template>

	<template x-if="$store.type === 'singular'">
		<main id="content">
			<?= nicholas()->templates()->get_template( 'index', 'singular' ) ?>
			<template x-if="$store.commentsOpen">
				<?= nicholas()->templates()->get_template( 'index', 'comments' ) ?>
			</template>
		</main>
	</template>

	<template x-if="$store.type === '404'">
		<main id="content">
			<?= nicholas()->templates()->get_template( 'index', '404' ) ?>
		</main>
	</template>
<?php endif; ?>