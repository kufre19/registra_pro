<template>
    <div>
        <div @click="stopParentEvent($event)">
            <template v-for="file in value">
                    <a :href="urlGenerator(file.path)" target="_blank">
                        <app-icon name="external-link"/>
                    </a>
            </template>
            <div v-if="value.length === 0">
                {{ '-' }}
            </div>
        </div>
    </div>
</template>

<script>

import {urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "AttachmentsColumn",
    props: {
        value: {},
        rowData: {}
    },
    data(){
        return{
            urlGenerator,
        }
    },
    methods: {
        stopParentEvent(e) {
            e.stopPropagation();
        }
    },
    computed: {
        file(){
            return this.value;
        }
    }
}
</script>

<style scoped>

</style>
