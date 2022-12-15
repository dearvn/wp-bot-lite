/**
 * External dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
// import { Input } from '../inputs/Input';
import Badge from '../badge/Badge';
import ListItemMenu from './ListItemMenu';
import { ITableHeader, ITableRow } from '../table/TableInterface';
import { capitalize } from '../../utils/StringHelper';

export const useTableHeaderData = (): ITableHeader[] => {
    return [
        {
            key: 'sl',
            title: 'Sl',
            className: '',
        },
        {
            key: 'title',
            title: __('Product', 'landlite'),
            className: '',
        },
        {
            key: 'product_type',
            title: __('Product type', 'landlite'),
            className: '',
        },
        {
            key: 'city',
            title: __('City', 'landlite'),
            className: '',
        },
        {
            key: 'status',
            title: __('Status', 'landlite'),
            className: '',
        },
        {
            key: 'actions',
            title: __('Action', 'landlite'),
            className: '',
        },
    ];
};

export const useTableRowData = (products = [], checked: number[]): ITableRow[] => {
    const rowsData: ITableRow[] = [];

    products.forEach((row, index) => {
        rowsData.push({
            id: row.id,
            cells: [
                {
                    key: 'sl',
                    value: (
                        // <Input
                        //     value={checked.includes(row.id) ? '1' : '0'}
                        //     type="checkbox"
                        //     //  onChange={() => checkProduct(row.id)}
                        // />
                        <>
                            <b>{index + 1}</b>
                        </>
                    ),
                    className: '',
                },
                {
                    key: 'title',
                    value: row.title,
                    className: '',
                },
                {
                    key: 'product_type',
                    value: row.product_type?.name,
                    className: '',
                },
                {
                    key: 'city',
                    value: (
                        <div className="flex">
                            <div className="flex-6">{row.city?.name}</div>
                        </div>
                    ),
                    className: '',
                },
                {
                    key: 'status',
                    value: (
                        <Badge
                            text={capitalize(row.status)}
                            type={
                                row.status === 'published'
                                    ? 'success'
                                    : 'default'
                            }
                            hasIcon={true}
                        />
                    ),
                    className: '',
                },
                {
                    key: 'actions',
                    value: (
                        <div>
                            <ListItemMenu product={row} />
                        </div>
                    ),
                    className: '',
                },
            ],
        });
    });

    return rowsData;
};
