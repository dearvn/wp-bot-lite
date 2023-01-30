/**
 * External dependencies.
 */
import {useNavigate} from 'react-router-dom';
import {useDispatch, useSelect} from '@wordpress/data';
import {faCheckCircle} from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import Button from '../button/Button';
import {ISettingFormData} from '../../interfaces';
import settingStore from "../../data/settings";

export default function SettingSubmit() {
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const form: ISettingFormData = useSelect(
        (select) => select(settingStore).getForm(),
        []
    );

    const settingsSaving: boolean = useSelect(
        (select) => select(settingStore).getSettingsSaving(),
        []
    );

    const backToDashboardPage = () => {
        navigate('/');
    };

    const validate = () => {
        if (form.enable_trading === 'yes' && (!form.api_key.length || !form.secret_key.length)) {
            return __('Please give an API & Secret key.', 'botlite');
        }

        return '';
    };

    const onSubmit = () => {
        // Validate
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
        dispatch(settingStore)
            .saveSettings(form)
            .then(() => {
                Swal.fire({
                    title: __('Settings saved', 'botlite'),
                    text: __('Settings has been saved successfully.', 'botlite'),
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2000,
                });

                // make a reload.
                location.reload();
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
                onClick={backToDashboardPage}
                buttonCustomClass="mr-3"
            />

            <Button
                text={
                    settingsSaving
                        ? __('Saving…', 'botlite')
                        : __('Save', 'botlite')
                }
                type="default"
                buttonCustomClass={'bg-gradient-to-r from-gradient-from to-gradient-to !border-0'}
                textClassName={'text-white'}
                iconCustomClass={'text-white'}
                icon={faCheckCircle}
                disabled={settingsSaving}
                onClick={onSubmit}
            />
        </>
    );
}
