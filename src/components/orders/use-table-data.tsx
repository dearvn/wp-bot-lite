/**
 * External dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
// import { Input } from '../inputs/Input';
//import Badge from '../badge/Badge';
import ListItemMenu from './ListItemMenu';
import { ITableHeader, ITableRow } from '../table/TableInterface';
//import { capitalize } from '../../utils/StringHelper';

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
            key: 'interval',
            title: __('Timframe', 'botlite'),
            className: '',
        },
        {
            key: 'entry_price',
            title: __('Entry Price', 'botlite'),
            className: '',
        },
        {
            key: 'entry_at',
            title: __('Entry at time', 'botlite'),
            className: '',
        },
        {
            key: 'exit_price',
            title: __('Exit Price', 'botlite'),
            className: '',
        },
        {
            key: 'exit_at',
            title: __('Exit at time', 'botlite'),
            className: '',
        },
        {
            key: 'gain_loss',
            title: __('Gain/Loss', 'botlite'),
            className: '',
        },
        {
            key: 'actions',
            title: __('Action', 'botlite'),
            className: '',
        },
    ];
};

export const useTableRowData = (orders = [], checked: number[]): ITableRow[] => {
    const rowsData: ITableRow[] = [];

    orders.forEach((row, index) => {
        rowsData.push({
            id: row.id,
            cells: [
                {
                    key: 'sl',
                    value: (
                        // <Input
                        //     value={checked.includes(row.id) ? '1' : '0'}
                        //     type="checkbox"
                        //     //  onChange={() => checkOrder(row.id)}
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
                    key: 'interval',
                    value: row.interval,
                    className: '',
                },
                {
                    key: 'entry_price',
                    value: row.entry_price,
                    className: '',
                },
                {
                    key: 'entry_at',
                    value: row.entry_at,
                    className: '',
                },
                {
                    key: 'exit_price',
                    value: row.exit_price,
                    className: '',
                },
                {
                    key: 'exit_at',
                    value: row.exit_at,
                    className: '',
                },
                {
                    key: 'gain_loss',
                    value: row.gain_loss,
                    className: '',
                },
                {
                    key: 'actions',
                    value: (
                        <div>
                            <ListItemMenu order={row} />
                        </div>
                    ),
                    className: '',
                },
            ],
        });
    });

    return rowsData;
};
