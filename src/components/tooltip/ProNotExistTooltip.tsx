/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Tooltip from './Tooltip';
import Button from '../button/Button';
import { hasPro } from '../../../utils/global-data';

export interface IProNotExistTooltip {
    /**
     * Pro not exist extra description.
     */
    desc?: string;
}

const ProNotExistTooltip = ({ desc }: IProNotExistTooltip) => {
    return (
        <span>
            {!hasPro && (
                <>
                    <Tooltip
                        innerContent={
                            <Button
                                text={__('PRO', 'botlite')}
                                style={{
                                    padding: '5px 12px',
                                    marginLeft: 10,
                                    border: 0,
                                }}
                                buttonCustomClass="bg-amber-400"
                            />
                        }
                    >
                        {desc}
                        {__(
                            'This feature is only available in Pro version.',
                            'botlite'
                        )}
                    </Tooltip>
                </>
            )}
        </span>
    );
};

ProNotExistTooltip.defaultProps = {
    desc: __("Sorry, You can't view this.", 'botlite'),
};

export default ProNotExistTooltip;
