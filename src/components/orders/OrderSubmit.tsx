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
import orderStore from '../../data/orders';
import Button from '../button/Button';
import { IOrderFormData } from '../../interfaces';
import { orderDefaultFormData } from '../../data/orders/default-state';

export default function OrderSubmit() {
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const form: IOrderFormData = useSelect(
        (select) => select(orderStore).getForm(),
        []
    );

    const ordersSaving: boolean = useSelect(
        (select) => select(orderStore).getOrdersSaving(),
        []
    );

    const backToOrdersPage = () => {
        navigate('/orders');
    };

    const validate = () => {
        if (!form.name.length) {
            return __('Please give a order name.', 'botlite');
        }

        if (!form.type.length) {
            return __('Please select order type.', 'botlite');
        }

        if (!form.ticker.length) {
            return __('Please give order ticker.', 'botlite');
        }

        if (!form.exchange.length) {
            return __('Please give order exchange.', 'botlite');
        }

        if (form.close === 0) {
            return __('Please give price.', 'botlite');
        }

        return '';
    };

    const onSubmit = () => {
        //Validate
        if (validate().length > 0) {
            Swal.fire({
                title: __('Error', 'botlite'),
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
        dispatch(orderStore)
            .saveOrder(form)
            .then(() => {
                Swal.fire({
                    title: __('Order saved', 'botlite'),
                    text: __('Order has been saved successfully.', 'botlite'),
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2000,
                });
                dispatch(orderStore).setFormData({
                    ...orderDefaultFormData,
                });
                navigate('/orders');
            })
            .catch((error) => {
                Swal.fire({
                    title: __('Error', 'botlite'),
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
                text={__('Cancel', 'botlite')}
                type="default"
                onClick={backToOrdersPage}
                buttonCustomClass="mr-3"
            />

            <Button
                text={
                    ordersSaving
                        ? __('Saving…', 'botlite')
                        : __('Save', 'botlite')
                }
                type="primary"
                icon={faCheckCircle}
                disabled={ordersSaving}
                onClick={onSubmit}
            />
        </>
    );
}
