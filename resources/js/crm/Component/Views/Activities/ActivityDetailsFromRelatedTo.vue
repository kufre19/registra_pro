<template>
    <div>
        <span :class="`badge badge-sm badge-pill badge-${badgeColor}`">
            {{arr[arr.length - 1].toLowerCase()}}
        </span>
        <a class="mb-1 d-flex" :href="urlGenerator(url)" @click="redirectToRoute" target="_new">
            <span class="link-list">{{ text }}</span>
            <span class="text-muted mt-1">
                <i data-feather="external-link"></i>
            </span>
        </a>
    </div>
</template>

<script>
import {urlGenerator} from "../../../Helpers/helpers";
export default {
    name: "ActivityDetailsFromRelatedTo",
    props: {
        rowData: {},
    },
    data() {
        return {
            urlGenerator,
            route,
        }
    },
    methods:{
        redirectToRoute(){
            window.open(this.url);
        }
    },
    computed:{
        arr(){
            return this.rowData.contextable_type.split("\\")
        },
        personOrOrg(){
            return `${this.arr[this.arr.length - 1].toLowerCase()}s`
        },
        url(){
            return this.rowData.contextable.title ? route('deal_details.page', {id: this.rowData.contextable.id}) : route(`${this.personOrOrg}.edit`, {id: this.rowData.contextable.id})
        },
        text(){
            return this.rowData.contextable.title ? this.rowData.contextable.title : this.rowData.contextable.name
        },
        badgeColor(){
            return this.rowData.contextable_type == 'App\\Models\\CRM\\Person\\Person' ? 'purple' : this.rowData.contextable_type == 'App\\Models\\CRM\\Deal\\Deal' ? 'success' : 'warning'
        }
    },
    mounted() {
      // console.log('rowData', this.rowData);
    }
}
</script>

