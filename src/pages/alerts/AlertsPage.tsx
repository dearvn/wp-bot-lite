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
import store from '../../data/alerts';
import {
    useTableHeaderData,
    useTableRowData,
} from '../../components/alerts/use-table-data';
import SelectCheckBox from '../../components/alerts/SelectCheckBox';
import { Input } from '../../components/inputs/Input';
import { IAlert, IAlertFilter } from '../../interfaces';

export default function AlertsPage() {
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

    const alerts: Array<IAlert> = useSelect(
        (select) => select(store).getAlerts({}),
        []
    );
    const totalAlerts: number = useSelect(
        (select) => select(store).getTotal(),
        []
    );
    const alertFilters: IAlertFilter = useSelect(
        (select) => select(store).getFilter(),
        []
    );
    const loadingAlerts: boolean = useSelect(
        (select) => select(store).getLoadingAlerts(),
        []
    );

    useEffect(() => {
        dispatch(store).setFilters({
            ...alertFilters,
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
        navigate(`/alerts?pages=${pageData}&s=${searchData}`);
        setPage(pageData);

        dispatch(store).setFilters({
            ...alertFilters,
            page: pageData,
            search: searchData,
        });
    };

    // TODO: Implement this later.
    const [checked, setChecked] = useState<Array<number>>([]);
    const checkAlert = (alertId: number, isChecked = false) => {
        const alertsData = [];
        if (alertId === 0) {
            if (isChecked) {
                alertsData.push(...alerts.map((alert) => alert.id));
            }
            setChecked(alertsData);
        } else {
            setChecked([...checked, alertId]);
        }
    };

    /**
     * Handle Checked and unchecked.
     */
    useEffect(() => {
        if (alerts.length === checked.length && checked.length > 0) {
            setCheckedAll(true);
        } else {
            setCheckedAll(false);
        }
    }, [alerts, checked]);

    /**
     * Get Page Content - Title and New Alert button.
     *
     * @return JSX.Element
     */
    const pageTitleContent = (
        <div className="flex">
            <div className="flex-6 mr-3">
                <PageHeading text={__('Alerts', 'botlite')} />
            </div>
            <div className="flex-1 text-left">
                <Button
                    text={__('New', 'botlite')}
                    type="primary"
                    icon={faPlus}
                    onClick={() => navigate('/alerts/new')}
                />
            </div>
        </div>
    );

    /**
     * Get Right Side Content - Alerts Search Input.
     *
     * @param  data
     */
    const pageRightSideContent = (
        <Input
            type="text"
            placeholder={__('Search Alerts…', 'botlite')}
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
    const tableRows = useTableRowData(alerts, checked);

    return (
        <Layout
            title={pageTitleContent}
            slug="alerts"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            {loadingAlerts ? (
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
                            onChange={(response) => checkAlert()}
                        />
                    )}

                    <Table
                        headers={tableHeaders}
                        rows={tableRows}
                        totalItems={totalAlerts}
                        perPage={10}
                        onCheckAll={(isChecked: boolean) => {
                            checkAlert(0, isChecked);
                            setCheckedAll(isChecked);
                        }}
                        responsiveColumns={tableResponsiveColumns}
                        checkedAll={checkedAll}
                        noDataMessage={__(
                            'Sorry !! No alerts found…',
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
