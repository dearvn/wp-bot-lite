/**
 * Internal dependencies.
 */
import { ISelect2Input } from '../components/inputs/Select2Input';

export interface IProduct {
    /**
     * Product ID.
     */
    id: number;

    /**
     * Product title.
     */
    title: string;

    /**
     * Product description.
     */
    description: string;

    /**
     * Product Type ID.
     */
    product_type_id: number;

    /**
     * City ID.
     */
    city_id: number;

    /**
     * Status published or draft
     */
    is_active: boolean | number;

    /**
     * Product status.
     */
    status?: 'draft' | 'published' | 'trashed';
}

export interface IProductFormData extends IProduct {}

export interface IProducts {
    /**
     * All city list dropdown as array of {label, value}.
     */
    cityDropdowns: Array<ISelect2Input>;

    /**
     * All products as array of IProduct.
     */
    products: Array<IProduct>;

    /**
     * Product detail.
     */
    product: IProduct;

    /**
     * Product saving or not.
     */
    productsSaving: boolean;

    /**
     * Product deleting or not.
     */
    productsDeleting: boolean;

    /**
     * All product types as array of {label, value}.
     */
    productTypes: Array<ISelect2Input>;

    /**
     * Is products loading.
     */
    loadingProducts: boolean;

    /**
     * Count total page.
     */
    totalPage: number;

    /**
     * Count total number of data.
     */
    total: number;

    /**
     * Product list filter.
     */
    filters: object;

    /**
     * Product Form data.
     */
    form: IProductFormData;
}

export interface IProductFilter {
    /**
     * Product filter by page no.
     */
    page?: number;

    /**
     * Product search URL params.
     */
    search?: string;
}

export interface IProductTypes {
    /**
     * Product type id.
     */
    id: number;

    /**
     * Product type name.
     */
    name: string;

    /**
     * Product type slug.
     */
    slug: string;

    /**
     * Product type description.
     */
    description: string | null;
}

export interface ICityDropdown {
    /**
     * City id.
     */
    id: number;

    /**
     * City name.
     */
    name: string;

    /**
     * City code.
     */
    code: string;

}
