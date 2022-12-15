/**
 * Internal dependencies.
 */
import actions from './actions';
import {
    citiesDropdownEndpoint,
    productsEndpoint,
    productTypesEndpoint,
} from './endpoint';
import {
    ICityDropdown,
    IProductFilter,
    IProductTypes,
    IResponseGenerator,
} from '../../interfaces';
import { formatSelect2Data } from '../../utils/Select2Helper';
import { prepareProductDataForDatabase } from './utils';

const resolvers = {
    *getProducts(filters: IProductFilter) {
        if (filters === undefined) {
            filters = {};
        }

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            `${productsEndpoint}?${queryParam}`
        );
        let totalPage = 0;
        let totalCount = 0;

        if (response.headers !== undefined) {
            totalPage = response.headers.get('X-WP-TotalPages');
            totalCount = response.headers.get('X-WP-Total');
        }

        yield actions.setProducts(response.data);
        yield actions.setTotalPage(totalPage);
        yield actions.setTotal(totalCount);
        return actions.setLoadingProducts(false);
    },

    *getProductDetail(id: number) {
        yield actions.setLoadingProducts(true);
        const path = `${productsEndpoint}/${id}`;
        const response = yield actions.fetchFromAPI(path);

        if (response.id) {
            const data = prepareProductDataForDatabase(response);

            yield actions.setFormData(data);
        }

        return actions.setLoadingProducts(false);
    },

    *getProductTypes() {
        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            productTypesEndpoint
        );

        const productTypes: Array<IProductTypes> = response.data;

        yield actions.setProductTypes(formatSelect2Data(productTypes));
    },

    *getCitiesDropdown() {
        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            citiesDropdownEndpoint
        );

        const cityDropdowns: Array<ICityDropdown> = response.data;

        yield actions.setCityDropdowns(formatSelect2Data(cityDropdowns));
    },
};

export default resolvers;
