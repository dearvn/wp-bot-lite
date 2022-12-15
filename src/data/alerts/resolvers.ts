/**
 * Internal dependencies.
 */
import actions from './actions';
import {
    citiesDropdownEndpoint,
    alertsEndpoint,
    alertTypesEndpoint,
} from './endpoint';
import {
    ICityDropdown,
    IAlertFilter,
    IAlertTypes,
    IResponseGenerator,
} from '../../interfaces';
import { formatSelect2Data } from '../../utils/Select2Helper';
import { prepareAlertDataForDatabase } from './utils';

const resolvers = {
    *getAlerts(filters: IAlertFilter) {
        if (filters === undefined) {
            filters = {};
        }

        const queryParam = new URLSearchParams(
            filters as URLSearchParams
        ).toString();

        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            `${alertsEndpoint}?${queryParam}`
        );
        let totalPage = 0;
        let totalCount = 0;

        if (response.headers !== undefined) {
            totalPage = response.headers.get('X-WP-TotalPages');
            totalCount = response.headers.get('X-WP-Total');
        }

        yield actions.setAlerts(response.data);
        yield actions.setTotalPage(totalPage);
        yield actions.setTotal(totalCount);
        return actions.setLoadingAlerts(false);
    },

    *getAlertDetail(id: number) {
        yield actions.setLoadingAlerts(true);
        const path = `${alertsEndpoint}/${id}`;
        const response = yield actions.fetchFromAPI(path);

        if (response.id) {
            const data = prepareAlertDataForDatabase(response);

            yield actions.setFormData(data);
        }

        return actions.setLoadingAlerts(false);
    },

    *getAlertTypes() {
        const response: IResponseGenerator = yield actions.fetchFromAPIUnparsed(
            alertTypesEndpoint
        );

        const alertTypes: Array<IAlertTypes> = response.data;

        yield actions.setAlertTypes(formatSelect2Data(alertTypes));
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
