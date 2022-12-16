/**
 * Internal dependencies.
 */
import { IOrder } from '../../interfaces';

export const prepareOrderDataForDatabase = (order: IOrder) => {
    const data = {
        ...order,
    };
    
    delete data._links;

    return data;
};
