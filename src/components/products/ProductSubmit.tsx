/**
 * External dependencies.
 */
import { useNavigate } from 'react-router-dom';
import { useDispatch, useSelect } from '@wordpress/data';
import { faCheckCircle } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import productStore from '../../data/products';
import Button from '../button/Button';
import { IProductFormData } from '../../interfaces';
import { productDefaultFormData } from '../../data/products/default-state';

export default function ProductSubmit() {
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const form: IProductFormData = useSelect(
        (select) => select(productStore).getForm(),
        []
    );

    const productsSaving: boolean = useSelect(
        (select) => select(productStore).getProductsSaving(),
        []
    );

    const backToProductsPage = () => {
        navigate('/products');
    };

    const validate = () => {
        if (!form.title.length) {
            return __('Please give a product title.', 'landlite');
        }

        if (form.product_type_id === 0) {
            return __('Please select product type.', 'landlite');
        }

        if (!form.description.length) {
            return __('Please give product description.', 'landlite');
        }

        if (form.city_id === 0) {
            return __('Please select a city.', 'landlite');
        }

        return '';
    };

    const onSubmit = () => {
        //Validate
        if (validate().length > 0) {
            Swal.fire({
                title: __('Error', 'landlite'),
                text: validate(),
                icon: 'error',
                toast: true,
                position: 'bottom',
                showConfirmButton: false,
                timer: 4000,
            });

            return;
        }

        // Submit
        dispatch(productStore)
            .saveProduct(form)
            .then(() => {
                Swal.fire({
                    title: __('Product saved', 'landlite'),
                    text: __('Product has been saved successfully.', 'landlite'),
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2000,
                });
                dispatch(productStore).setFormData({
                    ...productDefaultFormData,
                });
                navigate('/products');
            })
            .catch((error) => {
                Swal.fire({
                    title: __('Error', 'landlite'),
                    text: error.message,
                    icon: 'error',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 3000,
                });
            });
    };

    return (
        <>
            <Button
                text={__('Cancel', 'landlite')}
                type="default"
                onClick={backToProductsPage}
                buttonCustomClass="mr-3"
            />

            <Button
                text={
                    productsSaving
                        ? __('Saving…', 'landlite')
                        : __('Save', 'landlite')
                }
                type="primary"
                icon={faCheckCircle}
                disabled={productsSaving}
                onClick={onSubmit}
            />
        </>
    );
}
