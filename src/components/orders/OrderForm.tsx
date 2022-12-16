/**
 * External dependencies.
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import OrderCard from './OrderCard';
import OrderSubmit from './OrderSubmit';
import orderStore from '../../data/orders';
import OrderFormSidebar from './OrderFormSidebar';
import { IInputResponse, Input } from '../inputs/Input';
//import { Select2SingleRow } from '../inputs/Select2Input';
import { IOrder, IOrderFormData } from '../../interfaces';

type Props = {
    order?: IOrder;
};

export default function OrderForm({ order }: Props) {
    const dispatch = useDispatch();
    /*const orderTypes: Array<Select2SingleRow> = useSelect(
        (select) => select(orderStore).getOrderTypes(),
        []
    );

    const cityDropdowns: Array<Select2SingleRow> = useSelect(
        (select) => select(orderStore).getCitiesDropdown(),
        []
    );*/

    const form: IOrderFormData = useSelect(
        (select) => select(orderStore).getForm(),
        []
    );

    const loadingOrders: boolean = useSelect(
        (select) => select(orderStore).getLoadingOrders(),
        []
    );

    const onChange = (input: IInputResponse) => {
        dispatch(orderStore).setFormData({
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
                        <OrderFormSidebar loading={loadingOrders} />
                    </div>

                    {loadingOrders ? (
                        <div className="md:basis-4/5">
                            <OrderCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </OrderCard>
                            <OrderCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </OrderCard>
                        </div>
                    ) : (
                        <>
                            <div className="md:basis-4/5">
                                <OrderCard className="order-general-info">
                                    <Input
                                        type="text"
                                        label={__('Order Name', 'botlite')}
                                        id="name"
                                        placeholder={__(
                                            'Enter Order Name',
                                            'botlite'
                                        )}
                                        value={form.name}
                                        onChange={onChange}
                                    />
                                    <Input
                                        type="text"
                                        label={__('Type', 'botlite')}
                                        id="type"
                                        value={form.type}
                                        onChange={onChange}
                                    />

                                    <Input
                                        type="text"
                                        label={__('Ticker', 'botlite')}
                                        id="ticker"
                                        value={form.ticker}
                                        onChange={onChange}
                                    />

                                    <Input
                                        type="text"
                                        label={__('Exchange', 'botlite')}
                                        id="exchange"
                                        value={form.exchange}
                                        onChange={onChange}
                                    />

                                    <Input
                                        type="number"
                                        label={__('Cose', 'botlite')}
                                        id="close"
                                        value={form.close}
                                        onChange={onChange}
                                    />
                                </OrderCard>
                                {/*<OrderCard className="order-description-info">
                                    <Input
                                        type="text-editor"
                                        label={__('Order details', 'botlite')}
                                        id="description"
                                        placeholder={__(
                                            'Enter Order description and necessary requirements.',
                                            'botlite'
                                        )}
                                        editorHeight="150px"
                                        value={form.description}
                                        onChange={onChange}
                                    />
                                        </OrderCard>*/}
                                

                                <div className="flex justify-end md:hidden">
                                    <OrderSubmit />
                                </div>
                            </div>
                        </>
                    )}
                </div>
            </form>
        </div>
    );
}
