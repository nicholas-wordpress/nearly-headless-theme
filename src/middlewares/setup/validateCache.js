import { clearCache } from 'nicholas-router'

function check() {
	new Promise( async ( res, rej ) => {
		const status = await theme.fetch( { path: '/theme/v1/cache-status' } )
		const cacheStatus = JSON.parse( window.sessionStorage.getItem( 'cacheStatus' ) )
		let cleared = false


		if ( status.post_last_updated !== cacheStatus?.post_last_updated ) {
			cleared = true
		} else if ( status.theme_last_updated !== cacheStatus?.theme_last_updated ) {
			cleared = true
		}

		if ( true === cleared ) {
			console.debug( 'Cache was cleared' )
			clearCache()
			window.sessionStorage.setItem( 'cacheStatus', JSON.stringify( status ) )
		} else {
			console.debug( 'Cache was checked, but did not need cleared' )
		}
	} )
}

export default function ( args, next ) {
	check()
	// Check cache every 5 minutes
	window.setInterval( check, 300000 )
}