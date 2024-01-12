<template>
  <app-modal
    modal-id="note-modal"
    modal-size="large"
    modal-alignment="top"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">
        {{ note.id ? $t("edit") : $t("add") }} {{ $t("note_lowercase") }}
      </h5>
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
    <app-overlay-loader v-if="dataLoaded" />
    <template v-else slot="body">
      <form
        class="mb-0"
        ref="form"
        :data-url="route(`activities.update-note`, { id: note.id })"
      >
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("title")
            }}</label>
            <div class="col-sm-10">
              <app-input type="text" :required="true" v-model="noteTitle" />
            </div>
          </div>
        </div>
        <div class="col-12">
          <app-input
            type="markdown"
            id="editNote"
            :required="true"
            v-model="noteDetails"
          />
        </div>
      </form>
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
      <button type="button" class="btn btn-primary" @click.prevent="submitData">
        {{ $t("save") }}
      </button>
    </template>
  </app-modal>
</template>

<script>
import { FormMixin } from "../../../core/mixins/form/FormMixin";

export default {
  name: "NoteModal",
  props: ["note"],
  mixins: [FormMixin],
  data() {
    return {
      route,
      dataLoaded: false,
      noteDetails: this.$props.note.note,
      noteTitle: this.$props.note.title,
    };
  },

  methods: {
    submitData() {
      this.save({ note: this.noteDetails, title: this.noteTitle });
    },
    afterError(response) {
      this.$toastr.e(response.data.message);
    },

    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit("activity-list", "note");
    },
    afterFinalResponse() {
      this.closeModal();
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
    editNoteData() {
      this.noteDetails = this.note.note;
      this.dataLoaded = false;
    },
  },
  mounted() {
    this.editNoteData();
  },
};
</script>
