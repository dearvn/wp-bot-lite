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
        <div className="dashboard mx-12">
            <div className="card p-6">
                <h3 className="font-medium text-lg">
                    {__('Alert Summary', 'botlite')}
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
            <div className="card p-6">
                <h3 className="font-medium text-lg">
                    {__('Order Summary', 'botlite')}
                </h3>
                <div className="mt-4">
                    <Button
                        type="primary"
                        style={{ backgroundColor: '#00a0d2' }}
                        text={__('View Orders', 'botlite')}
                        onClick={() => navigate('/orders')}
                    />
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
