/**
 * External dependencies
 */
import { useEffect } from '@wordpress/element';
import { dispatch } from '@wordpress/data';
import { useNavigate } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Layout from '../../components/layout/Layout';
import PageHeading from '../../components/layout/PageHeading';
import OrderForm from '../../components/orders/OrderForm';
import OrderSubmit from '../../components/orders/OrderSubmit';
import orderStore from '../../data/orders';
import { orderDefaultFormData } from '../../data/orders/default-state';

export default function CreateOrder() {
    const navigate = useNavigate();

    const backToOrdersPage = () => {
        navigate('/orders');
    };

    useEffect(() => {
        dispatch(orderStore).setFormData({
            ...orderDefaultFormData,
        });
    }, []);

    /**
     * Get Page Content - Title and New Order button.
     *
     * @return JSX.Element
     */
    const pageTitleContent = (
        <div className="">
            <div className="mr-3 mb-4">
                <button
                    onClick={backToOrdersPage}
                    className="text-gray-dark border-none"
                >
                    ← {__('Back to orders', 'botlite')}
                </button>
            </div>
            <div className="text-left">
                <PageHeading text={__('Create New Order', 'botlite')} />
            </div>
        </div>
    );

    /**
     * Get Right Side Content - Create Order form data.
     */
    const pageRightSideContent = (
        <div className="mt-7 fixed invisible md:visible md:top-28 right-10 z-50">
            <OrderSubmit />
        </div>
    );

    return (
        <Layout
            title={pageTitleContent}
            slug="create-order"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            <OrderForm />
        </Layout>
    );
}
