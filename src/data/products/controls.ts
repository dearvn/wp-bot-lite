/**
 * External dependencies.
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies.
 */
import { productsEndpoint } from './endpoint';

const controls = {
    FETCH_FROM_API(action) {
        return apiFetch({ path: action.path });
    },

    FETCH_FROM_API_UNPARSED(action: { path: any }) {
        return apiFetch({ path: action.path, parse: false }).then(
            (response: { headers: object; json: any }) =>
                Promise.all([response.headers, response.json()]).then(
                    ([headers, data]) => ({ headers, data })
                )
        );
    },

    CREATE_PRODUCTS(action) {
        return apiFetch({
            path: productsEndpoint,
            method: 'POST',
            data: action.payload,
        });
    },

    UPDATE_PRODUCTS(action) {
        const path = productsEndpoint + '/' + action.payload.id;
        return apiFetch({ path, method: 'PUT', data: action.payload });
    },

    DELETE_PRODUCTS(action) {
        const path = productsEndpoint;
        return apiFetch({ path, method: 'DELETE', data: action.payload });
    },
};

export default controls;
