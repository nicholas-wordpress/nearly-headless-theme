import { registerPlugin } from '@wordpress/plugins';
import CompatibilityModeToggle from 'nicholas-wp/editor/CompatibilityModeToggle'

registerPlugin( 'theme', { render: () => <CompatibilityModeToggle/> } );