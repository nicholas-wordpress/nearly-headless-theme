import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { select, dispatch } from '@wordpress/data';
registerPlugin( 'theme', {

	render: () => {
		const [compatibilityMode, setCompatibilityMode] = useState( select( 'core/editor' ).getEditedPostAttribute( 'meta' ).use_compatibility_mode );

		function handleChange( use_compatibility_mode ) {
			setCompatibilityMode( use_compatibility_mode );
			dispatch( 'core/editor' )
				.editPost( { meta: { use_compatibility_mode } } )
		}

		return (
			<>
				<PluginDocumentSettingPanel
					name={'use-compatibility-mode'}
					title={__( 'Compatibility Mode' )}
					className={'compatibility-mode'}
				>
					<ToggleControl
						label={__( 'Use Compatibility Mode' )}
						help={__( 'Force this page to load without this theme\'s cache engine. Turn this on if this page is not working correctly.', 'beer' )}
						checked={compatibilityMode}
						onChange={handleChange}
					/>
				</PluginDocumentSettingPanel>
			</>
		)
	}
} );