export default function ( iterator = 0 ) {

	return {
		field( field ) {
			const post = Alpine.store( 'posts' )[iterator]

			if ( undefined === post ) {
				return ''
			}

			if ( undefined === post[field] ) {
				return ''
			}

			return post[field]
		},

		get title() {
			const field = this.field( 'title' )

			return undefined === field.rendered ? '' : field.rendered
		},

		get content() {
			const field = this.field( 'content' )

			return undefined === field.rendered ? '' : field.rendered
		},

		get excerpt() {
			const field = this.field( 'excerpt' )

			return undefined === field.rendered ? '' : field.rendered
		},

		get link(){
			return this.field('link')
		}
	}
}