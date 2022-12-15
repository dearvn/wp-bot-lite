/**
 * Internal dependencies.
 */
import * as Types from './types';
import { productDefaultState } from './default-state';

const reducer = (state = productDefaultState, action: any) => {
    switch (action.type) {
        case Types.GET_PRODUCTS:
            return {
                ...state,
                products: action.products,
            };

        case Types.GET_PRODUCT_DETAIL:
            return {
                ...state,
                product: action.product,
            };

        case Types.GET_PRODUCT_TYPES:
            return {
                ...state,
                productTypes: action.productTypes,
            };

        case Types.GET_CITIES_DROPDOWN:
            return {
                ...state,
                cityDropdowns: action.cityDropdowns,
            };

        case Types.SET_LOADING_PRODUCTS:
            return {
                ...state,
                loadingProducts: action.loadingProducts,
            };

        case Types.SET_TOTAL_PRODUCTS:
            return {
                ...state,
                total: action.total,
            };

        case Types.SET_TOTAL_PRODUCTS_PAGE:
            return {
                ...state,
                totalPage: action.totalPage,
            };

        case Types.SET_PRODUCTS_FILTER:
            return {
                ...state,
                filters: action.filters,
            };

        case Types.SET_PRODUCT_FORM_DATA:
            return {
                ...state,
                form: action.form,
            };

        case Types.SET_PRODUCTS_SAVING:
            return {
                ...state,
                productsSaving: action.productsSaving,
            };
    }

    return state;
};

export default reducer;
