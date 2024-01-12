<template>
    <div>
        <template v-if="rowData.persons_count > 0">
            <small class="cursor-pointer" v-on:click="viewAll(rowData.id)">
                <b>+{{ rowData.persons_count }} more</b>
            </small>
        </template>

        <all-person-org
            v-if="isViewModalOpen"
            :row-data="personOrgData"
            :component-type="'person'"
            @close-modal="closeModal"
        />
    </div>
</template>

<script>
import {collection} from "../../../Helpers/helpers";
import {axiosGet} from "../../../Helpers/AxiosHelper";

export default {
    name: "CommonPersonOrgColumn",
    props: {
        rowData: {
            required: true
        },
    },
    data() {
        return {
            route,
            isViewModalOpen: false,
            personOrgData: []
        }
    },
    methods: {
        viewAll(id) {
            this.isViewModalOpen = true;
            axiosGet(route(`organizations.get-person`, id)).then((response) => {
                this.personOrgData = response.data.persons
            })
        },
        closeModal() {
            this.isViewModalOpen = false;
            this.personOrgData = []
            $("#person-org-modal").modal("hide");
        },
        adminAccess(item) {
            return (window.user.roles[0].is_admin || window.user.roles[0].name == 'Manager') || window.user.id == item.owner_id;
        },
        stopParentEvent(e) {
            e.stopPropagation();
        }
    }
}
</script>
