/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, CheckboxControl } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import apiFetch from '@wordpress/api-fetch';
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {

	const onSetIsproductNameChange = (ischeck) => {
		setAttributes({checkproductname:ischeck});

		if(document.readyState != 'loading'){
			sendWootableArguments(ischeck);
		}else{
			document.addEventListener('DOMcontentLoades',sendWootableArguments);
		}
	}

	function sendWootableArguments(checkproductname){
		const sendData = {ischeck};
		apiFetch({
			path: 'woo-table/v2/table',
			method:'POST',
			data:sendData
		}).then(data => {
			console.log(data)
		});
	}

	return (
		<div { ...useBlockProps() }>
			
		</div>
	);
}
