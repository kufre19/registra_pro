<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('expenses')"
                    :directory="[$t('Expense'), $t('expense')]"
                    :icon="'users'"
                />
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
                    <div class="dropdown d-inline-block btn-dropdown">
                        <div class="dropdown-menu"></div>
                    </div>

                    <button
                        v-if="$can('create_expense')"
                        type="button"
                        class="btn btn-primary btn-with-shadow"
                        data-toggle="modal"
                        @click.prevent="openModal()"
                    >
                        {{ $t("add_expense") }}
                    </button>
                </div>
            </div>
        </div>

        <app-table
            :id="tableId"
            :options="options"
            @action="getAction"
        />

        <expense-create-edit-modal
            v-if="isExpenseModalActive"
            :selected-url="selectedExpenseUrl"
            :table-id="tableId"
            @close-modal="closeModal">
        </expense-create-edit-modal>

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="expense-delete-modal"
            @cancelled="cancelled"
            @confirmed="confirmed"
        />
    </div>
</template>
<script>
import {DeleteMixin} from "../../../Mixins/Global/DeleteMixin";
import {getCustomFileds} from "../../../Mixins/Global/CustomFieldMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {numberWithCurrencySymbol, textTruncate, formatDateToLocal} from "../../../Helpers/helpers";

export default {
    name: "Expenses",
    extends: CoreLibrary,
    mixins: [
        DeleteMixin,
        getCustomFileds
    ],
    data() {
        return {
            route,
            isModalActive: false,
            selectedExpenseUrl: '',
            isExpenseModalActive: false,
            tableId: "expense",
            confirmationModalActive: false,
            personId: null,
            selectedUrl: "",
            commonColumn: [
                {
                    title: this.$t('name'),
                    type: 'text',
                    key: 'name',
                },
                {
                    title: this.$t('area_of_expense'),
                    type: 'object',
                    key: 'expense_area',
                    modifier: value => value?.name
                },
                {
                    title: this.$t('amount'),
                    type: 'custom-html',
                    key: 'amount',
                    // titleAlignment: 'right',
                    modifier: value => `<p class="mb-0">${value ? numberWithCurrencySymbol(value) : '-'}</'p>`
                },
                {
                    title: this.$t('expense_date'),
                    type: 'custom-html',
                    key: 'expense_date',
                    isVisible: true,
                    modifier: expense_date => expense_date ? formatDateToLocal(expense_date) : '-'
                },
                {
                    title: this.$t('description'),
                    type: 'custom-html',
                    key: 'description',
                    modifier: value => value ? `<p class="mb-0">${textTruncate(value, 80)}</p>` : '-'
                },
                {
                    title: this.$t('attachments'),
                    type: 'component',
                    componentName: 'app-attachments-column',
                    key: 'attachments',
                },
                {
                    title: this.$t('created_by'),
                    type: 'object',
                    key: 'created_by',
                    modifier: value => value?.full_name
                },
                {
                    title: this.$t('actions'),
                    type: 'action',
                    key: 'expense',
                    isVisible: true
                },
            ],
            options: {
                name: this.$t("expense_table"),
                url: route("expense.index"),
                showHeader: true,
                enableHighlights: false,
                enableSaveFilter: false,
                columns: [],
                filters: [
                    {
                        title: this.$t("date"),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                        permission: this.$can("view_expense") ? true : false,
                    },
                    {
                        title: this.$t("expense_date"),
                        type: "range-picker",
                        key: "expense_date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                        permission: this.$can("view_expense") ? true : false, //for all
                    },
                    {
                        title: this.$t("expense_area"),
                        type: "search-and-select-filter",
                        key: "expense_area",
                        settings: {
                            url: route('selectable.expense_areas'),
                            modifire: (v) => {
                                return { id: v.id, name: v.name }
                            },
                            per_page: 10, //must need to set min 10 per_page for showing pagination scrollbar
                            queryName: "name",
                            loader: "app-pre-loader"
                        },
                        listValueField: "name"
                    },
                    {
                        title: this.$t("created_by"),
                        type: "search-and-select-filter",
                        key: "created_by",
                        settings: {
                            url: route('selectable.owners'),
                            modifire: (v) => {
                                return { id: v.id, name: v.full_name }
                            },
                            per_page: 10, //must need to set min 10 per_page for showing pagination scrollbar
                            queryName: "name",
                            loader: "app-pre-loader"
                        },
                        listValueField: "name"
                    },
                ],
                showSearch: true,
                searchPlaceholder: 'Search by name',
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
                        modifier: () => this.$can("update_expense"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        modifier: () => this.$can("delete_expense"),
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
                this.selectedExpenseUrl = route("expense.show", { id: rowData.id });
                this.isExpenseModalActive = true;
            } else if (actionObj.title == this.$t("delete")) {
                this.deleteUrl = route("expense.destroy", { id: rowData.id });
                this.confirmationModalActive = true;
            }
        },
        openModal() {
            this.isExpenseModalActive = true;
            $('#expense-modal').modal('show');
        },
        closeModal() {
            this.isExpenseModalActive = false;
            this.selectedUrl = "";
            this.selectedExpenseUrl = '';
            $("#expense-modal").modal("hide");
        },
    },
    computed: {},
    mounted() {
        this.getCustomFiled("expense"); //not used but will generate error without it
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
