<template>
    <app-modal
        modal-id="expense-area-modal"
        modal-size="large"
        modal-alignment="top"
        @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">
                {{ selectedUrl ? $t("edit_expense_area") : $t("add_expense_area") }}
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
            <form ref="form" v-if="dataLoaded" :data-url="selectedUrl ? selectedUrl : route('expense-area.store')">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">{{ $t('name') }}</label>
                            <app-input
                                :error-message="$errorMessage(errors, 'name')"
                                id="name"
                                v-model="formData.name"
                                type="text"
                            />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">{{ $t('description') }}</label>
                            <app-input
                                :error-message="$errorMessage(errors, 'description')"
                                id="description"
                                v-model="formData.description"
                                type="text"
                            />
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
import { numberWithCurrencySymbol } from "../../../Helpers/helpers";

export default {
    name: "ExpenseInvoiceModal",
    mixins: [FormSubmitMixin],
    props: ['selectedUrl'],
    data() {
        return {
            errors: [],
            numberWithCurrencySymbol,
            route,
            dataLoaded: false,
            formData: {
                name: '',
                description: '',
            }
        };
    },
    computed: {},

    methods: {
        resetFormData(){
            this.formData.name = ''
            this.formData.description = ''
        },
        submit() {
            // let formData = this.formData;
            this.save(this.formData);
        },
        afterSuccessFromGetEditData(response) {
            this.dataLoaded = true;
            this.formData = response.data;
        },
    },
    mounted() {
        if(!this.selectedUrl) this.dataLoaded = true
    },
};
</script>
