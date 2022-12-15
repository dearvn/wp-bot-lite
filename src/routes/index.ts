/**
 * Internal dependencies
 */
import HomePage from '../pages/HomePage';
import ProductsPage from '../pages/products/ProductsPage';
import CreateProduct from '../pages/products/CreateProduct';
import EditProduct from '../pages/products/EditProduct';

const routes = [
    {
        path: '/',
        element: HomePage,
    },
    {
        path: '/products',
        element: ProductsPage,
    },
    {
        path: '/products/new',
        element: CreateProduct,
    },
    {
        path: '/products/edit/:id',
        element: EditProduct,
    },
];

export default routes;
