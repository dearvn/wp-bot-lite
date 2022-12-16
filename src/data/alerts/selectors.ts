/**
 * Internal dependencies.
 */

import { IAlerts } from '../../interfaces';

const selectors = {
    getAlerts(state: IAlerts) {
        const { alerts } = state;

        return alerts;
    },

    getAlertDetail(state: IAlerts) {
        const { alert } = state;

        return alert;
    },

    getAlertTypes(state: IAlerts) {
        const { alertTypes } = state;

        return alertTypes;
    },

    getAlertsSaving(state: IAlerts) {
        const { alertsSaving } = state;

        return alertsSaving;
    },

    getAlertsDeleting(state: IAlerts) {
        const { alertsDeleting } = state;

        return alertsDeleting;
    },

    getLoadingAlerts(state: IAlerts) {
        const { loadingAlerts } = state;

        return loadingAlerts;
    },

    getTotalPage(state: IAlerts) {
        const { totalPage } = state;

        return totalPage;
    },

    getTotal(state: IAlerts) {
        const { total } = state;

        return total;
    },

    getFilter(state: IAlerts) {
        const { filters } = state;

        return filters;
    },

    getForm(state: IAlerts) {
        const { form } = state;

        return form;
    },

    /*getCitiesDropdown(state: IAlerts) {
        const { cityDropdowns } = state;

        return cityDropdowns;
    },*/
};

export default selectors;
