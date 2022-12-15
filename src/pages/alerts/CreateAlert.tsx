/**
 * External dependencies
 */
import { useEffect } from '@wordpress/element';
import { dispatch } from '@wordpress/data';
import { useNavigate } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Layout from '../../components/layout/Layout';
import PageHeading from '../../components/layout/PageHeading';
import AlertForm from '../../components/alerts/AlertForm';
import AlertSubmit from '../../components/alerts/AlertSubmit';
import alertStore from '../../data/alerts';
import { alertDefaultFormData } from '../../data/alerts/default-state';

export default function CreateAlert() {
    const navigate = useNavigate();

    const backToAlertsPage = () => {
        navigate('/alerts');
    };

    useEffect(() => {
        dispatch(alertStore).setFormData({
            ...alertDefaultFormData,
        });
    }, []);

    /**
     * Get Page Content - Title and New Alert button.
     *
     * @return JSX.Element
     */
    const pageTitleContent = (
        <div className="">
            <div className="mr-3 mb-4">
                <button
                    onClick={backToAlertsPage}
                    className="text-gray-dark border-none"
                >
                    ← {__('Back to alerts', 'botlite')}
                </button>
            </div>
            <div className="text-left">
                <PageHeading text={__('Create New Alert', 'botlite')} />
            </div>
        </div>
    );

    /**
     * Get Right Side Content - Create Alert form data.
     */
    const pageRightSideContent = (
        <div className="mt-7 fixed invisible md:visible md:top-28 right-10 z-50">
            <AlertSubmit />
        </div>
    );

    return (
        <Layout
            title={pageTitleContent}
            slug="create-alert"
            hasRightSideContent={true}
            rightSideContent={pageRightSideContent}
        >
            <AlertForm />
        </Layout>
    );
}
