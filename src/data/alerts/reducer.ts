/**
 * Internal dependencies.
 */
import * as Types from './types';
import { alertDefaultState } from './default-state';

const reducer = (state = alertDefaultState, action: any) => {
    switch (action.type) {
        case Types.GET_ALERTS:
            return {
                ...state,
                alerts: action.alerts,
            };

        case Types.GET_ALERT_DETAIL:
            return {
                ...state,
                alert: action.alert,
            };

        case Types.GET_ALERT_TYPES:
            return {
                ...state,
                alertTypes: action.alertTypes,
            };

        case Types.GET_CITIES_DROPDOWN:
            return {
                ...state,
                cityDropdowns: action.cityDropdowns,
            };

        case Types.SET_LOADING_ALERTS:
            return {
                ...state,
                loadingAlerts: action.loadingAlerts,
            };

        case Types.SET_TOTAL_ALERTS:
            return {
                ...state,
                total: action.total,
            };

        case Types.SET_TOTAL_ALERTS_PAGE:
            return {
                ...state,
                totalPage: action.totalPage,
            };

        case Types.SET_ALERTS_FILTER:
            return {
                ...state,
                filters: action.filters,
            };

        case Types.SET_ALERT_FORM_DATA:
            return {
                ...state,
                form: action.form,
            };

        case Types.SET_ALERTS_SAVING:
            return {
                ...state,
                alertsSaving: action.alertsSaving,
            };
    }

    return state;
};

export default reducer;
