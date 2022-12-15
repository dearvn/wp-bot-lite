/**
 * Internal dependencies.
 */
import { IAlerts } from '../../interfaces';

export const alertDefaultFormData = {
    id: 0,
    title: '',
    description: '',
    alert_type_id: 0,
    city_id: 0,
    is_active: 1,
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
    cityDropdowns: [],
};
