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
            key: 'name',
            title: __('Name', 'botlite'),
            className: '',
        },
        {
            key: 'type',
            title: __('Type', 'botlite'),
            className: '',
        },
        {
            key: 'ticker',
            title: __('Ticker', 'botlite'),
            className: '',
        },
        {
            key: 'exchange',
            title: __('Exchange', 'botlite'),
            className: '',
        },
        {
            key: 'close',
            title: __('Close', 'botlite'),
            className: '',
        },
        {
            key: 'created_at',
            title: __('Created at time', 'botlite'),
            className: '',
        },
        {
            key: 'actions',
            title: __('Action', 'botlite'),
            className: '',
        },
    ];
};

export const useTableRowData = (alerts = [], checked: number[]): ITableRow[] => {
    const rowsData: ITableRow[] = [];

    alerts.forEach((row, index) => {
        rowsData.push({
            id: row.id,
            cells: [
                {
                    key: 'sl',
                    value: (
                        // <Input
                        //     value={checked.includes(row.id) ? '1' : '0'}
                        //     type="checkbox"
                        //     //  onChange={() => checkAlert(row.id)}
                        // />
                        <>
                            <b>{index + 1}</b>
                        </>
                    ),
                    className: '',
                },
                {
                    key: 'name',
                    value: row.name,
                    className: '',
                },
                {
                    key: 'type',
                    value: row.type,
                    className: '',
                },
                {
                    key: 'ticker',
                    value: row.ticker,
                    className: '',
                },
                {
                    key: 'exchange',
                    value: row.exchange,
                    className: '',
                },
                {
                    key: 'close',
                    value: row.close,
                    className: '',
                },
                {
                    key: 'created_at',
                    value: row.created_at,
                    className: '',
                },
                {
                    key: 'actions',
                    value: (
                        <div>
                            <ListItemMenu alert={row} />
                        </div>
                    ),
                    className: '',
                },
            ],
        });
    });

    return rowsData;
};
