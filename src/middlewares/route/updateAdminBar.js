export default ( args, next ) => {
	const adminUrl = document.querySelector( '#wp-admin-bar-edit .ab-item' )

	if ( !adminUrl ) {
		next()
		return
	}

	const cache = args.url.getCache()

	if ( cache.type === 'singular' ) {
		if ( adminUrl ) {
			adminUrl.setAttribute( 'style', '' )
			let href = adminUrl.getAttribute( 'href' )
			href = href.replace( /post\=.+[0-9]/, `post=${cache.posts[0].id}` )
			adminUrl.setAttribute( 'href', href )
		}
	} else {
		adminUrl.setAttribute( 'style', 'display: none' )
	}

	next()
}