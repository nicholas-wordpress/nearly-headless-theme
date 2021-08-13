/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		/**
		 * Add your entry points for CSS and JS here.
		 */
		entry: {
			theme: './src/theme.js',
			editor: './src/editor.js',
			admin: './src/admin.js'
		},

		output: {
			...defaultConfig.output,
			libraryTarget: 'var',
			library: '[name]'
		},
	}
}