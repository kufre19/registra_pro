<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('area_of_expenses')"
                    :directory="[$t('Expense'), $t('area_of_expense')]"
                    :icon="'users'"
                />
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
                    <div class="dropdown d-inline-block btn-dropdown">
                        <div class="dropdown-menu"></div>
                    </div>

                    <button
                        v-if="$can('create_expense_area')"
                        type="button"
                        class="btn btn-primary btn-with-shadow"
                        data-toggle="modal"
                        @click.prevent="openModal()"
                    >
                        {{ $t("add_area_of_expense") }}
                    </button>
                </div>
            </div>
        </div>

        <app-table
            :id="tableId"
            :options="options"
            @action="getAction"
        />

        <app-expense-area-modal
            v-if="isExpenseAreaModalActive"
            :selected-url="selectedExpenseAreaUrl"
            :table-id="tableId"
            @close-modal="closeModal">
        </app-expense-area-modal>

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="area-of-expense-delete-modal"
            @cancelled="cancelled"
            @confirmed="confirmed"
        />
    </div>
</template>
<script>
import {DeleteMixin} from "../../../Mixins/Global/DeleteMixin";
import {getCustomFileds} from "../../../Mixins/Global/CustomFieldMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {numberWithCurrencySymbol, textTruncate} from "../../../Helpers/helpers";

export default {
    name: "AreaOfExpenses",
    extends: CoreLibrary,
    mixins: [
        DeleteMixin,
        getCustomFileds
    ],
    data() {
        return {
            route,
            isModalActive: false,
            selectedExpenseAreaUrl: '',
            isExpenseAreaModalActive: false,
            tableId: "area-of-expense",
            confirmationModalActive: false,
            personId: null,
            selectedUrl: "",
            invoiceRouteId: null,
            invoiceStatusId: null,
            commonColumn: [
            {
                title: this.$t('name'),
                type: 'text',
                key: 'name',
            },
            {
                title: this.$t('description'),
                type: 'custom-html',
                key: 'description',
                modifier: (value) => {
                    return value ? `<p class="mb-0">${textTruncate(value, 80)}</p>` : '-';
                },
            },
            {
                title: this.$t('total_expense_in_this_area'),
                type: 'custom-html',
                key: 'total_amount',
                titleAlignment: 'right',
                modifier: (value) => {
                    return `<p class="text-right mb-0">${numberWithCurrencySymbol(value)}</p>`
                },
            },
            {
                title: this.$t('actions'),
                type: 'action',
                key: 'invoice',
                isVisible: true
            },
            ],
            options: {
                name: this.$t("area_of_expense_table"),
                url: route("expense-area.index"),
                showHeader: true,
                enableHighlights: false,
                enableSaveFilter: false,
                columns: [],
                filters: [],
                showSearch: true,
                // searchPlaceholder: 'Search by invoice number',
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
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        modifier: () => this.$can("update_expense_area"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        modifier: () => this.$can("delete_expense_area"),
                    },
                ],
                showCount: true,
                showClearFilter: true,
            },
        };
    },
    methods: {
        getAction(rowData, actionObj, active) {
            if (actionObj.title == this.$t("edit")) {
                this.selectedExpenseAreaUrl = route("expense-area.show", { id: rowData.id });
                this.isExpenseAreaModalActive = true;
            } else if (actionObj.title == this.$t("delete")) {
                this.deleteUrl = route("expense-area.destroy", { id: rowData.id });
                this.confirmationModalActive = true;
            }
        },
        openModal() {
            this.isExpenseAreaModalActive = true;
            $('#expense-area-modal').modal('show');
        },
        closeModal() {
            this.isExpenseAreaModalActive = false;
            this.selectedUrl = "";
            this.selectedExpenseAreaUrl = '';
            $("#expense-area-modal").modal("hide");
        },
    },
    computed: {},
    mounted() {
       this.getCustomFiled("areaOfExpense"); //not used but will generate error without it
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
