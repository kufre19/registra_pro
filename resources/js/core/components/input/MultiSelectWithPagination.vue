<template>
    <div>
        <app-input
            type="multi-select"
            :list="list"
            @scroll-end="handleScrollEnd"
            :list-value-field="options.listValueField"
            v-model="selectedId"
            @input="handleInput"
            :is-animated-dropdown="true"
        />
    </div>
</template>

<script>
import {axiosGet} from "../../../crm/Helpers/AxiosHelper";

export default {
    props: {
        options: {
            type: Object,
            required: true,
        }
    },
    mounted() {
        this.fetchSelectList();
    },
    data() {
        return {
            list: [],
            page: 1, // starting with one
            selectedId: '',
        }
    },
    computed: {
        url() {
            return `${this.options.url}?page=${this.page}`;
        }
    },
    methods: {
        handleScrollEnd() {
            this.page++;
            this.fetchSelectList();
        },
        fetchSelectList() {
            axiosGet(this.url).then((data) => {
                this.page = +data.data.current_page;
                this.list = [...this.list, ...data.data.data];
            }).catch(e => console.log(e));
        },
        handleInput() {
            this.$emit('input', this.selectedId);
        }
    }
}
</script>

<style scoped>

</style>
