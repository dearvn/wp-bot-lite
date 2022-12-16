/**
 * External dependencies
 */
import { useSelect } from '@wordpress/data';
import { useNavigate, useParams } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Layout from '../../components/layout/Layout';
import PageHeading from '../../components/layout/PageHeading';
import OrderForm from '../../components/orders/OrderForm';
import OrderSubmit from '../../components/orders/OrderSubmit';
import orderStore from '../../data/orders';
import { IOrder } from '../../interfaces';

export default function EditOrder() {
    const navigate = useNavigate();
    const { id } = useParams();

    const backToOrdersPage = () => {
        navigate('/orders');
    };

    const orderDetails: IOrder = useSelect(
        (select) => select(orderStore).getOrderDetail(id),
        []
    );

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
                <PageHeading text={__('Edit Order', 'botlite')} />
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
            slug="edit-order"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            <OrderForm order={orderDetails} />
        </Layout>
    );
}
