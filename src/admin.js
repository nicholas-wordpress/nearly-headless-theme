import { createHooks } from '@wordpress/hooks'
import { render, useState } from '@wordpress/element'
import { Button, TextControl, Spinner, Dashicon } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import apiFetch from '@wordpress/api-fetch'
import { Url } from 'nicholas'

const fetch = apiFetch

function UrlField( { setLoading, isLoading, setData, urls, setSearch } ) {
	const [value, setValue] = useState( '' )
	const [error, setError] = useState( false )

	function addUrl() {
		let valueToSet = value

		if ( !valueToSet ) {
			setError( __( 'Please use a valid url.' ) )
			return
		}

		if ( !( valueToSet.includes( 'www.' ) || valueToSet.includes( window.location.origin ) ) && !valueToSet.startsWith( '/' ) ) {
			valueToSet = `/${valueToSet}`
		}

		const url = new Url( valueToSet )

		if ( !url.isLocal() ) {
			setError( __( 'Please use a valid url.' ) )
			return
		}

		const urlExists = urls.find( testUrl => url.matchesUrl( testUrl ) )

		if ( urlExists ) {
			setError( __( 'That URL is already set' ) )
			return
		}

		if ( !urls ) {
			urls = []
		}

		urls.push( encodeURI( url.href ) )

		new Promise( async ( res, rej ) => {
			setLoading( true )
			await admin.fetch( {
				path: '/theme/v1/settings/update',
				method: 'POST',
				data: { compatibility_mode_urls: urls }
			} )
			setLoading( false )
			res()
		} )

		setData( { compatibility_mode_urls: urls } )
		setValue( '' )
		setSearch( urls )
	}

	return (
		<div style={{ display: 'flex', justifyContent: 'left' }}>
			<TextControl
				disabled={isLoading}
				onChange={value => setValue( value )}
				value={value}
				help={error}
				label={__( 'URL' )}
				type={'url'}
				onKeyUp={( e ) => {
					if ( e.key === 'Enter' ) {
						addUrl()
					}
				}}
			/>
			<Button style={{ alignSelf: 'start', marginLeft: "5px" }} disabled={isLoading || !value.length} onClick={addUrl}
							className="button-secondary"
							variant="secondary">Add URL</Button>
		</div>

	)
}

function Urls( { urls, removeUrl, search, setSearch } ) {
	const [query, setQuery] = useState( [] )

	function Url( { url, id } ) {
		const [value, setValue] = useState( url )
		return (
			<li>
				<a href={url}>{url}</a>
				<Button style={{ marginLeft: "5px" }} onClick={e => removeUrl( url )}>Remove</Button>
			</li>
		)
	}

	if ( !urls.length ) {
		return (
			<p>There are no URLs using compatibility mode right now.</p>
		)
	}

	function UrlList() {

		if ( !search.length ) {
			return <p>No URLs matching the filter were found.</p>
		}

		return (
			<ul>
				{search.map( ( url, id ) => <Url id={id} key={id} url={url}/> )}
			</ul>
		)
	}

	return (
		<>
			<h3>Current URLs</h3>
			<em>List of URLs that are currently using compatibility mode.</em>
			<TextControl
				onChange={value => {
					setSearch( urls.filter( url => url.includes( encodeURI( value ) ) ) )
					setQuery( value )
				}}
				value={query}
				label={__( 'Filter' )}
				type={'text'}
			/>
			<UrlList/>
		</>
	)
}

function App() {

	const [isLoading, setLoading] = useState( true )
	const [data, setData] = useState( false )
	const [search, setSearch] = useState( [] )

	if ( isLoading && false === data ) {

		new Promise( async ( res, rej ) => {
				const data = await admin.fetch( { path: 'theme/v1/settings' } )
				setData( data )
				setSearch( data.compatibility_mode_urls )
				setLoading( false )
			}
		)

		return (
			<div className="wrap">
				<h1>Theme Settings</h1>
				<Spinner/>
			</div>
		)
	}


	function removeUrl( url ) {
		const remainingUrls = data.compatibility_mode_urls.filter( ( urlTest ) => {
			if ( url !== urlTest ) {
				return true
			}

			return false
		} )

		setData( { compatibility_mode_urls: remainingUrls } )

		new Promise( async ( res, rej ) => {
				setLoading( true )
				await admin.fetch( {
					path: '/theme/v1/settings/update',
					method: 'POST',
					data: { compatibility_mode_urls: remainingUrls }
				} )
				setLoading( false )
				res()
			}
		)

		const searchUrls = search.filter( ( testUrl ) => testUrl !== url )
		setSearch( searchUrls )
	}

	function FlushButton() {

		const [notice, setNotice] = useState( '' )
		const [isFlushing, setFlushing] = useState( false )

		async function flush() {
			setFlushing( true )

			await admin.fetch( {
				path: '/theme/v1/settings/update',
				method: 'POST',
				data: { flush_cache: true }
			} )

			setFlushing( false )
			setNotice( __( 'Cache flushed!' ) )
			window.setTimeout( () => {
				setNotice( '' )
			}, 1000 )
		}

		let message = ''

		if ( isFlushing ) {
			message = <Spinner/>
		} else if ( notice ) {
			message = <em style={{ marginLeft: "10px" }}>{notice}</em>
		}

		return (
			<>
				<Button style={{ marginTop: "10px" }} className="button-primary" disabled={isLoading || isFlushing}
								onClick={flush}>
					Flush Cache
				</Button>
				{message}
			</>
		)
	}


	return (
		<div className="wrap">
			<h1>Theme Settings</h1>
			<h2>Compatibility Mode URLs</h2>
			<em>Any URLs manually added here will use compatibility mode, loading without the
				JavaScript cache.</em>
			<UrlField style={{ marginBottom: '20px' }} setSearch={setSearch} urls={data.compatibility_mode_urls}
								setData={setData} setLoading={setLoading}
								isLoading={isLoading}/>
			<Urls setSearch={setSearch} search={search} removeUrl={removeUrl} urls={data.compatibility_mode_urls}/>
			<h2>Force Flush</h2>
			<em style={{ display: 'block' }}>
				Cache auto-flushes when you update a post, but if you need to manually force the cache to flush you can do
				so
				here.
			</em>
			<FlushButton/>
		</div>
	)
}

window.onload = () => render( <App/>, document.getElementById( 'app' ) )

export { fetch }