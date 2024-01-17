<template>
    <div>
        <template v-if="rowData.notes[0]?.created_at">
            <div class="d-flex align-items-center">
                <span class="mb-1 badge badge-round badge-light">{{ formatDateTime(rowData.notes[0]?.created_at) }}</span>
            </div>
        </template>


    </div>
</template>

<script>
import { collection } from "../../../../Helpers/helpers";
import {
    time_format,
    formatted_date,
    onlyTime,
    formatted_time,
    formatDateTime,
} from "@app/Helpers/helpers";
export default {
    name: "CommentColumn",
    props: {
        rowData: {
            type: Object,
            required: true
        },
        value: {
            type: Array,
        }
    },
    data() {
        return {
            route,
            isViewModalOpen: false,
        }
    },
    methods: {
        viewAll() {
            this.isViewModalOpen = true;
        },
        closeModal() {
            this.isViewModalOpen = false;
            $("#person-org-modal").modal("hide");
        },
        formatDateTime,
    },
    computed: {
        lastCommentData() {
            // if (!this.value || this.value.length === 0) return [];

            // // Assuming the notes are already sorted by date, descending
            // return [this.value[0]]; // Take the first item only, which should be the latest note
        

        }
    }
}
</script>
