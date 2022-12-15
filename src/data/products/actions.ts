/**
 * Internal dependencies.
 */
import { Select2SingleRow } from '../../components/inputs/Select2Input';
import { IProduct, IProductFormData, IResponseGenerator } from '../../interfaces';
import { productsEndpoint } from './endpoint';
import * as Types from './types';
import { productDefaultFormData } from './default-state';

const actions = {
    setProducts(products: Array<IProduct>) {
        return {
            type: Types.GET_PRODUCTS,
            products,
        };
    },

    setProductDetail(product: IProduct) {
        return {
            type: Types.GET_PRODUCT_DETAIL,
            product,
        };
    },

    setCityDropdowns(cityDropdowns: Array<Select2SingleRow>) {
        return {
            type: Types.GET_CITIES_DROPDOWN,
            cityDropdowns,
        };
    },

    setProductTypes(productTypes: Array<Select2SingleRow>) {
        return {
            type: Types.GET_PRODUCT_TYPES,
            productTypes,
        };
    },

    setFormData(form: IProductFormData) {
        return {
            type: Types.SET_PRODUCT_FORM_DATA,
            form,
        };
    },

    setLoadingProducts(loadingProducts: boolean) {
        return {
            type: Types.SET_LOADING_PRODUCTS,
            loadingProducts,
        };
    },

    setSavingProducts(productsSaving: boolean) {
        return {
            type: Types.SET_PRODUCTS_SAVING,
            productsSaving,
        };
    },

    setDeletingProducts(productsDeleting: boolean) {
        return {
            type: Types.SET_PRODUCTS_DELETING,
            productsDeleting,
        };
    },

    *setFilters(filters = {}) {
        yield actions.setLoadingProducts(true);
        yield actions.setFilterObject(filters);

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        const path = `${productsEndpoint}?${queryParam}`;
        const response: {
            headers: Headers;
            data;
        } = yield actions.fetchFromAPIUnparsed(path);

        let totalPage = 0;
        let totalCount = 0;

        if (response.headers !== undefined) {
            totalPage = parseInt(response.headers.get('X-WP-TotalPages'));
            totalCount = parseInt(response.headers.get('X-WP-Total'));
        }

        yield actions.setTotalPage(totalPage);
        yield actions.setTotal(totalCount);
        yield actions.setProducts(response.data);
        return actions.setLoadingProducts(false);
    },

    setFilterObject(filters: object) {
        return {
            type: Types.SET_PRODUCTS_FILTER,
            filters,
        };
    },

    *saveProduct(payload: IProductFormData) {
        yield actions.setSavingProducts(true);

        try {
            let response: IResponseGenerator = {};
            if (payload.id > 0) {
                response = yield {
                    type: Types.UPDATE_PRODUCTS,
                    payload,
                };
            } else {
                response = yield {
                    type: Types.CREATE_PRODUCTS,
                    payload,
                };
            }

            if (response?.id > 0) {
                yield actions.setFormData({ ...productDefaultFormData });
                yield actions.setSavingProducts(false);
            }
        } catch (error) {
            yield actions.setSavingProducts(false);
        }
    },

    setTotalPage(totalPage: number) {
        return {
            type: Types.SET_TOTAL_PRODUCTS_PAGE,
            totalPage,
        };
    },

    setTotal(total: number) {
        return {
            type: Types.SET_TOTAL_PRODUCTS,
            total,
        };
    },

    fetchFromAPI(path: string) {
        return {
            type: Types.FETCH_FROM_API,
            path,
        };
    },

    fetchFromAPIUnparsed(path: string) {
        return {
            type: Types.FETCH_FROM_API_UNPARSED,
            path,
        };
    },

    *deleteProducts(payload: Array<number>) {
        yield actions.setDeletingProducts(true);

        try {
            const responseDeleteProducts: IResponseGenerator = yield {
                type: Types.DELETE_PRODUCTS,
                payload,
            };

            if (responseDeleteProducts?.total > 0) {
                yield actions.setFilters({});
            }

            yield actions.setDeletingProducts(false);
        } catch (error) {
            yield actions.setDeletingProducts(false);
        }
    },
};

export default actions;
