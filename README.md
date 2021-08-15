# Boilerplate for Nearly Headless WordPress Themes

This is a plugin boilerplate built using [Underpin](https://github.com/alexstandiford/underpin)
,[Nicholas](https://github.com/nicholas-wordpress), and [AlpineJS](https://github.com/alpinejs/alpine/). It will allow
you to build
a [Nearly Headless](https://www.wpdev.academy/concepts/headless-wordpress-is-overrated-a-case-for-the-nearly-headless-web-app/)
WordPress theme, that allows you to run a WordPress website like a headless app, but also allows you to render specific
pages using PHP instead of Javascript.

**Want to learn more about this method? Check out my
course [here](https://www.wpdev.academy/course/build-a-nearly-headless-wordpress-site-using-alpinejs/)**

## Setup

To set this plugin up, follow these steps:

1. Install composer packages `composer install`
2. Install NPM packages `npm i`
3. Compile NPM scripts `npm run start` or `npm run build`

## Templates

Under the hood, this boilerplate uses Underpin's [Template Loader](https://github.com/Underpin-WP/template-loader/) for
theme templating. Check out that library's readme file for documentation on how to create, and extend templates in this
template.

## Scripts

This boilerplate extends the [Nicholas library](https://github.com/nicholas-wordpress/app) to set up the interfaces
needed to manage compatibility mode settings in the admin. Because of this, there are **3** scripts that get compiled.
Check out that readme for more information on how to extend these scripts and customize them to suit your theme's needs.

1. **admin.js** - Used to render the react app that displays the Nicholas settings screen located in **Settings>>
   Nicholas Settings**
2. **editor.js** - Used to add the "compatibility mode" toggle to posts
3. **theme.js** - The core functionality to handle the nearly headless approach.

## Stylesheets

The default webpack config comes with Webpack's [postCSS loader](https://webpack.js.org/loaders/postcss-loader/), and
can handle SCSS and CSS files. You can override the default loader by adding a postcss file to this theme.