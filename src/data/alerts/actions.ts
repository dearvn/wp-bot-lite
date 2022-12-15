/**
 * Internal dependencies.
 */
import { Select2SingleRow } from '../../components/inputs/Select2Input';
import { IAlert, IAlertFormData, IResponseGenerator } from '../../interfaces';
import { alertsEndpoint } from './endpoint';
import * as Types from './types';
import { alertDefaultFormData } from './default-state';

const actions = {
    setAlerts(alerts: Array<IAlert>) {
        return {
            type: Types.GET_ALERTS,
            alerts,
        };
    },

    setAlertDetail(alert: IAlert) {
        return {
            type: Types.GET_ALERT_DETAIL,
            alert,
        };
    },

    setCityDropdowns(cityDropdowns: Array<Select2SingleRow>) {
        return {
            type: Types.GET_CITIES_DROPDOWN,
            cityDropdowns,
        };
    },

    setAlertTypes(alertTypes: Array<Select2SingleRow>) {
        return {
            type: Types.GET_ALERT_TYPES,
            alertTypes,
        };
    },

    setFormData(form: IAlertFormData) {
        return {
            type: Types.SET_ALERT_FORM_DATA,
            form,
        };
    },

    setLoadingAlerts(loadingAlerts: boolean) {
        return {
            type: Types.SET_LOADING_ALERTS,
            loadingAlerts,
        };
    },

    setSavingAlerts(alertsSaving: boolean) {
        return {
            type: Types.SET_ALERTS_SAVING,
            alertsSaving,
        };
    },

    setDeletingAlerts(alertsDeleting: boolean) {
        return {
            type: Types.SET_ALERTS_DELETING,
            alertsDeleting,
        };
    },

    *setFilters(filters = {}) {
        yield actions.setLoadingAlerts(true);
        yield actions.setFilterObject(filters);

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        const path = `${alertsEndpoint}?${queryParam}`;
        const response: {
            headers: Headers;
            data;
        } = yield actions.fetchFromAPIUnparsed(path);

        let totalPage = 0;
        let totalCount = 0;

        if (response.headers !== undefined) {
            totalPage = parseInt(response.headers.get('X-WP-TotalPages'));
            totalCount = parseInt(response.headers.get('X-WP-Total'));
        }

        yield actions.setTotalPage(totalPage);
        yield actions.setTotal(totalCount);
        yield actions.setAlerts(response.data);
        return actions.setLoadingAlerts(false);
    },

    setFilterObject(filters: object) {
        return {
            type: Types.SET_ALERTS_FILTER,
            filters,
        };
    },

    *saveAlert(payload: IAlertFormData) {
        yield actions.setSavingAlerts(true);

        try {
            let response: IResponseGenerator = {};
            if (payload.id > 0) {
                response = yield {
                    type: Types.UPDATE_ALERTS,
                    payload,
                };
            } else {
                response = yield {
                    type: Types.CREATE_ALERTS,
                    payload,
                };
            }

            if (response?.id > 0) {
                yield actions.setFormData({ ...alertDefaultFormData });
                yield actions.setSavingAlerts(false);
            }
        } catch (error) {
            yield actions.setSavingAlerts(false);
        }
    },

    setTotalPage(totalPage: number) {
        return {
            type: Types.SET_TOTAL_ALERTS_PAGE,
            totalPage,
        };
    },

    setTotal(total: number) {
        return {
            type: Types.SET_TOTAL_ALERTS,
            total,
        };
    },

    fetchFromAPI(path: string) {
        return {
            type: Types.FETCH_FROM_API,
            path,
        };
    },

    fetchFromAPIUnparsed(path: string) {
        return {
            type: Types.FETCH_FROM_API_UNPARSED,
            path,
        };
    },

    *deleteAlerts(payload: Array<number>) {
        yield actions.setDeletingAlerts(true);

        try {
            const responseDeleteAlerts: IResponseGenerator = yield {
                type: Types.DELETE_ALERTS,
                payload,
            };

            if (responseDeleteAlerts?.total > 0) {
                yield actions.setFilters({});
            }

            yield actions.setDeletingAlerts(false);
        } catch (error) {
            yield actions.setDeletingAlerts(false);
        }
    },
};

export default actions;
