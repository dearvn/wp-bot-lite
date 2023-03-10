/**
 * Internal dependencies
 */
import HomePage from '../pages/HomePage';
import SettingsPage from "../pages/SettingsPage";

import AlertsPage from '../pages/alerts/AlertsPage';
import CreateAlert from '../pages/alerts/CreateAlert';
import EditAlert from '../pages/alerts/EditAlert';
import OrdersPage from '../pages/orders/OrdersPage';

const routes = [
    {
        path: '/',
        element: HomePage,
    },
    {
        path: '/settings',
        element: SettingsPage,
    },
    {
        path: '/alerts',
        element: AlertsPage,
    },
    {
        path: '/alerts/new',
        element: CreateAlert,
    },
    {
        path: '/alerts/edit/:id',
        element: EditAlert,
    },
    {
        path: '/orders',
        element: OrdersPage,
    },
];

export default routes;
