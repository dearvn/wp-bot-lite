/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useNavigate } from 'react-router-dom';

/**
 * Internal dependencies.
 */
import Button from '../button/Button';

const Dashboard = () => {
    const navigate = useNavigate();
    return (
        <div className="dashboard mx-8">
            <div className="card p-5">
                <h3 className="font-medium text-lg">
                    {__('Dashboard', 'botlite')}
                </h3>
                <div className="mt-4">
                    <Button
                        type="primary"
                        style={{ backgroundColor: '#00a0d2' }}
                        text={__('View Alerts', 'botlite')}
                        onClick={() => navigate('/alerts')}
                    />
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
