import { setHistory } from '../../helpers'

export default ( args, next ) => {
	setHistory( args.url.href )
	next()
}