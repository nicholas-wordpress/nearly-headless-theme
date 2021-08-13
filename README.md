# Boilerplate for Custom Projects

This is a plugin boilerplate built on the [Underpin](https://github.com/alexstandiford/underpin) Framework. For
information on how to use this, check out Underpin's docs.

This plugin expects that Underpin has been installed as
a [WordPress Must-Use plugin](https://wordpress.org/support/article/must-use-plugins/).

## Template Loader

This theme includes a special [loader](https://github.com/underpin-WP/underpin#loaders), called `Templates`. This loader
makes it possible to create custom templates to be used arbitrarily in your theme. It replaces `get_template_part`, and
adds some useful beneftis:

1. It provides a clear place to put logic, data-fetching, and other things of the sort.
1. Templates can be nested.

### Registering a Template

Like any loader item, Templates must be registered. This can be done in a few different ways.

```php
theme()->templates()->add( 'index', [
	'description' => "Renders the home page.", // Human-readable description
	'name'        => "Index Template.",        // Human-readable name
	'group'       => 'index',                  // Template group.
	'templates'   => [                         // Templates to include.
		'loop'     => 'public',
		'post'     => 'public',
		'no-posts' => 'public',
	],
] );
```

The above example would expect three templates inside the `templates/index` directory.

1. `loop`
1. `post`
1. `no-posts`

This is a great down-and dirty way to set up a template, however some more-complex templates will benefit from using
custom class methods inside the template. In these cases, it makes more sense to extend `Theme\Abstracts\Template` so
you can add your own logic and keep your markup clean.

You can register a template as a class directly, like so:

```php
// lib/templates/Index.php

class Index extends \Theme\Abstracts\Template{

  protected $name = 'Index Template';
  
  protected $description = 'Human Read-able description';

  protected $group = 'index';

  protected $templates = [                         // Templates to include.
		'loop'     => 'public',
		'post'     => 'public',
		'no-posts' => 'public',
	];

  // Optionally place any helper methods specific to this template here. These methods would be use-able inside of the
  // template, and can really help keep your templates clean.
}
```

And then register your template in `functions.php` like so:

```php
theme()->templates()->add('index','Theme\Templates\Index');
```

### Rendering Template Output

The purpose of a template is to render the output HTML. Ideally, all of your logic would be pre-determined, and passed
directly to your template so it can be accessed directly via `get_param()`.

Let's take a look at the basic WordPress loop using the template system:

```php
<?php
/**
 * Index Loop Template
 *
 * @author: Alex Standiford
 * @date  : 12/21/19
 * @var Theme\Abstracts\Template $template
 */

// This confirms that nobody is trying to be cute, and load this template in a potentially dangerous way.
if ( ! theme()->templates()->is_valid_template( $template ) ) {
	return;
}

?>
<main>
	<?php if ( have_posts() ): ?>
		<?php while ( have_posts() ): the_post(); ?>
			<?= $template->get_template( 'post' ); ?>
		<?php endwhile; ?>
	<?php else: ?>
		<?= $template->get_template( 'no-posts' ); ?>
	<?php endif; ?>
	<?php get_sidebar(); ?>
</main>
```

Notice how we're referencing `$template` as-if it's a class? That's because it's _literally_ the instance of the
Template class. You can reference it directly as `$template`. This means you can use any of the methods inside your
Template class.

This includes rendering sub-templates by running `get_template`. In this context, you don't need to specify the group
because, `$template` already knows the group - you just need to tell it which template to use in the group.

Instead, the second argument for `get_template` is an optional associative array of arguments that get passed to the
next template. Those args can be accessed using `$template->get_param('argument-key', 'fallback_value')`

### Calling a template

To call a template, you can do this:

```php
<?= theme()->templates()->get_template( 'index', 'loop', [/* Arguments to pass to template */] ); ?>
```

where `index` is your template group, and `loop` is the template you wish to load. The third argument is an associative
array of arguments that you can pass to the template.

Just like inside the `$template` context above, arbitrary data that is passed to a template can be accessed using
`$template->get_param('key', 'fallback value')` where the first argument is the array key to grab, and the second value
is a default value to display if the key is not set.

You can learn more about the template system in [Underpin's docs](https://github.com/Underpin-WP/underpin/#template-system-trait).

## Useful Loaders

There are a handful of loaders that get used in most WordPress themes. Keep these in-mind as you work on the theme, and
use them if you find you need the functionality:

1. [Block Loader](https://github.com/Underpin-WP/underpin-block-loader) Create, register, and manage WordPress blocks.
1. [Decision List Loader](https://github.com/Underpin-WP/decision-list-loader) Create decision list registries that
   makes custom logic easy to extend.
1. [Menu Loader](https://github.com/Underpin-WP/menu-loader) Register, and manage custom theme nav menus
1. [Meta Loader](https://github.com/Underpin-WP/meta-loader) Manage custom meta to store in various meta tables
1. [Option Loader](https://github.com/Underpin-WP/option-loader) Register , and manage values to store in wp_options
1. [Rest Endpoint Loader](https://github.com/Underpin-WP/rest-endpoint-loader) Create, register, and manage REST
   endpoints
1. [Script Loader](https://github.com/Underpin-WP/script-loader) Create, and enqueue scripts
1. [Shortcode Loader](https://github.com/Underpin-WP/shortcode-loader) Create, and render custom shortcodes
1. [Sidebar Loader](https://github.com/Underpin-WP/sidebar-loader) Create, and manage WordPress sidebars
1. [Style Loader](https://github.com/Underpin-WP/style-loader) Create, and enqueue styles
1. [Widget Loader](https://github.com/Underpin-WP/widget-loader) Create widgets, complete with admin settings.

## Webpack Config

The Webpack and NPM configuration in this plugin is a barebones WordPress configuration that aligns the script dir with
Underpin's default script directory. It is intentionally un-opinionated, but it is set-up and ready to be extended.

The default entrypoint is `src/index.js`.

## Stylesheets

The default webpack config comes with Webpack's [postCSS loader](https://webpack.js.org/loaders/postcss-loader/), and
can handle SCSS and CSS files. You can override the default loader by adding a postcss file to this theme.