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

const alertStore = createReduxStore('wp-react/alerts', {
    reducer,
    actions,
    selectors,
    controls,
    resolvers,
});

export default alertStore;
