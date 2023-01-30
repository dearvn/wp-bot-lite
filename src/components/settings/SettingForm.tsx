/**
 * External dependencies.
 */
import {useSelect, useDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import SettingCard from './SettingCard';
import SettingSubmit from './SettingSubmit';
import {IInputResponse, Input} from '../inputs/Input';
import {ISettingFormData} from '../../interfaces';
import settingStore from "../../data/settings";
import {selectOptions} from "../../utils/Select2Helper";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {faInfoCircle} from "@fortawesome/free-solid-svg-icons";

export default function SettingForm() {
    const dispatch = useDispatch();

    const form: ISettingFormData = useSelect(
        (select) => select(settingStore).getSettings(),
        []
    );

    const loadingSettings: boolean = useSelect(
        (select) => select(settingStore).getLoadingSettings(),
        []
    );

    const onChange = (input: IInputResponse) => {
        dispatch(settingStore).setFormData({
            ...form,
            [input.name]:
                typeof input.value === 'object'
                    ? input.value?.value
                    : input.value,
        });
    };

    return (
        <form className="mt-10">
            <div className="">
                {loadingSettings ? (
                    <div className="">
                        <SettingCard>
                            <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                        </SettingCard>
                    </div>
                ) : (
                    <>
                        <div className="">
                            <SettingCard className="setting-general-info">
                                <div className="">
                                    <div className="max-w-[300px]">
                                        <Input
                                            type="select"
                                            label={__('Enable Trading', 'botlite')}
                                            id="enable_trading"
                                            value={form.enable_trading}
                                            options={selectOptions}
                                            onChange={onChange}
                                        />
                                    </div>

                                    {
                                        form.enable_trading === 'yes' &&
                                        <div>
                                            <Input
                                                type="text"
                                                label={__('API Key', 'botlite')}
                                                id="api_key"
                                                placeholder={__(
                                                    'Enter Binance API Key, eg: xxxx',
                                                    'botlite'
                                                )}
                                                value={form.api_key}
                                                onChange={onChange}
                                            />
                                            <Input
                                                type="text"
                                                label={__('Secret Key', 'botlite')}
                                                id="secret_key"
                                                placeholder={__(
                                                    'Enter Binance Secret Key, eg: xxxx',
                                                    'botlite'
                                                )}
                                                value={form.secret_key}
                                                onChange={onChange}
                                            />
                                            <p className="mt-1 text-slate-500">
                                                <FontAwesomeIcon icon={faInfoCircle} /> &nbsp;
                                                {__('Get your API & Secret key from ', 'botlite')}
                                                <a href="https://www.binance.com/en/my/settings/api-management" target="_blank" className="text-blue-600">
                                                https://www.binance.com/en/my/settings/api-management
                                                </a>
                                            </p>
                                        </div>

                                    }
                                </div>
                            </SettingCard>

                            <div className="flex justify-end md:hidden">
                                <SettingSubmit/>
                            </div>
                        </div>
                    </>
                )}
            </div>
        </form>
    );
}
