/**
 * External dependencies.
 */
import { Link } from 'react-router-dom';
import Swal from 'sweetalert2';
import { Fragment } from '@wordpress/element';
import { Menu, Transition } from '@headlessui/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faEdit,
    faEllipsisV,
    faPencilAlt,
    faTrash,
    faCheckCircle,
} from '@fortawesome/free-solid-svg-icons';
import { __ } from '@wordpress/i18n';
import { dispatch, useSelect } from '@wordpress/data';

/**
 * Internal dependencies.
 */
import alertStore from '../../data/alerts/index';
import { IAlert } from '../../interfaces';
import { prepareAlertDataForDatabase } from '../../data/alerts/utils';

export default function ListItemMenu({ alert }: IAlert) {
    const { id, status } = alert;

    const alertsDeleting: boolean = useSelect(
        (select) => select(alertStore).getAlertsDeleting(),
        []
    );

    const showDeleteAlert = () => {
        Swal.fire({
            title: __('Are you sure?', 'botlite'),
            text: __('Are you sure to delete the alert?', 'botlite'),
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#1c64f2',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return dispatch(alertStore)
                    .deleteAlerts({
                        ids: [id],
                    })
                    .then(() => {
                        return true;
                    })
                    .catch((error) => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.message}`
                        );
                    });
            },
            allowOutsideClick: () => alertsDeleting,
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(__('Deleted!', 'botlite'), '', 'success');
            }
        });
    };

    const changeAlertStatus = () => {
        dispatch(alertStore)
            .saveAlert(
                prepareAlertDataForDatabase({
                    ...alert,
                    is_active: 'published' === status ? 0 : 1,
                })
            )
            .then(() => {
                Swal.fire({
                    title: __('Alert status updated', 'botlite'),
                    text: __('Alert has been updated successfully.', 'botlite'),
                    icon: 'success',
                    toast: true,
                    position: 'bottom',
                    showConfirmButton: false,
                    timer: 2000,
                });
                dispatch(alertStore).setFilters({});
            });
    };

    return (
        <Menu as="div" className="relative inline-block text-left">
            <div>
                <Menu.Button className="inline-flex justify-center w-full pr-3 pl-0 py-2 text-sm font-medium">
                    <FontAwesomeIcon
                        icon={faEllipsisV}
                        color={'#ddd'}
                        className="mr-1"
                    />
                </Menu.Button>
            </div>

            <Transition
                as={Fragment}
                enter="transition ease-out duration-100"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <Menu.Items className="absolute w-40 z-10 top-4 -left-28 mt-2 origin-top-right rounded-md shadow-lg bg-white p-2 border-gray-dark">
                    <div className="px-1 py-1 bg-white">
                        {/*<Menu.Item>
                            <Link
                                to={`/alerts/edit/${id}`}
                                className="text-left hover:opacity-80 block text-slate-600 hover:text-slate-700 group items-center w-full px-3 text-sm bg-white outline-none hover:outline-none focus:outline-none focus:shadow-none mb-2"
                            >
                                <FontAwesomeIcon icon={faPencilAlt} />
                                <span className="ml-2">
                                    {__('Edit', 'botlite')}
                                </span>
                            </Link>
                        </Menu.Item>*/}
                        <Menu.Item>
                            <button
                                onClick={showDeleteAlert}
                                className="text-left hover:opacity-80 block text-slate-600 hover:text-slate-700 group items-center w-full px-3 text-sm bg-white outline-none hover:outline-none focus:outline-none focus:shadow-none mb-2"
                            >
                                <FontAwesomeIcon icon={faTrash} />
                                <span className="ml-2">
                                    {__('Delete', 'botlite')}
                                </span>
                            </button>
                        </Menu.Item>
                        {/*<Menu.Item>
                            <button
                                onClick={changeAlertStatus}
                                className="text-left hover:opacity-80 block text-slate-600 hover:text-slate-700 group items-center w-full px-3 text-sm bg-white outline-none hover:outline-none focus:outline-none focus:shadow-none mb-2"
                            >
                                <span>
                                    {'published' === status ? (
                                        <>
                                            <FontAwesomeIcon icon={faEdit} />{' '}
                                            <span className="ml-2">
                                                {__('Make Draft', 'botlite')}
                                            </span>
                                        </>
                                    ) : (
                                        <>
                                            <FontAwesomeIcon
                                                icon={faCheckCircle}
                                            />{' '}
                                            <span className="ml-2">
                                                {__('Make Publish', 'botlite')}
                                            </span>
                                        </>
                                    )}
                                </span>
                            </button>
                                    </Menu.Item>*/}
                    </div>
                </Menu.Items>
            </Transition>
        </Menu>
    );
}
