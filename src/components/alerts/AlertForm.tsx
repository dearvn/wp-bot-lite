/**
 * External dependencies.
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import AlertCard from './AlertCard';
import AlertSubmit from './AlertSubmit';
import alertStore from '../../data/alerts';
import AlertFormSidebar from './AlertFormSidebar';
import { IInputResponse, Input } from '../inputs/Input';
import { Select2SingleRow } from '../inputs/Select2Input';
import { IAlert, IAlertFormData } from '../../interfaces';

type Props = {
    alert?: IAlert;
};

export default function AlertForm({ alert }: Props) {
    const dispatch = useDispatch();
    const alertTypes: Array<Select2SingleRow> = useSelect(
        (select) => select(alertStore).getAlertTypes(),
        []
    );

    const cityDropdowns: Array<Select2SingleRow> = useSelect(
        (select) => select(alertStore).getCitiesDropdown(),
        []
    );

    const form: IAlertFormData = useSelect(
        (select) => select(alertStore).getForm(),
        []
    );

    const loadingAlerts: boolean = useSelect(
        (select) => select(alertStore).getLoadingAlerts(),
        []
    );

    const onChange = (input: IInputResponse) => {
        dispatch(alertStore).setFormData({
            ...form,
            [input.name]:
                typeof input.value === 'object'
                    ? input.value?.value
                    : input.value,
        });
    };

    return (
        <div className="mt-10">
            <form>
                <div className="flex flex-col md:flex-row">
                    <div className="md:basis-1/5">
                        <AlertFormSidebar loading={loadingAlerts} />
                    </div>

                    {loadingAlerts ? (
                        <div className="md:basis-4/5">
                            <AlertCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </AlertCard>
                            <AlertCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </AlertCard>
                        </div>
                    ) : (
                        <>
                            <div className="md:basis-4/5">
                                <AlertCard className="alert-general-info">
                                    <Input
                                        type="text"
                                        label={__('Alert title', 'botlite')}
                                        id="title"
                                        placeholder={__(
                                            'Enter Alert title, eg: Software Engineer',
                                            'botlite'
                                        )}
                                        value={form.title}
                                        onChange={onChange}
                                    />
                                    <Input
                                        type="select"
                                        label={__('Alert type', 'botlite')}
                                        id="alert_type_id"
                                        value={form.alert_type_id}
                                        options={alertTypes}
                                        onChange={onChange}
                                    />

                                    <Input
                                        type="select"
                                        label={__('City Name', 'botlite')}
                                        id="city_id"
                                        value={form.city_id}
                                        options={cityDropdowns}
                                        onChange={onChange}
                                    />
                                </AlertCard>
                                <AlertCard className="alert-description-info">
                                    <Input
                                        type="text-editor"
                                        label={__('Alert details', 'botlite')}
                                        id="description"
                                        placeholder={__(
                                            'Enter Alert description and necessary requirements.',
                                            'botlite'
                                        )}
                                        editorHeight="150px"
                                        value={form.description}
                                        onChange={onChange}
                                    />
                                </AlertCard>
                                

                                <div className="flex justify-end md:hidden">
                                    <AlertSubmit />
                                </div>
                            </div>
                        </>
                    )}
                </div>
            </form>
        </div>
    );
}
