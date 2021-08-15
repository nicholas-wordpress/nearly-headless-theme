import { render } from '@wordpress/element'
import fetch from 'nicholas-wp'
import {Admin} from 'nicholas-wp/admin'

// Render the app
window.onload = () => render( <Admin/>, document.getElementById( 'app' ) )

// Export fetch, so we can add midleware via PHP
export { fetch }