/**
 * Internal dependencies.
 */
import { ISelect2Input } from '../components/inputs/Select2Input';

export interface IOrder {
    /**
     * Order ID.
     */
    id: number;

    /**
     * Order name.
     */
    name: string;

    /**
     * Order description.
     */
    type: string;

    /**
     * Order Ticker.
     */
    ticker: string;

    /**
     * Order Exchange.
     */
     exchange: string;

    /**
     * Close.
     */
    close: number;
}

export interface IOrderFormData extends IOrder {}

export interface IOrders {
    /**
     * All city list dropdown as array of {label, value}.
     */
    cityDropdowns: Array<ISelect2Input>;

    /**
     * All orders as array of IOrder.
     */
    orders: Array<IOrder>;

    /**
     * Order detail.
     */
    order: IOrder;

    /**
     * Order saving or not.
     */
    ordersSaving: boolean;

    /**
     * Order deleting or not.
     */
    ordersDeleting: boolean;

    /**
     * All order types as array of {label, value}.
     */
    orderTypes: Array<ISelect2Input>;

    /**
     * Is orders loading.
     */
    loadingOrders: boolean;

    /**
     * Count total page.
     */
    totalPage: number;

    /**
     * Count total number of data.
     */
    total: number;

    /**
     * Order list filter.
     */
    filters: object;

    /**
     * Order Form data.
     */
    form: IOrderFormData;
}

export interface IOrderFilter {
    /**
     * Order filter by page no.
     */
    page?: number;

    /**
     * Order search URL params.
     */
    search?: string;
}

export interface IOrderTypes {
    /**
     * Order type id.
     */
    id: number;

    /**
     * Order type name.
     */
    name: string;

    /**
     * Order type slug.
     */
    slug: string;

    /**
     * Order type description.
     */
    description: string | null;
}
