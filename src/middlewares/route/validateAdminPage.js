export default ( args, next ) => {
	// If any of the URLs
	if ( args.url.pathname.includes( 'wp-admin' ) || args.url.pathname.includes('wp-login') ) {
		return;
	}
	next()
}