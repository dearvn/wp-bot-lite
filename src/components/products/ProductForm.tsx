/**
 * External dependencies.
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import ProductCard from './ProductCard';
import ProductSubmit from './ProductSubmit';
import productStore from '../../data/products';
import ProductFormSidebar from './ProductFormSidebar';
import { IInputResponse, Input } from '../inputs/Input';
import { Select2SingleRow } from '../inputs/Select2Input';
import { IProduct, IProductFormData } from '../../interfaces';

type Props = {
    product?: IProduct;
};

export default function ProductForm({ product }: Props) {
    const dispatch = useDispatch();
    const productTypes: Array<Select2SingleRow> = useSelect(
        (select) => select(productStore).getProductTypes(),
        []
    );

    const cityDropdowns: Array<Select2SingleRow> = useSelect(
        (select) => select(productStore).getCitiesDropdown(),
        []
    );

    const form: IProductFormData = useSelect(
        (select) => select(productStore).getForm(),
        []
    );

    const loadingProducts: boolean = useSelect(
        (select) => select(productStore).getLoadingProducts(),
        []
    );

    const onChange = (input: IInputResponse) => {
        dispatch(productStore).setFormData({
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
                        <ProductFormSidebar loading={loadingProducts} />
                    </div>

                    {loadingProducts ? (
                        <div className="md:basis-4/5">
                            <ProductCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </ProductCard>
                            <ProductCard>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                                <div className="animate-pulse h-4 bg-slate-100 w-full p-2.5 rounded-lg mt-5"></div>
                            </ProductCard>
                        </div>
                    ) : (
                        <>
                            <div className="md:basis-4/5">
                                <ProductCard className="product-general-info">
                                    <Input
                                        type="text"
                                        label={__('Product title', 'landlite')}
                                        id="title"
                                        placeholder={__(
                                            'Enter Product title, eg: Software Engineer',
                                            'landlite'
                                        )}
                                        value={form.title}
                                        onChange={onChange}
                                    />
                                    <Input
                                        type="select"
                                        label={__('Product type', 'landlite')}
                                        id="product_type_id"
                                        value={form.product_type_id}
                                        options={productTypes}
                                        onChange={onChange}
                                    />

                                    <Input
                                        type="select"
                                        label={__('City Name', 'landlite')}
                                        id="city_id"
                                        value={form.city_id}
                                        options={cityDropdowns}
                                        onChange={onChange}
                                    />
                                </ProductCard>
                                <ProductCard className="product-description-info">
                                    <Input
                                        type="text-editor"
                                        label={__('Product details', 'landlite')}
                                        id="description"
                                        placeholder={__(
                                            'Enter Product description and necessary requirements.',
                                            'landlite'
                                        )}
                                        editorHeight="150px"
                                        value={form.description}
                                        onChange={onChange}
                                    />
                                </ProductCard>
                                

                                <div className="flex justify-end md:hidden">
                                    <ProductSubmit />
                                </div>
                            </div>
                        </>
                    )}
                </div>
            </form>
        </div>
    );
}
