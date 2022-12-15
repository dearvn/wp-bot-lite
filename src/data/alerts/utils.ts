/**
 * Internal dependencies.
 */
import { IAlert } from '../../interfaces';

export const prepareAlertDataForDatabase = (alert: IAlert) => {
    const data = {
        ...alert,
        alert_type_id: alert.alert_type.id,
        city_id: alert.city.id,
    };

    if (alert.is_active !== undefined) {
        data.is_active = alert.is_active;
    } else {
        data.is_active = 1;
    }

    // Remove unnecessary data.
    delete data.city;
    delete data.alert_type;
    delete data.status;
    delete data._links;

    return data;
};
