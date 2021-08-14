import { Url } from "nicholas-router";
import Alpine from "alpinejs";
import { setStore } from '../../helpers'

export default ( args, next ) => {
	window.onpopstate = function ( e ) {
		const url = new Url( e.currentTarget.window.location.href )
		const cache = url.getCache()
		if ( cache ) {
			e.preventDefault()
			setStore( cache )

			if ( cache.comments_open ) {
				// If the comments are in the cache
				if ( e.data && e.data.comments ) {
					Alpine.store( 'comments', e.data.comments )

				// Sometimes, history gets saved before comments are added. In this case, go fetch the data.
				} else {
					new Promise( async ( res, rej ) => {
						const { output } = await theme.fetch( { path: `theme/v1/comment-output?path=${url.pathname}` } )

						// Set store to comment output
						Alpine.store( 'comments', output )
					} )
				}
			}
		}
	}

	next()
}