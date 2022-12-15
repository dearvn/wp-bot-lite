/**
 * Internal dependencies.
 */

import { IProducts } from '../../interfaces';

const selectors = {
    getProducts(state: IProducts) {
        const { products } = state;

        return products;
    },

    getProductDetail(state: IProducts) {
        const { product } = state;

        return product;
    },

    getProductTypes(state: IProducts) {
        const { productTypes } = state;

        return productTypes;
    },

    getProductsSaving(state: IProducts) {
        const { productsSaving } = state;

        return productsSaving;
    },

    getProductsDeleting(state: IProducts) {
        const { productsDeleting } = state;

        return productsDeleting;
    },

    getLoadingProducts(state: IProducts) {
        const { loadingProducts } = state;

        return loadingProducts;
    },

    getTotalPage(state: IProducts) {
        const { totalPage } = state;

        return totalPage;
    },

    getTotal(state: IProducts) {
        const { total } = state;

        return total;
    },

    getFilter(state: IProducts) {
        const { filters } = state;

        return filters;
    },

    getForm(state: IProducts) {
        const { form } = state;

        return form;
    },

    getCitiesDropdown(state: IProducts) {
        const { cityDropdowns } = state;

        return cityDropdowns;
    },
};

export default selectors;
