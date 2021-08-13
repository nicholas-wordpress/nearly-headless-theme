export default ( args, next ) => {
	// Reset comment store
	Alpine.store( 'comments', '' )

	// Skip this if comments are not enabled on this post
	if ( false === Alpine.store( 'commentsOpen' ) ) {
		next()
		return
	}

	// Wrap this in a promise - no need to wait for it.
	new Promise( async ( res, rej ) => {

		const { output } = await theme.fetch( { path: `theme/v1/comment-output?path=${args.url.pathname}` } )

		// Set store to comment output
		Alpine.store( 'comments', output )
	} )

	next()
}