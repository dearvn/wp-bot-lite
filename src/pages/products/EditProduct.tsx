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
import ProductForm from '../../components/products/ProductForm';
import ProductSubmit from '../../components/products/ProductSubmit';
import productStore from '../../data/products';
import { IProduct } from '../../interfaces';

export default function EditProduct() {
    const navigate = useNavigate();
    const { id } = useParams();

    const backToProductsPage = () => {
        navigate('/products');
    };

    const productDetails: IProduct = useSelect(
        (select) => select(productStore).getProductDetail(id),
        []
    );

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
                <PageHeading text={__('Edit Product', 'landlite')} />
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
            slug="edit-product"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            <ProductForm product={productDetails} />
        </Layout>
    );
}
