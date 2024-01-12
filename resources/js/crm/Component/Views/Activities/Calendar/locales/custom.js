'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

let custom = (vue) => {
    return {
        code: 'custom',
        buttonText: {
            month: vue.$t('calendar_month'),
            week: vue.$t('calendar_week'),
            day: vue.$t('calendar_day'),
        },
    }
};

exports.default = custom;
