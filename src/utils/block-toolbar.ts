
/**
 * External dependencies.
 */
import { registerFormatType } from '@wordpress/rich-text';
import {__} from "@wordpress/i18n";

/**
 * Internal dependencies.
 */
import {AiBlockToolbar} from "../components/blocks/AiBlockToolbar";

registerFormatType(
    'botlite/generator-button', {
        title: __('Socbase AI Content Generator', 'botlite'),
        tagName: 'b',
        className: null,
        edit: AiBlockToolbar,
    }
);