/**
 * Internal dependencies.
 */
import { IProduct } from '../../interfaces';

export const prepareProductDataForDatabase = (product: IProduct) => {
    const data = {
        ...product,
        product_type_id: product.product_type.id,
        city_id: product.city.id,
    };

    if (product.is_active !== undefined) {
        data.is_active = product.is_active;
    } else {
        data.is_active = 1;
    }

    // Remove unnecessary data.
    delete data.city;
    delete data.product_type;
    delete data.status;
    delete data._links;

    return data;
};
