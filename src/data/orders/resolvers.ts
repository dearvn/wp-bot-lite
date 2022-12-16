/**
 * Internal dependencies.
 */
import actions from './actions';
import {
    //citiesDropdownEndpoint,
    ordersEndpoint,
    //orderTypesEndpoint,
} from './endpoint';
import {
    IOrderFilter,
    IResponseGenerator,
} from '../../interfaces';
//import { formatSelect2Data } from '../../utils/Select2Helper';
import { prepareOrderDataForDatabase } from './utils';

const resolvers = {
    *getOrders(filters: IOrderFilter) {
        if (filters === undefined) {
            filters = {};
        }

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            `${ordersEndpoint}?${queryParam}`
        );
        let totalPage = 0;
        let totalCount = 0;

        if (response.headers !== undefined) {
            totalPage = response.headers.get('X-WP-TotalPages');
            totalCount = response.headers.get('X-WP-Total');
        }
        debugger;
        yield actions.setOrders(response.data);
        yield actions.setTotalPage(totalPage);
        yield actions.setTotal(totalCount);
        return actions.setLoadingOrders(false);
    },

    *getOrderDetail(id: number) {
        yield actions.setLoadingOrders(true);
        const path = `${ordersEndpoint}/${id}`;
        const response = yield actions.fetchFromAPI(path);

        if (response.id) {
            const data = prepareOrderDataForDatabase(response);

            yield actions.setFormData(data);
        }

        return actions.setLoadingOrders(false);
    },

    /**getOrderTypes() {
        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            orderTypesEndpoint
        );

        const orderTypes: Array<IOrderTypes> = response.data;

        yield actions.setOrderTypes(formatSelect2Data(orderTypes));
    },

    *getCitiesDropdown() {
        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            citiesDropdownEndpoint
        );

        const cityDropdowns: Array<ICityDropdown> = response.data;

        yield actions.setCityDropdowns(formatSelect2Data(cityDropdowns));
    },*/
};

export default resolvers;
