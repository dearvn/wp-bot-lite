/**
 * Internal dependencies.
 */

import { IOrders } from '../../interfaces';

const selectors = {
    getOrders(state: IOrders) {
        const { orders } = state;
        return orders;
    },

    getOrderDetail(state: IOrders) {
        const { order } = state;

        return order;
    },

    getOrdersSaving(state: IOrders) {
        const { ordersSaving } = state;

        return ordersSaving;
    },

    getOrdersDeleting(state: IOrders) {
        const { ordersDeleting } = state;

        return ordersDeleting;
    },

    getLoadingOrders(state: IOrders) {
        const { loadingOrders } = state;

        return loadingOrders;
    },

    getTotalPage(state: IOrders) {
        const { totalPage } = state;

        return totalPage;
    },

    getTotal(state: IOrders) {
        const { total } = state;
        return total;
    },

    getFilter(state: IOrders) {
        const { filters } = state;
        return filters;
    },

    getForm(state: IOrders) {
        const { form } = state;

        return form;
    }
};

export default selectors;
