/**
 * External dependencies.
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies.
 */
import { ordersEndpoint } from './endpoint';

const controls = {
    FETCH_FROM_API(action) {
        return apiFetch({ path: action.path });
    },

    FETCH_FROM_API_UNPARSED(action: { path: any }) {
        console.log(">>>>>>>>>>>>>apiFetch");
        return apiFetch({ path: action.path, parse: false }).then(
            (response: { headers: object; json: any }) =>
                Promise.all([response.headers, response.json()]).then(
                    ([headers, data]) => ({ headers, data })
                )
        );
    },

    CREATE_ORDERS(action) {
        return apiFetch({
            path: ordersEndpoint,
            method: 'POST',
            data: action.payload,
        });
    },

    UPDATE_ORDERS(action) {
        const path = ordersEndpoint + '/' + action.payload.id;
        return apiFetch({ path, method: 'PUT', data: action.payload });
    },

    DELETE_ORDERS(action) {
        const path = ordersEndpoint;
        return apiFetch({ path, method: 'DELETE', data: action.payload });
    },
};

export default controls;
