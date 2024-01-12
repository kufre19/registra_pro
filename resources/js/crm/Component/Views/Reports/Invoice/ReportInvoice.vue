<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('invoices')"
                    :directory="[$t('reports'), $t('invoice')]"
                    :icon="'users'"
                />
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
                    <div class="dropdown d-inline-block btn-dropdown">
                        <div class="dropdown-menu"></div>
                    </div>

                    <button
                        v-if="$can('create_invoice')"
                        type="button"
                        class="btn btn-primary btn-with-shadow"
                        data-toggle="modal"
                        @click.prevent="openModal()"
                    >
                        {{ $t("create_invoice") }}
                    </button>
                </div>
            </div>
        </div>

        <app-table
            :id="tableId"
            :options="options"
            @getRows="afterBulkSelect"
            @action="getAction"
        />

        <app-invoice-modal
            v-if="isInvoiceModalActive"
            :selected-url="selectedInvoiceUrl"
            :table-id="tableId"
            @close-modal="closeModal">
        </app-invoice-modal>

        <!-- new confirmation modal -->
        <app-confirmation-modal
            v-if="isInvoiceStatusModalActive"
            icon="alert-circle"
            modal-id="invoice-status-modal"
            :message="$t('you_are_going_to_change_the_payment_status')"
            @confirmed="paymentStatusChangeConfirm"
            @cancelled="closeModal"
        />

        <app-deal-person-send-invoice
            v-if="isSendModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @close-modal="sendInvoiceCloseModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="invoice-delete-modal"
            @cancelled="cancelled"
            @confirmed="confirmed"
        />

        <lead-bulk-action
            v-if="isBulkActionActive"
            :table-id="tableId"
            :context="bulkContext"
            :selected-leads="selectedRows"
            :is-all-row-selected="isAllRowSelected"
            @addLead="addLeadToOpposite"
            @deleteSelected="deleteBulkLead"
        />
        <app-confirmation-modal
            v-if="bulkDeleteModal"
            modal-id="bulk-delete-modal"
            @cancelled="bulkDeleteCancel"
            @confirmed="bulkDeleteConfirmed"
        />
    </div>
</template>
<script>
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import { collection } from "@app/Helpers/helpers";
import { mapGetters } from "vuex";
import AppFunction from "../../../../../core/helpers/app/AppFunction";


import {
    invoiceStatus,
    owner,
    person,
    contactType
} from "@app/Mixins/Global/FilterMixins";

import { getCustomFileds } from "@app/Mixins/Global/CustomFieldMixin";
import { DeleteMixin } from "@app/Mixins/Global/DeleteMixin";
import LeadBulkActionMixin from "../../Contacts/BulkAction/LeadBulkActionMixin";
import {numberWithCurrencySymbol} from "../../../../Helpers/helpers";

export default {
    name: "ReportInvoice",
    extends: CoreLibrary,
    mixins: [
        DeleteMixin,
        getCustomFileds,
        invoiceStatus,
        LeadBulkActionMixin,
        owner,
        person,
        contactType
    ],
    data() {
        return {
            route,
            numberWithCurrencySymbol,
            invoiceSettings: {},
            isModalActive: false,
            isInvoiceStatusModalActive: false,
            selectedInvoiceUrl: '',
            isInvoiceModalActive: false,
            isContactModalActive: false,
            isSendModalActive: false,
            invoiceStatusTableId: 'invoice-status-table',
            tableId: "invoice-modal",
            contacttableId: "contact-modal",
            confirmationModalActive: false,
            organizationModal: false,
            personActivitiesModal: false,
            editedUrl: "",
            viewAllDeal: false,
            viewAllFollower: false,
            isNoteModal: false,
            noteRowData: null,
            personId: null,
            selectedUrl: "",
            activityData: {},
            followerUrl: "",
            bulkContext: 'invoice',
            updatedInvoice: null,
            invoiceRouteId: null,
            invoiceStatusId: null,
            commonColumn: [
                {
                    title: this.$t("invoice_number"),
                    type: "object",
                    key: "invoice_number",
                    modifier: (value, row) => {
                        return this.invoiceSettings ? this.invoiceSettings.invoice_prefix+value : value;
                    }
                },
                {
                    title: this.$t("deal_name"),
                    type: "object",
                    key: "deal",
                    modifier: (value, row) => {
                        return value.title;
                    }
                },
                {
                    title: this.$t("status"),
                    type: "custom-html",
                    key: "status",
                    modifier: (value, row) => {
                        return `<span class="badge badge-pill badge-${
                            value.class ?? 'secondary'
                        }">${this.$t(value.name) }</span>`;
                    },
                },
                {
                    title: this.$t("leads"),
                    type: "object",
                    key: "deal",
                    modifier: (value, row) => {
                        return value?.contact_person[0]?.name;
                    }
                },
                {
                    title: this.$t("invoice_amount"),
                    type: "object",
                    key:"total",
                    modifier: (value, row) => {
                        return numberWithCurrencySymbol(value);
                    }
                },
                {
                    title: this.$t("owner"),
                    type: "custom-html",
                    key: "deal",
                    modifier: (value, row) => {
                        return value?.owner?.full_name ?? `<p class="m-0 font-size-90 text-secondary"> ${this.$t("user_deleted")}</p>`;
                    }
                },
                {
                    title: this.$t("tax"),
                    type: "object",
                    key: "tax",
                    modifier: (value, row) => {
                        return value ? value.name : '-';
                    }
                },
                {
                    title: this.$t("action"),
                    type: "action",
                    key: "invoice",
                    isVisible: true,
                }
            ],
            options: {
                name: this.$t("invoice_table"),
                url: route("invoice.index"),
                showHeader: true,
                enableHighlights: false,
                enableSaveFilter: false,
                columns: [],
                filters: [
                    {
                        title: this.$t("created_date"),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                        permission: this.$can("view_invoice") ? true : false, //for all
                    },
                    {
                        title: this.$t("owner"),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: window.user.roles.map(role => role.name).includes('App Admin')
                            ||  window.user.roles.map(role => role.name).includes('Manager'),
                    },
                    {
                        title: this.$t("payment_status"),
                        type: "checkbox",
                        key: "status",
                        option: [],
                        permission: window.user.roles.map( role => role.name).includes('App Admin')
                            || window.user.roles.map( role => role.name).includes('Agent')
                            ||  window.user.roles.map(role => role.name).includes('Manager'),
                    },
                    //Disable for now, will available later. But full functional
                    // {
                    //     title: this.$t("person"),
                    //     type: "multi-select-filter",
                    //     key: "person",
                    //     option: [],
                    //     permission: this.$can('view_persons') ? true : false
                    // },
                ],
                showSearch: true,
                searchPlaceholder: 'Search by invoice number',
                showFilter: true,
                paginationType: "pagination",
                enableRowSelect: false,
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: "desc",
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t("send_to_email"),
                        icon: "zap",
                        type: "modal",
                        modifier: () => this.$can("invoice_sending_attachment_mail"),
                    },
                    {
                        title: this.$t('action_invoice_download'),
                        type: 'download',
                        modifier: () => this.$can("download_invoice"),
                    },
                    {
                        title: this.$t('change_to_unpaid'),
                        key: 'change_to_unpaid',
                        icon: 'edit',
                        type: 'action',
                        modalId: 'invoice-status-modal',
                        modifier: (row) => this.$can("update_invoice") && row.status.name === 'status_paid'
                    },
                    {
                        title: this.$t('change_to_paid'),
                        key: 'change_to_paid',
                        icon: 'edit',
                        type: 'action',
                        modalId: 'invoice-status-modal',
                        modifier: (row) => this.$can("update_invoice") && row.status.name === 'status_unpaid'
                    },
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        component: "app-invoice-modal",
                        modalId: "invoice-modal",
                        url: "",
                        modifier: () => this.$can("update_invoice"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "invoice-delete-modal",
                        url: "",
                        modifier: () => this.$can("delete_invoice"),
                    },
                ],
                showCount: true,
                showClearFilter: true,
            },
        };
    },
    methods: {
        getAction(rowData, actionObj, active) {
            if (actionObj.title == this.$t("send_to_email")) {
                this.isSendModalActive = true;
                this.selectedUrl = route("get_deal_contact_person.invoice", { id: rowData.id });

            }else if (actionObj.type === 'action') {
                const [{ id: unpaidStatusId }, { id: paidStatusId }] = this.invoiceStatus;
                const paymentStatusUpdatedInvoice = {
                    ...rowData,
                    status_id: actionObj.key === "change_to_paid"
                        ? paidStatusId
                        : unpaidStatusId
                };

                this.updatedInvoice = paymentStatusUpdatedInvoice;
                this.invoiceRouteId =  rowData.id;
                this.invoiceStatusId =  rowData.status_id;
                this.isInvoiceStatusModalActive = true;
            } else if (actionObj.type === 'download') {
                window.open(AppFunction.getAppUrl(`invoice/${rowData.id}/generate-pdf`));
            }else if (actionObj.title == this.$t("edit")) {
                this.selectedInvoiceUrl = route("invoice.show", { id: rowData.id });
                this.isInvoiceModalActive = true;
            } else if (actionObj.title == this.$t("delete")) {
                this.deleteUrl = route("invoice.destroy", { id: rowData.id });
                this.confirmationModalActive = true;
            }
        },
        openModal() {
            this.isInvoiceModalActive = true;
            $('#invoice-modal').modal('show');
        },
        openContactModal() {
            this.isContactModalActive = true;
        },
        closeContactModal() {
            this.isContactModalActive = false;
            this.selectedUrl = "";
            $("#contact-type-modal").modal("hide");
        },
        closeModal() {
            this.isInvoiceModalActive = false;
            this.isInvoiceStatusModalActive = false;
            this.selectedUrl = "";
            this.selectedInvoiceUrl = '';
            this.updatedInvoice = null;
            this.invoiceRouteId = null
            this.invoiceStatusId = null
            $("#invoice-status-modal").modal("hide");
            $("#invoice-modal").modal("hide");
            $("#person-modal").modal("hide");
        },
        sendInvoiceCloseModal() {
            this.selectedUrl = "";
            this.isSendModalActive = false;
            $("#send-invoice-modal").modal("hide");
        },
        async paymentStatusChangeConfirm() {
            try {
                const response = await this.axiosPatch({
                    url: route("invoice.update", { id: this.invoiceRouteId, status_id: this.invoiceStatusId }),
                    data: this.updatedInvoice,
                })
                this.$toastr.s(response.data.message);
                this.$hub.$emit("reload-" + this.tableId);
            } catch (error) {
                this.$toastr.e(error.response.data.message);
            }
            this.closeModal();
        },
    },
    computed: {
        ...mapGetters({
            invoiceStatus: "getInvoiceStatus",
        }),
    },
    mounted() {
       this.getCustomFiled("invoice");
       this.$store.dispatch('getInvoiceStatus');
       this.invoiceSettings = window.settings;
    },
    created() {},
};

</script>
<style>
.person .link-list {
    white-space: nowrap !important;
    max-width: 150px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
