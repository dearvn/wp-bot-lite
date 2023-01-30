export interface ISetting {
    /**
     * Enable AI or not.
     */
    enable_trading: string;

    /**
     * Binance API key.
     */
    api_key: string;

    /**
     * Binance API Secret.
     */
     secret_key: string;
}

export interface ISettingFormData extends ISetting {
}

export interface ISettings {
    /**
     * Is settings loading.
     */
    loadingSettings: boolean;

    /**
     * Is settings saving.
     */
    settingsSaving: boolean;

    /**
     * Settings Form data.
     */
    form: ISettingFormData;
}
