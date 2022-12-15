/**
 * Internal dependencies.
 */
import { ISelect2Input } from '../components/inputs/Select2Input';

export interface IAlert {
    /**
     * Alert ID.
     */
    id: number;

    /**
     * Alert name.
     */
    name: string;

    /**
     * Alert description.
     */
    type: string;

    /**
     * Alert Ticker.
     */
    ticker: string;

    /**
     * Alert Exchange.
     */
     exchange: string;

    /**
     * Close.
     */
    close: number;
}

export interface IAlertFormData extends IAlert {}

export interface IAlerts {
    /**
     * All city list dropdown as array of {label, value}.
     */
    cityDropdowns: Array<ISelect2Input>;

    /**
     * All alerts as array of IAlert.
     */
    alerts: Array<IAlert>;

    /**
     * Alert detail.
     */
    alert: IAlert;

    /**
     * Alert saving or not.
     */
    alertsSaving: boolean;

    /**
     * Alert deleting or not.
     */
    alertsDeleting: boolean;

    /**
     * All alert types as array of {label, value}.
     */
    alertTypes: Array<ISelect2Input>;

    /**
     * Is alerts loading.
     */
    loadingAlerts: boolean;

    /**
     * Count total page.
     */
    totalPage: number;

    /**
     * Count total number of data.
     */
    total: number;

    /**
     * Alert list filter.
     */
    filters: object;

    /**
     * Alert Form data.
     */
    form: IAlertFormData;
}

export interface IAlertFilter {
    /**
     * Alert filter by page no.
     */
    page?: number;

    /**
     * Alert search URL params.
     */
    search?: string;
}

export interface IAlertTypes {
    /**
     * Alert type id.
     */
    id: number;

    /**
     * Alert type name.
     */
    name: string;

    /**
     * Alert type slug.
     */
    slug: string;

    /**
     * Alert type description.
     */
    description: string | null;
}

export interface ICityDropdown {
    /**
     * City id.
     */
    id: number;

    /**
     * City name.
     */
    name: string;

    /**
     * City code.
     */
    code: string;

}
