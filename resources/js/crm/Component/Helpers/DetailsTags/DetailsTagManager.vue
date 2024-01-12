<template>
    <div class="card border-0 card-with-shadow">
        <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">
            <h5 class="card-title text-muted m-0">{{ $t('tags') }}</h5>
        </div>
        <div class="card-body">
            <app-tag-manager
                :tags="tags"
                :list="allTags"
                list-value-field="name"
                color-value-field="color_code"
                :tag-preloader="tagPreloader"
                @storeTag="storeAndAttachTag"
                @attachTag="attachTag"
                @detachTag="detachTag"
            />
        </div>
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
export default {
    name: "DetailsTagManager",
    mixins: [FormMixin],
    props:['tagData', 'taggableId', 'postUrl'],
    data() {
        return {
            tags: [],
            tagOptions: [],
            tagPreloader: false,
        }
    },
    created() {
        this.$store.dispatch('getAllTags');
    },
    watch: {
        tagData: {
            handler: function (tags) {
                this.tags = tags
            },
            immediate: true
        },
    },
    computed: {
        allTags() {
            return this.$store.getters.getAllTags
        }
    },
    methods: {
        storeAndAttachTag({name, color_code}) {
            this.tagPreloader = true;
            this.axiosPost({
                url: route('tags.store'),
                data: {name, color_code}
            }).then(response => {
                this.$store.dispatch('getAllTags');
                this.attachTag(response.data);
            }).catch((error) => this.$toastr.e(error.response.data.errors.name[0]));
        },
        attachTag(tag) {
            this.tagPreloader = true;
            this.axiosPost({
                url: this.postUrl,
                data: {tag_id: tag.id}
            }).then(response => {
                this.$toastr.s(response.data.message);
                this.tags.push(tag);
                this.tagPreloader = false;
            });
        },
        detachTag(tag) {
            this.tagPreloader = true;
            this.axiosPut({
                url: this.postUrl,
                data: {tag_id: tag.id}
            }).then(response => {
                this.$toastr.s(response.data.message);
                let index = this.tags.indexOf(tag);
                this.tags.splice(index, 1);
                this.tagPreloader = false;
            })
        },
    },
}
</script>
