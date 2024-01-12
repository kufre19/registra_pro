<template>
  <div class="card border-0 card-with-shadow">
    <div
      class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent"
    >
      <h5 v-show="isProposalsActive" class="card-title text-muted m-0">
        {{ $t("Proposals") }} ({{ proposalData.proposals.length }})
      </h5>
    </div>

    <div class="card-body">
        <div v-if="proposalData.proposals" v-show="isProposalsActive">
<!--            <div class="mb-4 pb-4">-->
            <div>
                <template v-if="proposalData.proposals.length">
                    <div
                        class="
                    d-flex
                    align-items-center
                    justify-content-between
                    font-size-90
                    mb-2
                  "
                        v-for="(proposal, index) in proposalData.proposals"
                        :key="proposal.id"
                        v-if="index < 3"
                    >
                        <a>{{ textTruncate(proposal.subject, 22, "...") }}</a>
                        <span :class="`badge badge-pill badge-${proposal.status.class}`">
                    {{ proposal.status.translated_name }}
                  </span>
                    </div>
                    <div
                        v-if="proposalData.proposals.length > 3"
                        class="mb-4 d-flex justify-content-center">
                        <button class="btn btn-secondary" type="button" @click.prevent="viewAll()">
                            {{ $t("view_all") }}
                        </button>
                    </div>
                </template>

                <template v-else>
                    <p class="text-muted">{{ $t("no_porposal_for_deal") }}</p>
                </template>
            </div>
        </div>

      <app-common-all-proposals
        v-if="viewAllModal"
        :proposal-data="proposalUrl"
        @close-modal="closedViewModal"
      />
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import {
    urlGenerator,
    textTruncate
} from "@app/Helpers/helpers";

export default {
  name: "DetailsProposal",
  mixins: [FormMixin],
  props: {
    proposalData: {
      required: true,
    },
    getProposalUrl: {
      type: String,
    },
    quickView: {
      type: Boolean,
      required: false,
    },
    statusCheck: {
      default: true,
      required: false,
    },
    permissionCheck: {
      default: true,
      required: false,
    },
  },
  data() {
    return {
      textTruncate,
      urlGenerator,
      isProposalsActive: true,
      viewAllModal: false,
      errors: {},
      proposalUrl: "",
    };
  },
  methods: {
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.isProposalsActive = true;
      this.statusCheck != true;
      this.errors = {};
      this.$emit("update-request");
    },
    followerEdit() {
      this.isProposalsActive = false;
    },
    getProposals() {
      this.proposalUrl = this.getProposalUrl;
    },
    viewAll() {
      this.quickView
        ? this.$emit("viewAllProposal", this.proposalUrl)
        : (this.viewAllModal = true);
    },
    closedViewModal() {
      this.viewAllModal = false;
    },
  },

  created() {
    this.getProposals();
  },
};
</script>
