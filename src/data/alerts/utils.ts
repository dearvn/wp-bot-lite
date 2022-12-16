/**
 * Internal dependencies.
 */
import { IAlert } from '../../interfaces';

export const prepareAlertDataForDatabase = (alert: IAlert) => {
    const data = {
        ...alert,
    };

    delete data._links;

    return data;
};
