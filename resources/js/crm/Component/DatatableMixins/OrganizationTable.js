import {collection, urlGenerator} from "../../Helpers/helpers";


export default {
    data() {
        return {
            commonColumn: [
                {
                    title: this.$t("name"),
                    type: "component",
                    key: "profile_picture",
                    data: 'organization',
                    isVisible: true,
                    componentName: "app-media-name-column",
                },
                {
                    title: this.$t("lead_groups"),
                    type: "custom-html",
                    key: "contact_type",
                    modifier: (value, row) => {
                        return value
                            ? `<span class="badge badge-sm badge-pill badge-${value.class ?? "secondary"}">${
                                value.name
                            }</span>`
                            : "-";
                    },
                },
                {
                    title: this.$t("person"),
                    type: "component",
                    key: "id",
                    componentName: 'app-common-person',
                },
                {
                    title: this.$t("address"),
                    type: "component",
                    isVisible: true,
                    componentName: "app-common-address",
                },
                {
                    title: this.$t("closed_deal"),
                    type: "text",
                    key: "close_deals_count",
                },
                {
                    title: this.$t("opened_deal"),
                    type: "text",
                    key: "open_deals_count",
                },
                {
                    title: this.$t("owner"),
                    type: "custom-html",
                    key: "owner",
                    modifier: (value, row) => {
                        return value ? value.full_name
                            : `<p class="m-0 font-size-90 text-secondary">` +
                            this.$t("user_deleted") +
                            `</p>`;
                    },
                },
                {
                    title: this.$t("tags"),
                    type: "component",
                    key: "tags",
                    isVisible: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
                    componentName: "tags-type-column",
                },
                {
                    title: this.$t("action"),
                    type: "action",
                    key: "invoice",
                    isVisible: (this.$can('update_organizations') || this.$can('delete_organizations')) ? true : false,
                },
            ],

            options: {
                name: "OrganizationTable",
                url: route('organizations.index'),
                showHeader: true,
                columns: [],
                filters: [
                    {
                        title: this.$t("created_date"),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                    },
                    {
                        title: this.$t("owner"),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: this.$can('manage_public_access') ? true : false
                    },

                    {
                        title: this.$t("lead_group"),
                        type: "checkbox",
                        key: "contact_type",
                        option: [],
                    },
                    // {
                    //     title: this.$t("person"),
                    //     type: "multi-select-filter",
                    //     key: "person",
                    //     option: [],
                    //     permission: this.$can('view_persons') ? true : false
                    // },
                    // {
                    //     title: this.$t("tag"),
                    //     type: "multi-select-filter",
                    //     key: "tags",
                    //     option: [],
                    //     permission: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
                    // },

                    {
                        title: this.$t("tag"),
                        type: "search-and-select-filter",
                        key: "tags",
                        option: [],
                        settings: {
                            url: route('selectable.tags'),
                            modifire: (v) => {
                                return { id: v.id, name: v.name }
                            },
                            per_page: 10, //must need to set min 10 per_page for showing pagination scrollbar
                            queryName: "name",
                            loader: "app-pre-loader"
                        },
                        listValueField: "name",
                        permission: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
                    },

                ],
                showSearch: true,
                enableRowSelect: window.user.roles[0].name === 'Client' ? false : true, //client will not see the bulk option
                enableHighlights: true,
                enableSaveFilter: true,
                showFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                showManageColumn: !this.$can('manage_public_access') && this.$can('client_access') ? false : true,
                orderBy: "desc",
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        component: "app-organization-modal",
                        modalId: "organization-modal",
                        url: "",
                        modifier: () => this.$can("update_organizations"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "organization-delete-modal",
                        url: "",
                        modifier: () => this.$can("delete_organizations"),
                    },
                ],
                showCount: true,
                showClearFilter: true,
            }
        }
    }
}
