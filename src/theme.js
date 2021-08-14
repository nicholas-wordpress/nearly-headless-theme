import Alpine from 'alpinejs'
import apiFetch from '@wordpress/api-fetch'
import Post from './components/Post'
import Comments from './components/Comments'
import primeCache from './middlewares/route/primeCache'
import fetchComments from './middlewares/route/fetchComments'
import updateStore from './middlewares/route/updateStore'
import validateCompatibilityMode from './middlewares/route/validateCompatibilityMode'
import updateHistory from './middlewares/route/updateHistory'
import updateAdminBar from './middlewares/route/updateAdminBar'
import validateAdminPage from './middlewares/route/validateAdminPage'
import setupPopstate from './middlewares/setup/setupPopstate'
import validateCache from './middlewares/setup/validateCache'
import { setStore, setLoadingState, setCompatibilityModeUrls, setHistory } from './helpers'
import {
	addCacheActions,
	addRouteActions,
	handleClickMiddleware,
	route,
	setupRouter,
	Url,
	validateMiddleware
} from "nicholas-router";

// Set up our own instance of apiFetch. This gets exported and is accessible globally via theme.fetch
// This allows us to create preloading middleware and other cool optimizations to our fetch API.
const fetch = apiFetch

// Delay startup of this script until after the page is loaded.
window.onload = function () {
	window.Alpine = Alpine

	// When Alpine is initialized, do these actions.
	document.addEventListener( 'alpine:init', async () => {

		// First, set up the initial state in our global store.
		// By passing an empty object here, we basically force it to reset.
		setStore( {} )
		setLoadingState( true )
		Alpine.store( 'compatibilityModeUrls', [] );
		Alpine.store( 'comments', '' )


		// Now fetch data. Fetch returns a promise, so we use 'await' to tell JS to wait it to resolve before moving on.
		const pageData = await theme.fetch( { path: theme_vars.preloaded_endpoint } )

		// Setup the Alpine store
		setStore( pageData[0] )
		setLoadingState( false )
		setCompatibilityModeUrls()

		// Store data in the cache
		new Url( window.location.href ).updateCache( pageData[0] )
	} )

	// Setup route middleware actions
	addRouteActions(
		// First, validate the URL
		validateMiddleware,
		// Validate this page is not an admin page
		validateAdminPage,
		// Validate this page doesn't require compatibility mode
		validateCompatibilityMode,
		// Then, we prime the cache for this URL
		primeCache,
		// Then, we Update the Alpine store
		updateStore,
		// Maybe fetch comments, if enabled
		fetchComments,
		// Update the history
		updateHistory,
		// Maybe update the admin bar
		updateAdminBar
	)

	// Fire up Nicholas
	setupRouter( handleClickMiddleware, setupPopstate, validateCache )

	// Fire up AlpineJS
	Alpine.start()
}


export { fetch, Post, Comments }