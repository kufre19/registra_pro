<template>
    <app-modal modal-alignment="top" modal-id="invoice-status-modal" modal-size="large" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('invoice_payment_status_change') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <form ref="form" :data-url="selectedUrl ? selectedUrl : route('invoice.store')">
               <!-- adding some bottom padding   -->
                <div class="form-group row padding-bottom">
                    <div class="mb-0 col-sm-3 d-flex align-items-center">
                        <label>{{ $t('status') }}</label>
                    </div>
                    <div class="col-sm-6">
                        <app-input
                            v-model="formData.status_id"
                            :error-message="$errorMessage(errors, 'status_id')"
                            :list="invoiceStatus"
                            :required="true"
                            :placeholder="$t('choose_a_status')"
                            list-value-field="translated_name"
                            type="search-select"
                        />
                    </div>
                </div>
            </form>
        </template>
        <template slot="footer">
            <button class="btn btn-secondary mr-2" data-dismiss="modal" type="button" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button class="btn btn-primary" type="button" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t('save') }}</template>
            </button>
        </template>
    </app-modal>
</template>
<script>

import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";
import { mapGetters } from 'vuex'
export default {
    name: "InvoiceStatusModal",
    mixins: [FormSubmitMixin],
    data() {
        return {
            route,
            formData: {},
        }
    },
    computed:{
        ...mapGetters({
            invoiceStatus: "getInvoiceStatus",
        })
    },
    methods: {
        submitData() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.$store.dispatch('getInvoiceStatus');
            this.closeModal();
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
        },
    },
    mounted(){
        this.$store.dispatch('getInvoiceStatus');
    }
}
</script>

<style>
.btn-purple {
    color: #ffffff;
    background-color: #964ed8;
}

.btn.btn-purple:hover {
    color: #ffffff;
    background-color: #964EED;
}

.padding-bottom {
    padding-bottom: 10rem;
}
</style>
