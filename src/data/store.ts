/**
 * External dependencies.
 */
import { register } from '@wordpress/data';

/**
 * Internal dependencies.
 */
import AlertStore from './alerts';
import OrderStore from './orders';
import SettingStore from "./settings";

/**
 * Register stores.
 */
register(AlertStore);
register(OrderStore);
register(SettingStore);
