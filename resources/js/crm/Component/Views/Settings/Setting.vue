<template>
    <div class="content-wrapper">
        <app-breadcrumb
            :page-title="$t('settings')"
            :directory="$t('settings')"
            :icon="'settings'"
        />
        <app-tab v-if="dataLoaded" :tabs="tabs" :icon="tabIcon"/>
    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin";

export default {
    name: "Setting",
    props: {
        marketPlaceVersion: {
            default: false,
        },
        appUrl: {},
    },
    mixins: [FormMixin],

    data() {
        return {
            dataLoaded: false,
            loggedInUser: {},
            tabIcon: "settings",
            tabs: [
                {
                    name: this.$t("general"),
                    title: this.$t("general"),
                    component: "app-general-settings",
                    permission: "",
                },
                {
                    name: this.$t('cron_job'),
                    title: this.$t('cron_job'),
                    component: "app-cron-job-settings",
                    props: {alias: 'tenant'},
                },
                {
                    name: this.$t("custom_fields"),
                    title: this.$t("custom_fields"),
                    component: "app-custom-field",
                    permission: "",
                    headerButton: {
                        label: this.$t("add_custom_field"),
                        class: "btn btn-primary",
                    },
                },
                {
                    name: this.$t("email_setup"),
                    title: this.$t("email_setup"),
                    component: "app-email-setup",
                    permission: "",
                    props: "",
                },
                {
                    name: this.$t("broadcast_setup"),
                    title: this.$t("broadcast_setup"),
                    component: "app-broadcast-setup",
                    permission: "",
                    props: "",
                },
                {
                    name: this.$t("notification"),
                    title: this.$t("notification"),
                    component: "app-notification-settings",
                    permission: "",
                },
                {
                    name: this.$t("invoice"),
                    title: this.$t("invoice"),
                    component: "invoice-settings",
                    permission: this.$can('manage-invoice-setting'),
                    props: "",
                },
                {
                    name: this.$t("tag"),
                    title: this.$t("tag"),
                    component: "app-tag",
                    permission: this.$can('view-tags'),
                    // headerButton: {
                    //     label: this.$t("add_tax"),
                    //     class: "btn btn-primary",
                    // },
                },
                {
                    name: this.$t("tax"),
                    title: this.$t("tax"),
                    component: "app-tax",
                    permission: "",
                    headerButton: {
                        label: this.$t("add_tax"),
                        class: "btn btn-primary",
                    },
                },
                {
                    name: this.$t("person_web_form"),
                    title: this.$t("person_web_form"),
                    component: "app-lead-web-form-setting",
                    permission: this.$can('manage-person'),
                    props: "",
                },
                {
                    name: this.$t("update"),
                    title: this.$t("update"),
                    component: "app-update",
                    permission: "",
                    props: "",
                },
            ],
        };
    },

    mounted() {
        this.tabs.forEach((el) => {
            el["props"] = {
                isMarketPlaceVersion: this.marketPlaceVersion,
                appUrl: this.appUrl,
            };
        });
        this.dataLoaded = true;
    },
};
</script>
