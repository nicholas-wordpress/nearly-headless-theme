export default async ( args, next ) => {
	// If this URL is a compatibility mode URL, bail.
	if ( Alpine.store( 'compatibilityModeUrls' ).find( ( url ) => args.url.matchesUrl( url ) ) ) {
		return;
	}
	next()
}