export default async ( args, next ) => {
	// If any of the URLs
	if ( Alpine.store( 'compatibilityModeUrls' ).find( ( url ) => args.url.matchesUrl( url ) ) ) {
		return;
	}
	next()
}