/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

// Force Webpack to compile nicholas in this script.
defaultConfig.module.rules = defaultConfig.module.rules.map( ( rule ) => {

	// If the webpack configuration excludes node modules, change the exclusion to compile Nicholas.
	if ( rule.exclude && rule.exclude.toString() === /node_modules/.toString() ) {
		rule.exclude = /node_modules\/!nicholas$/
	}

	return rule
} )

// Now, take the resulting export and combine it with last-minute overrides.
module.exports = {
	...defaultConfig,
	...{
		/**
		 * Add your entry points for CSS and JS here.
		 */
		entry: {
			theme: './src/theme.js',
			editor: './src/editor.js',
			sessionManager: './src/session-manager.js',
			admin: './src/admin.js'
		},

		output: {
			...defaultConfig.output,
			libraryTarget: 'var',
			library: '[name]'
		},
	}
}