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
import ProductForm from '../../components/products/ProductForm';
import ProductSubmit from '../../components/products/ProductSubmit';
import productStore from '../../data/products';
import { productDefaultFormData } from '../../data/products/default-state';

export default function CreateProduct() {
    const navigate = useNavigate();

    const backToProductsPage = () => {
        navigate('/products');
    };

    useEffect(() => {
        dispatch(productStore).setFormData({
            ...productDefaultFormData,
        });
    }, []);

    /**
     * Get Page Content - Title and New Product button.
     *
     * @return JSX.Element
     */
    const pageTitleContent = (
        <div className="">
            <div className="mr-3 mb-4">
                <button
                    onClick={backToProductsPage}
                    className="text-gray-dark border-none"
                >
                    ← {__('Back to products', 'landlite')}
                </button>
            </div>
            <div className="text-left">
                <PageHeading text={__('Create New Product', 'landlite')} />
            </div>
        </div>
    );

    /**
     * Get Right Side Content - Create Product form data.
     */
    const pageRightSideContent = (
        <div className="mt-7 fixed invisible md:visible md:top-28 right-10 z-50">
            <ProductSubmit />
        </div>
    );

    return (
        <Layout
            title={pageTitleContent}
            slug="create-product"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            <ProductForm />
        </Layout>
    );
}
