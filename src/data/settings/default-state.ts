/**
 * Internal dependencies.
 */
import {ISettings} from "../../interfaces";

export const settingDefaultFormData = {
    enable_trading: 'yes',
    api_key: '',
    secret_key: '',
};

export const settingDefaultState: ISettings = {
    loadingSettings: false,
    settingsSaving: false,
    form: {
        ...settingDefaultFormData,
    }
};
