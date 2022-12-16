/**
 * Internal dependencies.
 */
import { IOrders } from '../../interfaces';

export const orderDefaultFormData = {
    id: 0,
    name: '',
    ticker: '',
    type: '',
    exchange: '',
    interval: 0,
    entry_price: 0,
    entry_at: '',
    exit_price: 0,
    exit_at: '',
    contracts: 0
};

export const orderDefaultState: IOrders = {
    orders: [],
    order: {
        ...orderDefaultFormData,
    },
    loadingOrders: false,
    ordersSaving: false,
    ordersDeleting: false,
    totalPage: 0,
    total: 0,
    filters: {},
    form: {
        ...orderDefaultFormData,
    },
};
