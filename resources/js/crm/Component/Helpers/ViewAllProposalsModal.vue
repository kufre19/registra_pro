<template>
  <app-modal
    modal-id="details-view-modal"
    modal-size="full-width"
    modal-alignment="top"
    modal-body-class="p-0"
    @close-modal="closeModal"
  >

    <template slot="header">
      <h4 class="ml-4">{{ $t("all_proposals") }}</h4>
      <button
        type="button"
        class="close outline-none"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <div class="p-3">
        <app-table :id="'proposal-common-table'" :options="options" @action="getAction" />
      </div>
    </template>
    <template slot="footer">
      <button
        type="button"
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        @click.prevent="closeModal"
      >
        {{ $t("close") }}
      </button>
    </template>
  </app-modal>
</template>

<script>
export default {
  name: "ViewAllProposalsModal",
  props: {
    proposalData: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      options: {
        name: "proposalTable",
        url: this.proposalData,
        showHeader: true,
        tableShadow: false,
        columns: [
            {
                title: this.$t("subject"),
                type: "text",
                key: "subject",
            },
            {
                title: this.$t("status"),
                type: "custom-html",
                key: "status",
                modifier: (value, row) => {
                    return `<span class="badge badge-pill badge-${
                        value.class ?? "secondary"
                    }">${this.$t(value.name)}</span>`;
                },
            },
            {
                title: this.$t("owner"),         // object type column
                type: 'object',
                key: 'owner',
                modifier: (value, row) => {
                    return value.full_name;
                }
            },
        ],
        showFilter: false,
        showSearch: false,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        orderBy: "desc",
      },
    };
  },
  methods: {
    getAction() {},
    closeModal(value) {
      this.$emit("close-modal", value);
    },
  },
};
</script>
