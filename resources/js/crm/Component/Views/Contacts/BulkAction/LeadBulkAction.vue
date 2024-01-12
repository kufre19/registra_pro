<template>
  <div class="bulk-floating-action-wrapper">
    <div class="actions">
      <!-- Person <-> Organization -->
      <div class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open"
           :title="context === 'organization' ? $t('add_person') : $t('add_organization')"
           data-toggle="tooltip">
        <button
          class="btn btn-light dropdown-toggle border-right"
          type="button"
          @click.prevent="addLead">
          <app-icon v-if="context === 'organization'" name="user-plus"/>
          <app-icon v-else name="briefcase"/>
        </button>
      </div>

      <!-- Update Lead Group -->
      <div class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open"
           :title="$t('change_lead_group')"
           data-toggle="tooltip">
        <button
          aria-expanded="false"
          aria-haspopup="true"
          class="btn btn-light dropdown-toggle border-right"
          data-toggle="dropdown"
          type="button"
          id="leadGroup">
          <app-icon name="users"/>
        </button>
        <div
          class="dropdown-menu p-primary"
          style="width: 500px"
          aria-labelledby="leadGroup">
          <form ref="form" :data-url="``" class="mb-0">
            <h5 class="mb-3">{{ $t('change_lead_group') }}</h5>
            <div class="form-group">
              <div class="form-row">
                <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t('lead_group') }}
                </label>
                <div class="col-sm-9">
                  <app-input
                    type="select"
                    list-value-field="name"
                    v-model="contactTypeId"
                    :list="leadGroupList"
                  />
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <button
                class="btn btn-primary"
                @click.prevent="changeLeadGroup">
                <span v-if="preloader" class="w-100">
                    <app-submit-button-loader/>
                  </span>
                <template v-else>{{ $t('save') }}</template>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Update Lead Group -->
      <div class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open"
           :title="$t('change_owner')"
           data-toggle="tooltip">
        <button
          aria-expanded="false"
          aria-haspopup="true"
          class="btn btn-light dropdown-toggle border-right"
          data-toggle="dropdown"
          type="button"
          id="ownerUpdate">
          <app-icon name="shield"/>
        </button>
        <div
          class="dropdown-menu p-primary"
          style="width: 500px"
          aria-labelledby="ownerUpdate">
          <form ref="form" :data-url="``" class="mb-0">
            <h5 class="mb-3">{{ $t('change_owner') }}</h5>
            <div class="form-group">
              <div class="form-row">
                <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t('owner') }}
                </label>
                <div class="col-sm-9">
                  <app-input
                    type="select"
                    list-value-field="full_name"
                    v-model="ownerId"
                    :list="ownerList"
                  />
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <button
                class="btn btn-primary"
                @click.prevent="changeOwner">
                <span v-if="preloader" class="w-100">
                    <app-submit-button-loader/>
                  </span>
                <template v-else>{{ $t('save') }}</template>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!--Tag manager-->
      <div v-if="!isAllRowSelected"
        class="dropdown d-inline-block btn-dropdown"
        :title="$t('manage_tag')"
        data-toggle="tooltip">
        <bulk-action-tag-manager
          :tags="allSelectedTags"
          :list="tagList"
          list-value-field="name"
          color-value-field="color_code"
          :tag-preloader="tagPreloader"
          @storeTag="storeAndAttachTag"
          @attachTag="attachTag"
          @detachTag="detachTag"
        />
      </div>
      <!--Delete-->
      <div
        class="dropdown d-inline-block btn-dropdown"
        :title="$t('delete')"
        data-toggle="tooltip">
        <button
          class="btn btn-light dropdown-toggle border-right"
          type="button"
          @click.prevent="deleteDeal">
          <app-icon name="trash-2"/>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {mapGetters} from "vuex";

export default {
  name: "LeadBulkAction",
  mixins: [FormMixin],
  props: {
    selectedLeads: {
      type: Array,
      required: true
    },
    isAllRowSelected: Boolean,
    context: {
      type: String,
      required: true
    },
    tableId: {}
  },
  data() {
    return {
      selectedDataTags: [],
      contactTypeId: '',
      ownerId: '',
      tagPreloader: false,
      preloader: false
    }
  },
  computed: {
    ...mapGetters({
      ownerList: 'getOwner',
      leadGroupList: 'contentType',
      tagList: 'getAllTags'
    }),
    selectedIds() {
      return this.selectedLeads.map(item => item.id);
    },
    allSelectedTags() {
      let allTagsOfSelectedLeads = this.selectedLeads.reduce((result, item) => {
        return result.concat(item.tags)
      }, []);
      return [...new Set(allTagsOfSelectedLeads.map(el => el.id))]
    }
  },
  watch: {
    ownerList: {
      handler: function (data) {
        this.ownerId = data.length ? data[0].id : ''
      },
      immediate: true
    },
    leadGroupList: {
      handler: function (data) {
        this.contactTypeId = data.length ? data[0].id : ''
      },
      immediate: true
    }
  },
  methods: {
    deleteDeal() {
      this.$emit('deleteSelected')
    },
    changeLeadGroup() {
      this.preloader = true;
      let url = route(`${this.context}.bulk-update-lead-group`),
        data = {
          attachable_ids: this.selectedIds,
          contact_type_id: this.contactTypeId,
          is_all_selected: this.isAllRowSelected
        };
      this.axiosPatch({url, data}).then((res) => {
        this.$toastr.s(res.data.message);
        this.preloader = false;
      }).catch(({response}) => {
        this.$toastr.e(response.data.message);
      }).finally(() => {
        this.$hub.$emit(`reload-${this.tableId}`);
      })
    },
    changeOwner() {
      this.preloader = true;
      let url = route(`${this.context}.bulk-update-owner`),
        data = {
          attachable_ids: this.selectedIds,
          owner_id: this.ownerId,
          is_all_selected: this.isAllRowSelected
        };
      this.axiosPatch({url, data}).then((res) => {
        this.$toastr.s(res.data.message);
        this.preloader = false;
      }).catch(({response}) => {
        this.$toastr.e(response.data.message);
      }).finally(() => {
        this.$hub.$emit(`reload-${this.tableId}`);
      })
    },
    storeAndAttachTag({name, color_code}) {
      this.axiosPost({
        url: route("tags.index"),
        data: {name, color_code},
      })
      .then((response) => {
        this.$store.dispatch("getAllTags");
        this.attachTag(response.data.id);
      })
      .catch((error) => this.$toastr.e(error.response.data.errors.name[0]));
    },
    attachTag(tag_id) {
      this.axiosPatch({
        url: route(`${this.context}.bulk-attach-tags`),
        data: {
          tag_id,
          attachable_ids: this.selectedIds,
        },
      }).then((response) => {
        this.$toastr.s(response.data.message);
        this.$hub.$emit(`reload-${this.tableId}`);
      });
    },
    detachTag(tag_id) {
      this.axiosPatch({
        url: route(`${this.context}.bulk-detach-tags`),
        data: {
          tag_id,
          detachable_ids: this.selectedIds,
        },
      }).then((response) => {
        this.$toastr.s(response.data.message);
        this.$hub.$emit(`reload-${this.tableId}`);
      });
    },
    addLead() {
      this.$emit('addLead');
    }
  }
}
</script>
