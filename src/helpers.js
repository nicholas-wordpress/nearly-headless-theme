import Alpine from "alpinejs";

function setStore( pageData ) {
	const defaults = {
		posts: [],
		body_class: [],
		type: '',
		pagination: '',
		comments_open: false
	}

	// Set default values. This ensures pageData has all of the necessary properties.
	pageData = { ...defaults, ...pageData }

	Alpine.store( 'posts', pageData.posts )
	Alpine.store( 'type', pageData.type )
	Alpine.store( 'pagination', pageData.pagination )
	Alpine.store( 'bodyClass', pageData.body_class )
	Alpine.store( 'commentsOpen', pageData.comments_open )
}

function setLoadingState( to ) {
	Alpine.store( 'isLoading', to === true )
}

async function setCompatibilityModeUrls() {
	const compatibilityModeUrls = await theme.fetch( { path: 'theme/v1/compatibility-mode-urls' } )

	Alpine.store( 'compatibilityModeUrls', compatibilityModeUrls );
}

function setHistory( url ){
	window.history.pushState( {
		comments: Alpine.store( 'comments' )
	}, document.title, url )
}

export { setStore, setLoadingState, setCompatibilityModeUrls, setHistory }