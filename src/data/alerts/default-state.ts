/**
 * Internal dependencies.
 */
import { IAlerts } from '../../interfaces';

export const alertDefaultFormData = {
    id: 0,
    name: '',
    ticker: '',
    type: '',
    exchange: 0,
    close: 0
};

export const alertDefaultState: IAlerts = {
    alerts: [],
    alert: {
        ...alertDefaultFormData,
    },
    alertTypes: [],
    loadingAlerts: false,
    alertsSaving: false,
    alertsDeleting: false,
    totalPage: 0,
    total: 0,
    filters: {},
    form: {
        ...alertDefaultFormData,
    },
};
