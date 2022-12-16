/**
 * Internal dependencies.
 */

import { IOrders } from '../../interfaces';

const selectors = {
    getOrders(state: IOrders) {
        const { orders } = state;
        console.log(">>>>>>>>>orders", orders);
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
        console.log(">>>>>>>>>total", total);
        return total;
    },

    getFilter(state: IOrders) {
        const { filters } = state;
        console.log(">>>>>>>>>filters", filters);
        return filters;
    },

    getForm(state: IOrders) {
        const { form } = state;

        return form;
    }
};

export default selectors;
