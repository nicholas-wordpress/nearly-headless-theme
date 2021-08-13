export default function () {
	return {

		isLoading: false,

		init() {
			//get comments
			new Promise( async ( res, rej ) => {
				// bail if comments are not open.
				if ( false === Alpine.store( 'commentsOpen' ) ) {
					res();
				}

				this.isLoading = true

				// fetch comments
				const { output } = await theme.fetch( { path: `theme/v1/comment-output?path=${window.location.pathname}` } )

				Alpine.store( 'comments', output )

				this.isLoading = false

				res()
			} )
		}
	}
}