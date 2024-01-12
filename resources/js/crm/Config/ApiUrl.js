import {urlGenerator} from "../Helpers/AxiosHelper";


//Update
export const APP_UPDATE = `${urlGenerator('app/updates')}`;
export const APP_UPDATE_INSTALL = `${urlGenerator('app/updates/install')}`;

//Install
export const APP_LOG_IN = `${urlGenerator('admin/users/login')}`;
export const APP_INSTALL_ADMIN_INFO = `${urlGenerator('setup/admin-info')}`;
export const GENERATE_PURCHASE_CODE_URL = `${urlGenerator('setup/generate-url')}`;
export const GET_DATABASE_HOSTNAME = `${urlGenerator('setup/get-database-hostname')}`;
export const GET_UPDATE_URL = `${urlGenerator('app/generated-update-url-purchase-code')}`;
export const SET_UP_EMAIL = `${urlGenerator('setup/email-setup')}`;
export const SET_UP_BROADCAST = `${urlGenerator('setup/broadcast-setup')}`;
export const BROADCAST_SETTING_UPDATE = `${urlGenerator('setup/broadcast-setting-update')}`;
export const BROADCAST_SKIP = `${urlGenerator('setup/broadcast-skip')}`;
export const EMAIL_SETUP_SKIP = `${urlGenerator('setup/email-setup-skip')}`;
export const ADDITIONAL_REQUIREMENTS = `${urlGenerator('setup/additional-requirements')}`;
export const ADDITIONAL_REQUIREMENT = `${urlGenerator('setup/additional-requirement')}`;
export const DATABASE_CONFIGURATION = `${urlGenerator('setup/database')}`;
export const PURCHASE_CODE= `${urlGenerator('setup/purchase-code')}`;
export const PURCHASE_CODE_STORE= `${urlGenerator('setup/purchase-code-store')}`;
export const INSTALL= `${urlGenerator('install')}`;
export const EMAIL_SETTING_UPDATE= `${urlGenerator('setup/email-setting-update-delivery')}`;
export const TEST_MAIL= `${urlGenerator('app/test-mail/send')}`;
export const CLEAR_CACHE= `${urlGenerator('app/clear-cache')}`;



// Invoice
export const INVOICES_SEND = 'invoice-send';
