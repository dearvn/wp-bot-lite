/**
 * Internal dependencies.
 */
import { Select2SingleRow } from '../../components/inputs/Select2Input';
import { IOrder, IOrderFormData, IResponseGenerator } from '../../interfaces';
import { ordersEndpoint } from './endpoint';
import * as Types from './types';
import { orderDefaultFormData } from './default-state';

const actions = {
    setOrders(orders: Array<IOrder>) {
        return {
            type: Types.GET_ORDERS,
            orders,
        };
    },

    setOrderDetail(order: IOrder) {
        return {
            type: Types.GET_ORDER_DETAIL,
            order,
        };
    },

    setFormData(form: IOrderFormData) {
        return {
            type: Types.SET_ORDER_FORM_DATA,
            form,
        };
    },

    setLoadingOrders(loadingOrders: boolean) {
        return {
            type: Types.SET_LOADING_ORDERS,
            loadingOrders,
        };
    },

    setSavingOrders(ordersSaving: boolean) {
        return {
            type: Types.SET_ORDERS_SAVING,
            ordersSaving,
        };
    },

    setDeletingOrders(ordersDeleting: boolean) {
        return {
            type: Types.SET_ORDERS_DELETING,
            ordersDeleting,
        };
    },

    *setFilters(filters = {}) {
        yield actions.setLoadingOrders(true);
        yield actions.setFilterObject(filters);

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        console.log(">>>>>>>>>>>>>>>>>>>>>>>>queryParam", queryParam);

        const path = `${ordersEndpoint}?${queryParam}`;
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
        yield actions.setOrders(response.data);
        return actions.setLoadingOrders(false);
    },

    setFilterObject(filters: object) {
        return {
            type: Types.SET_ORDERS_FILTER,
            filters,
        };
    },

    *saveOrder(payload: IOrderFormData) {
        yield actions.setSavingOrders(true);

        try {
            let response: IResponseGenerator = {};
            if (payload.id > 0) {
                response = yield {
                    type: Types.UPDATE_ORDERS,
                    payload,
                };
            } else {
                response = yield {
                    type: Types.CREATE_ORDERS,
                    payload,
                };
            }

            if (response?.id > 0) {
                yield actions.setFormData({ ...orderDefaultFormData });
                yield actions.setSavingOrders(false);
            }
        } catch (error) {
            yield actions.setSavingOrders(false);
        }
    },

    setTotalPage(totalPage: number) {
        return {
            type: Types.SET_TOTAL_ORDERS_PAGE,
            totalPage,
        };
    },

    setTotal(total: number) {
        return {
            type: Types.SET_TOTAL_ORDERS,
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

    *deleteOrders(payload: Array<number>) {
        yield actions.setDeletingOrders(true);

        try {
            const responseDeleteOrders: IResponseGenerator = yield {
                type: Types.DELETE_ORDERS,
                payload,
            };

            if (responseDeleteOrders?.total > 0) {
                yield actions.setFilters({});
            }

            yield actions.setDeletingOrders(false);
        } catch (error) {
            yield actions.setDeletingOrders(false);
        }
    },
};

export default actions;
