/**
 * Internal dependencies.
 */
import * as Types from './types';
import { orderDefaultState } from './default-state';

const reducer = (state = orderDefaultState, action: any) => {
    switch (action.type) {
        case Types.GET_ORDERS:
            return {
                ...state,
                orders: action.orders,
            };

        case Types.GET_ORDER_DETAIL:
            return {
                ...state,
                order: action.order,
            };

        case Types.SET_LOADING_ORDERS:
            return {
                ...state,
                loadingOrders: action.loadingOrders,
            };

        case Types.SET_TOTAL_ORDERS:
            return {
                ...state,
                total: action.total,
            };

        case Types.SET_TOTAL_ORDERS_PAGE:
            return {
                ...state,
                totalPage: action.totalPage,
            };

        case Types.SET_ORDERS_FILTER:
            return {
                ...state,
                filters: action.filters,
            };

        case Types.SET_ORDER_FORM_DATA:
            return {
                ...state,
                form: action.form,
            };

        case Types.SET_ORDERS_SAVING:
            return {
                ...state,
                ordersSaving: action.ordersSaving,
            };
    }

    return state;
};

export default reducer;
