<template>
  <app-modal
    modal-alignment="top"
    modal-id="tax-modal"
    modal-size="default"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">{{ selectedUrl ? $t('edit') : $t('add') }}</h5>
      <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>

    <template slot="body">
      <form ref="form" :data-url="selectedUrl ? selectedUrl : route('tax.store')">
        <div class="form-group">
          <label>{{ $t('name') }}</label>
          <app-input
            v-model="formData.name"
            :placeholder="$t('enter_tax_name')"
            :required="true"
          />
          <span v-if="customErrorMessage" class="text-danger">{{ customErrorMessage }}</span>
          <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
        </div>

        <div class="form-group">
          <label>{{ $t('tax_value') }}</label>
          <app-input
              type="number"
              v-model="formData.value"
              :placeholder="$t('enter_tax_value')"
              :required="true"
          />
          <span v-if="customErrorMessage" class="text-danger">{{ customErrorMessage }}</span>
          <span v-if="errors.value" class="text-danger">{{ errors.value[0] }}</span>
        </div>

      </form>
    </template>
    <template slot="footer">
      <button
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        type="button"
        @click.prevent="closeModal"
      >{{ $t('cancel') }}
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

import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {mapGetters} from "vuex";

export default {
  name: "TaxModal",
  mixins: [FormMixin],
  props: {
    tableId: String,
  },
  data() {
    return {
        route,
      formData: {},
      errors: [],
      loading: false,
      customErrorMessage: '',
    };
  },
  computed: {},
  methods: {
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
        this.save(this.formData);
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit("reload-" + this.tableId);
      this.closeModal(this.tableId);
    },

    afterFinalResponse() {
      this.loading = false;
    },
    afterSuccessFromGetEditData(response) {
        this.formData = response.data;
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
  },
  mounted() {},
};
</script>


