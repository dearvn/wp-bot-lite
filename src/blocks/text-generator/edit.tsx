/**
 * External dependencies.
 */
import {useEffect, useState} from "@wordpress/element";
import {__} from "@wordpress/i18n";
import {BlockControls, InspectorControls, RichText, useBlockProps} from "@wordpress/block-editor";
import {
    PanelBody,
    ColorPicker,
    ToolbarGroup,
    ToolbarButton,
    __experimentalBoxControl as BoxControl,
    __experimentalInputControl as InputControl
} from '@wordpress/components';
import Swal from "sweetalert2";
import useAbortableStreamFetch from 'use-abortable-stream-fetch';

/**
 * Internal dependencies.
 */
import "./editor.scss";
import {icon} from "./icon";
import Button from "../../components/button/Button";
import {Input} from "../../components/inputs/Input";
import {defaultConfigTextGenerator, openai} from "../../utils/open-ai.config";
import {copyToClipboard} from "../../utils/copy-to-clipboard";
import {ContentGenerator} from "../../components/blocks/ContentGenerator";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
    const {title, description, contentFrom, bgColor, padding} = attributes;
    const [openPreviewModal, setOpenPreviewModal] = useState(false);
    const [generating, setGenerating] = useState(false);

    const generateContent = async (e: any) => {
        e.preventDefault();
        setGenerating(true);
        await openai.createCompletion({
            prompt: contentFrom,
            ...defaultConfigTextGenerator
        }).then((res: any) => {
            let generatedArticle = res.data.choices[0]?.text;

            if (generatedArticle !== undefined && generatedArticle.length) {
                generatedArticle = generatedArticle.substring(2);
                generatedArticle = generatedArticle.replace(/(?:\r\n|\r|\n)/g, '<br>');
                setAttributes({description: description + ' ' + generatedArticle});
                setAttributes({contentFrom: ''});
                copyToClipboard(generatedArticle);
                Swal.fire({
                    title: __('Content generated', 'botlite'),
                    text: 'Text copied to clipboard.',
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 3000,
                });
            }
            setGenerating(false);
        }).catch((error: any) => {
            Swal.fire({
                title: __('Error', 'botlite'),
                text: error?.response?.data?.error?.message ?? error,
                icon: 'error',
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 3000,
            });
            setGenerating(false);
        });
    }

    return (
        <div
            {...useBlockProps()}
            style={{
                backgroundColor: bgColor,
                padding: `${padding?.top} ${padding?.right} ${padding?.bottom} ${padding?.left}`
            }}
        >
            <RichText
                className="wp-block-wp-react-kit-header_title"
                tagName="h2"
                placeholder={__("Header title", "botlite")}
                value={title}
                onChange={(title: string) => setAttributes({title})}
            />

            <RichText
                className="wp-block-wp-react-kit-header_title"
                tagName="div"
                placeholder={__("Header description", "botlite")}
                value={description}
                onChange={(description: string) => setAttributes({description})}
            />

            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton
                        icon={icon}
                        label={__('Socbase AI Content Generator', 'botlite')}
                        onClick={() => {
                            setOpenPreviewModal(true);
                        }}
                    />
                </ToolbarGroup>
            </BlockControls>

            {/* Content generator modal */}
            <ContentGenerator
                openPreviewModal={openPreviewModal}
                setOpenPreviewModal={setOpenPreviewModal}
            />

            <InspectorControls>
                <PanelBody
                    title={__('AI Content', 'botlite')}
                    initialOpen={true}
                >
                    <form onSubmit={generateContent}>
                        <Input
                            label={__('Generate content from', 'botlite')}
                            value={contentFrom}
                            className={"mb-2"}
                            placeholder={__('Enter content hint', 'botlite')}
                            onChange={({name, value}) => setAttributes({contentFrom: value})}
                        />
                        <Button
                            type='default'
                            isSubmitButton={true}
                            disabled={generating}
                            buttonCustomClass={'w-full bg-gradient-to-r from-gradient-from to-gradient-to !border-0'}
                            textClassName={'text-white'}
                            text={generating ? __('Generating...', 'botlite') : __('Generate AI Content →', 'botlite')}
                        />
                    </form>
                </PanelBody>
                <PanelBody
                    title={__('Color Settings', 'botlite')}
                    initialOpen={false}
                >
                    <ColorPicker
                        color={bgColor}
                        onChange={(bgColor: string) => setAttributes({bgColor})}
                        enableAlpha
                        defaultValue={bgColor}
                        clearable={false}
                    />
                </PanelBody>
                <PanelBody
                    title={__('Padding/Margin Settings', 'botlite')}
                    initialOpen={false}
                >
                    <BoxControl
                        label={__('Inline Padding', 'botlite')}
                        values={padding}
                        onChange={(padding: object) => setAttributes({padding})}
                    />
                </PanelBody>
            </InspectorControls>
        </div>
    );
}
