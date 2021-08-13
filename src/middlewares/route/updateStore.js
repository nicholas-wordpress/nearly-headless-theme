import Alpine from "alpinejs";
import { setStore, setLoadingState } from '../../helpers'

export default async ( args, next ) => {
	// We know the cache exists because it is primed in the previous step
	const cache = args.url.getCache()

	// Reset the scroll position
	window.scroll( { top: 0, behavior: 'smooth' } )

	// Update the Alpine store using the cached data
	setStore( cache )

	// Done loading!
	setLoadingState( false )

	// Move on to the next action.
	next()
}