/**
 * External dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import { useNavigate } from 'react-router-dom';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Button from '../../components/button/Button';
import Layout from '../../components/layout/Layout';
import Table from '../../components/table/Table';
import TableLoading from '../../components/loading/TableLoading';
import PageHeading from '../../components/layout/PageHeading';
import { useSelect, useDispatch } from '@wordpress/data';
import store from '../../data/orders';
import {
    useTableHeaderData,
    useTableRowData,
} from '../../components/orders/use-table-data';
import SelectCheckBox from '../../components/orders/SelectCheckBox';
import { Input } from '../../components/inputs/Input';
import { IOrder, IOrderFilter } from '../../interfaces';

export default function OrdersPage() {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [page, setPage] = useState(
        new URLSearchParams(location.search).get('pages') || 1
    );
    const searched = new URLSearchParams(location.search).get('s');
    const [search, setSearch] = useState<string>(
        typeof searched === 'string' ? searched : ''
    );
    const [checkedAll, setCheckedAll] = useState(false);

    const orders: Array<IOrder> = useSelect(
        (select) => select(store).getOrders({}),
        []
    );
    const totalOrders: number = useSelect(
        (select) => select(store).getTotal(),
        []
    );
    const orderFilters: IOrderFilter = useSelect(
        (select) => select(store).getFilter(),
        []
    );
    const loadingOrders: boolean = useSelect(
        (select) => select(store).getLoadingOrders(),
        []
    );

    useEffect(() => {
        dispatch(store).setFilters({
            ...orderFilters,
            page,
            search,
        });
    }, [page, search]);

    /**
     * Process search-bar, tab and pagination clicks.
     *
     * @param  pagePassed
     * @param  searchPassed
     * @return {void}
     */
    const processAndNavigate = (
        pagePassed: number = 0,
        searchPassed: string | null = null
    ) => {
        const pageData = pagePassed === 0 ? page : pagePassed;
        const searchData = searchPassed === '' ? search : searchPassed;

        navigate(`/orders?pages=${pageData}&s=${searchData}`);
        setPage(pageData);

        dispatch(store).setFilters({
            ...orderFilters,
            page: pageData,
            search: searchData,
        });
    };

    // TODO: Implement this later.
    const [checked, setChecked] = useState<Array<number>>([]);
    const checkOrder = (orderId: number, isChecked = false) => {
        const ordersData = [];
        if (orderId === 0) {
            if (isChecked) {
                ordersData.push(...orders.map((order) => order.id));
            }
            setChecked(ordersData);
        } else {
            setChecked([...checked, orderId]);
        }
    };

    /**
     * Handle Checked and unchecked.
     */
    useEffect(() => {
        if (orders.length === checked.length && checked.length > 0) {
            setCheckedAll(true);
        } else {
            setCheckedAll(false);
        }
    }, [orders, checked]);

    /**
     * Get Page Content - Title and New Order button.
     *
     * @return JSX.Element
     */
    const pageTitleContent = (
        <div className="flex">
            <div className="flex-6 mr-3">
                <PageHeading text={__('Orders', 'botlite')} />
            </div>
            {/*<div className="flex-1 text-left">
                <Button
                    text={__('New', 'botlite')}
                    type="primary"
                    icon={faPlus}
                    onClick={() => navigate('/orders/new')}
                />
            </div>*/}
        </div>
    );

    /**
     * Get Right Side Content - Orders Search Input.
     *
     * @param  data
     */
    const pageRightSideContent = (
        <Input
            type="text"
            placeholder={__('Search Orders…', 'botlite')}
            onChange={(data) => {
                setSearch(data.value);
                processAndNavigate(page, data.value);
            }}
            value={search}
            className="w-full md:w-80"
        />
    );

    const tableResponsiveColumns = ['sl', 'title', 'actions'];
    const tableHeaders = useTableHeaderData();
    const tableRows = useTableRowData(orders, checked);

    return (
        <Layout
            title={pageTitleContent}
            slug="orders"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            {loadingOrders ? (
                <TableLoading
                    headers={tableHeaders}
                    responsiveColumns={tableResponsiveColumns}
                    hasCheckbox={false}
                    count={5}
                />
            ) : (
                <>
                    {checked.length > 0 && (
                        <SelectCheckBox
                            checked={checked}
                            onChange={(response) => checkOrder()}
                        />
                    )}

                    <Table
                        headers={tableHeaders}
                        rows={tableRows}
                        totalItems={totalOrders}
                        perPage={10}
                        onCheckAll={(isChecked: boolean) => {
                            checkOrder(0, isChecked);
                            setCheckedAll(isChecked);
                        }}
                        responsiveColumns={tableResponsiveColumns}
                        checkedAll={checkedAll}
                        noDataMessage={__(
                            'Sorry !! No orders found…',
                            'botlite'
                        )}
                        currentPage={
                            typeof page === 'number' ? parseInt(page) : 1
                        }
                        onChangePage={(page) =>
                            processAndNavigate(page, search)
                        }
                    />
                </>
            )}
        </Layout>
    );
}
