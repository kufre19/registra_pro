<template>
  <app-modal
    :modal-id="modalId"
    modal-size="default"
    modal-alignment="top"
    :modal-scroll="false"
    @close-modal="closeModal">
    <template slot="header">
      <h5 class="modal-title">
        {{ context === 'organization' ? $t('add_person') : $t('add_organization') }}
      </h5>
      <button
        type="button"
        data-dismiss="modal"
        class="close outline-none"
        @click.prevent="closeModal"
        aria-label="close">
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <app-overlay-loader v-if="preloader"/>
      <form v-else ref="form" :data-url="``" class="mb-0">
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">
              {{ context === 'organization' ? $t("person") : $t('organization') }}
            </label>
            <div class="col-sm-9">
<!--              <app-input-->
<!--                type="search-select"-->
<!--                :error-message="errors[`${context==='person'? 'organization.organization_id' : 'person.person_id'}`] ? $t('this_field_is_required'): ''"-->
<!--                :placeholder="context === 'organization' ? $t('choose_a_person') : $t('choose_an_organization')"-->
<!--                list-value-field="name"-->
<!--                v-model="formData.id"-->
<!--                :list="context === 'organization' ? personList : orgList"-->
<!--              />-->

                <app-input
                    v-model="formData.id"
                    :placeholder="context === 'organization' ? $t('choose_a_person') : $t('choose_an_organization')"
                    :options="context === 'organization' ? personsOptions : organisationOptions"
                    type="search-and-select"
                />

            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">
              {{ $t("job_title") }}
            </label>
            <div class="col-sm-9">
              <app-input
                type="text"
                :placeholder="$t('enter_job_title')"
                v-model="formData.job_title"
              />
            </div>
          </div>
        </div>
      </form>
    </template>
    <template slot="footer">
      <button
        class="btn btn-primary"
        @click.prevent="save">
        {{ $t('save') }}
      </button>
    </template>
  </app-modal>
</template>

<script>
import {mapGetters} from "vuex";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

export default {
  name: "PersonOrganizationModal",
  mixins: [FormMixin],
  props: {
    modalId: {
      type: String,
      required: true
    },
    context: {
      type: String,
      required: true
    },
    selectedIds: {
      type: Array,
      required: true
    },
    isAllRowSelected: Boolean,
    tableId: {}
  },
  data() {
    return {
        organisationOptions: {
            url: route('selectable.organisations'),
            query_name: "name", // by default 'search'
            per_page: 10, // must need to set min 10 per page for showing scrollbar
            modifire: (item) => {
                return { id: item.id, value: item.name }
            },
            params: {},
            loader: "app-pre-loader" // by default 'app-overlay-loader'
        },

        personsOptions: {
            url: route('selectable.persons'),
            query_name: "name", // by default 'search'
            per_page: 10, // must need to set min 10 per page for showing scrollbar
            modifire: (item) => {
                return { id: item.id, value: item.name }
            },
            params: {},
            loader: "app-pre-loader" // by default 'app-overlay-loader'
        },

      errors: {},
      preloader: false,
      formData: {
        id: '',
        job_title: ''
      }
    }
  },
  computed: {
    ...mapGetters({
      // personList: 'getPerson',
      // orgList: 'getOrganization'
    })
  },
    mounted() {
        // this.$store.dispatch("getPerson")
        // this.$store.dispatch("getOrganization")
    },
  methods: {
    closeModal() {
      $(`#${this.modalId}`).modal('hide');
      this.$emit('closeModal');
    },
    save() {
      this.preloader = true;
      this.fieldStatus.isSubmit = true;
      let url,
        data = {
          attachable_ids: this.selectedIds,
          is_all_selected: this.isAllRowSelected
        };
      if (this.context === 'person') {
        url = route('person.bulk-attach-organizations');
        data.organization = {
          organization_id: this.formData.id,
          job_title: this.formData.job_title
        }
      } else {
        url = route('organization.bulk-attach-persons');
        data.person = {
          person_id: this.formData.id,
          job_title: this.formData.job_title
        }
      }
      this.axiosPatch({url, data}).then(res => {
        this.$toastr.s(res.data.message);
        this.closeModal();
        this.$hub.$emit(`reload-${this.tableId}`);
      }).catch(({response}) => {
        this.errors = response.data.errors;
      }).finally(() => {
        this.preloader = false;
      })
    }
  }
}
</script>
