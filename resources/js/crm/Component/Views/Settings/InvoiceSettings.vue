<template>
    <section class="invoice-settings">
        <app-note :notes="$t('invoice_setting_warning_note')" :title="$t('warning')" />
        <app-overlay-loader v-if="preloader"/>
        <form v-else
            ref="form"
            :data-url="route('setting.invoice_update')"
            :class="{'loading-opacity': preloader}"
            class="mb-0 mt-3"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label class="col-lg-3 mb-lg-0" for="invoiceStartingNumber">
                            {{ $t('invoice_starting_number') }}<br>
                        </label>
                        <div class="col-lg-8">
                            <app-input
                                id="invoiceStartingNumber"
                                v-model="formData.invoice_starting_number"
                                :error-message="$errorMessage(errors, 'invoice_starting_number')"
                                type="number"
                            />
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="invoice-prefix" class="col-lg-3 mb-lg-0">
                            {{ $t('invoice_prefix') }} <br />
                        </label>
                        <div class="col-lg-8">
                            <app-input
                                id="invoice-prefix"
                                v-model="formData.invoice_prefix"
                                :error-message="$errorMessage(errors, 'invoice_prefix')"
                                type="text"
                            />
                        </div>
                    </div>

                </div>
            </div>

            <div v-if="$can('manage_invoice_setting')" class="mt-5 action-buttons">
                <button class="btn btn-primary mr-2" @click.prevent="handleSubmit">
                    {{ $t('save') }}
                </button>
            </div>

        </form>
    </section>
</template>

<script>
import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";

export default {
    name: 'invoice-settings',
    mixins: [FormSubmitMixin],
    props: {},
    data() {
        return {
            route,
            editorShow: false,
            preloader: false,
            errors: {},
            formData: {
                invoice_starting_number:'',
                invoice_prefix:'',
            },
        }
    },
    methods: {
        handleInvoiceLogoChange() {
            this.companyLogo = true;
        },
        handleSubmit() {
            this.save(this.formData);
        },
        handleError({data: { errors }}) {
            this.errors = errors;
        },
        handleSuccess({data: { message }}) {
            this.$toastr.s(message);
        },
        getInvoiceData() {
            this.preloader = true;
            this.axiosGet('get-invoice-setting-data')
                .then(({data}) => {
                    data.map(setting => {
                        if (setting.name === 'invoice_starting_number'){
                            this.formData.invoice_starting_number = setting.value
                        }
                        if (setting.name === 'invoice_prefix'){
                            this.formData.invoice_prefix = setting.value
                        }
                    })
                })
               .finally(() => this.preloader = false);
        },
    },
    created() {
        this.getInvoiceData();
    },
}
</script>
