/**
 * Internal dependencies.
 */
import { IProducts } from '../../interfaces';

export const productDefaultFormData = {
    id: 0,
    title: '',
    description: '',
    product_type_id: 0,
    city_id: 0,
    is_active: 1,
};

export const productDefaultState: IProducts = {
    products: [],
    product: {
        ...productDefaultFormData,
    },
    productTypes: [],
    loadingProducts: false,
    productsSaving: false,
    productsDeleting: false,
    totalPage: 0,
    total: 0,
    filters: {},
    form: {
        ...productDefaultFormData,
    },
    cityDropdowns: [],
};
