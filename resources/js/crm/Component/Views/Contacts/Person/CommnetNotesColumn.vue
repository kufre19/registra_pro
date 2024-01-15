<template>
    <div>
        <template v-for="(item, index) in lastCommentData" >
            <div class="d-flex align-items-center">
                <span class="mb-1 badge badge-round badge-light">{{ item.date }}</span>
               
                <br>
            </div>

          
        </template>


    </div>
</template>

<script>
import { collection } from "../../../../Helpers/helpers";
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
    },
    computed: {
        lastCommentData() {
            // if (!this.value || this.value.length === 0) return [];

            // // Assuming the notes are already sorted by date, descending
            // return [this.value[0]]; // Take the first item only, which should be the latest note
            let sample_data = [
            {date: '2024-01-14'},
            {date: '2024-01-14'},
            {date: '2024-01-14'},
            {date: '2024-01-14'}, 
            {date: '2024-01-14'},
        ]; 
           return  collection(sample_data).get("date");
        }
    }
}
</script>
