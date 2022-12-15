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
import alertStore from '../../data/alerts';
import Button from '../button/Button';
import { IAlertFormData } from '../../interfaces';
import { alertDefaultFormData } from '../../data/alerts/default-state';

export default function AlertSubmit() {
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const form: IAlertFormData = useSelect(
        (select) => select(alertStore).getForm(),
        []
    );

    const alertsSaving: boolean = useSelect(
        (select) => select(alertStore).getAlertsSaving(),
        []
    );

    const backToAlertsPage = () => {
        navigate('/alerts');
    };

    const validate = () => {
        if (!form.name.length) {
            return __('Please give a alert name.', 'botlite');
        }

        if (!form.type.length) {
            return __('Please select alert type.', 'botlite');
        }

        if (!form.ticker.length) {
            return __('Please give alert ticker.', 'botlite');
        }

        if (!form.exchange.length) {
            return __('Please give alert exchange.', 'botlite');
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
        dispatch(alertStore)
            .saveAlert(form)
            .then(() => {
                Swal.fire({
                    title: __('Alert saved', 'botlite'),
                    text: __('Alert has been saved successfully.', 'botlite'),
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2000,
                });
                dispatch(alertStore).setFormData({
                    ...alertDefaultFormData,
                });
                navigate('/alerts');
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
                onClick={backToAlertsPage}
                buttonCustomClass="mr-3"
            />

            <Button
                text={
                    alertsSaving
                        ? __('Saving…', 'botlite')
                        : __('Save', 'botlite')
                }
                type="primary"
                icon={faCheckCircle}
                disabled={alertsSaving}
                onClick={onSubmit}
            />
        </>
    );
}
