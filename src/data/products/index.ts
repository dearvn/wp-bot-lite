/**
 * External dependencies.
 */
import { createReduxStore } from '@wordpress/data';

/**
 * Internal dependencies.
 */
import reducer from './reducer';
import actions from './actions';
import selectors from './selectors';
import controls from './controls';
import resolvers from './resolvers';

const productStore = createReduxStore('wp-react/products', {
    reducer,
    actions,
    selectors,
    controls,
    resolvers,
});

export default productStore;
