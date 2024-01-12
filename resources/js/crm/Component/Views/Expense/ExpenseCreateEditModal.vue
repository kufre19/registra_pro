<template>
    <app-modal
        modal-id="expense-modal"
        modal-size="large"
        modal-alignment="top"
        @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">
                {{ selectedUrl ? $t("edit_expense") : $t("add_expense") }}
            </h5>
            <button
                type="button"
                class="close outline-none"
                data-dismiss="modal"
                aria-label="Close">
                <span>
                  <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form ref="form" v-if="dataLoaded" :data-url="selectedUrl ? selectedUrl : route('expense.store')">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">{{ $t('name') }}</label>
                            <app-input
                                :error-message="$errorMessage(errors, 'name')"
                                :placeholder="$t('name')"
                                id="name"
                                v-model="formData.name"
                                type="text"
                            />
                        </div>

                        <div class="form-group">
                            <label for="expense_area_id">{{ $t('expense_area') }}</label>
                            <app-input
                                v-model="formData.expense_area_id"
                                :placeholder="$t('choose_an_expense_area')"
                                :options="expenseAreaOptions"
                                type="search-and-select"
                                :error-message="$errorMessage(errors, 'expense_area_id')"
                            />
                        </div>

                        <div class="form-group">
                            <label for="amount">{{ $t('amount') }}</label>
                            <app-input
                                v-model="formData.amount"
                                :label="$t('amount')+' '+`${'('+getCurrencySymbol()+')'}`"
                                :placeholder="$t('amount')"
                                type="currency"
                                :error-message="$errorMessage(errors, 'amount')"
                            />
                        </div>

                        <div class="form-group">
                            <label for="expense_date">{{ $t('expense_date') }}</label>
                            <app-input
                                v-model="formData.expense_date"
                                :label="$t('expense_date')"
                                :placeholder="$t('expense_date')"
                                type="date"/>
                        </div>

                        <div class="form-group">
                            <label for="description">{{ $t('description') }}</label>
                            <app-input
                                type="textarea"
                                v-model="formData.description"
                                :label="$t('description')"
                                :placeholder="$t('description')"
                            />
                        </div>
                        <div class="form-group mb-0">
                            <label>
                                {{ $t('attachments') }}
                            </label>
                            <app-input
                                type="dropzone"
                                v-model="formData.attachments"
                                @file-removed="handleDropzoneFileRemove"
                                :error-message="$errorMessage(errors, 'attachments.0')"
                            />
                            <small class="text-muted">{{ $t('expense_attachment_allowed_file_types') }}</small>
                        </div>
                    </div>
                </div>
            </form>
            <app-overlay-loader v-else/>
        </template>

        <template slot="footer">
            <button
                type="button"
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                @click.prevent="closeModal"
            >
                {{ $t("cancel") }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submit">
                <span class="w-100">
                  <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>
        </template>
    </app-modal>
</template>
<script>

import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";
import {numberWithCurrencySymbol, getCurrencySymbol, formDataAssigner} from "../../../Helpers/helpers";
import {formatDateForServer} from "../../../../../../package/installer/src/resources/js/Helpers/DateTimeHelper";

export default {
    name: "ExpenseCreateEditModal",
    mixins: [FormSubmitMixin],
    props: ['selectedUrl'],
    data() {
        return {
            errors: [],
            numberWithCurrencySymbol,
            route,
            dataLoaded: false,
            attachments: [],
            formData: {
                name: '',
                expense_area_id: '',
                amount: '',
                expense_date: '',
                attachments: [],
                remove_attachments: [],
                description: '',
            },
            expenseAreaOptions: {
                url: route('selectable.expense_areas'),
                query_name: "name", // by default 'search'
                per_page: 10, // must need to set min 10 per page for showing scrollbar
                modifire: (item) => {
                    return { id: item.id, value: item.name }
                },
                params: {},
                loader: "app-pre-loader" // by default 'app-overlay-loader'
            },
            getCurrencySymbol,
        };
    },
    computed: {},

    methods: {
        resetFormData(){
            this.formData.name = ''
            this.formData.expense_area_id = ''
            this.formData.amount = ''
            this.formData.expense_date = ''
            this.formData.attachments = []
            this.formData.remove_attachments = []
            this.formData.description = ''
            this.attachments = []
        },
        handleDropzoneFileRemove(file) {
            const fileToRemove = this.attachments.find(attachment => attachment.path === file.dataURL);
            if (!fileToRemove) return;
            this.formData.remove_attachments.push(fileToRemove)
        },
        submit() {
            let formData = new FormData();
            formData.append('name', this.formData.name)
            formData.append('expense_area_id', this.formData.expense_area_id)
            formData.append('amount', this.formData.amount)
            formData.append('expense_date', this.formData.expense_date)
            formData.append('description', this.formData.description)

            if (this.selectedUrl) formData.append('_method', 'patch')
            formData.append('expense_date', formatDateForServer(this.formData.expense_date))
            // this.save(formData);

            this.formData.attachments.forEach(file => {
                if (file instanceof File) {
                    formData.append('attachments[]', file)
                }
            })

            if (this.selectedUrl) {
                this.formData.remove_attachments.forEach(file => {
                    if (!(file instanceof File)) {
                        console.log(file);
                        formData.append('remove_attachments[]', JSON.stringify(file))
                    }
                })
            }

            return this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl :  route('expense.store'),
                formData
            );
        },
        afterSuccessFromGetEditData(response) {
            this.attachments = response.data.attachments;
            this.formData = response.data;
            this.formData.attachments = response.data.attachments.map((attachment) => attachment.path)
            this.formData.remove_attachments = []
            this.dataLoaded = true;
        },
    },
    mounted() {
        if(!this.selectedUrl) this.dataLoaded = true
    },
};
</script>
