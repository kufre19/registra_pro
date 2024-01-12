<template>
    <div class="border-bottom mb-4 pb-4">
        <p class="mb-2 font-weight-bold">{{ $t('tags') }}</p>
        <div>
            <app-tag-manager
                :tags="tagData"
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
    name: "TagManager",
    mixins: [FormMixin],
    props:['tagData', 'taggableId', 'postUrl'],
    data() {
        return {
            tagPreloader: false,
        }
    },
    created() {
        this.tagPreloader = true;
        this.$store.dispatch('getAllTags');
    },
    watch: {
        allTags: {
            handler: function () {
                this.tagPreloader= false;
            }
        }
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
                this.$emit("update-request");
                this.$toastr.s(response.data.message);
                this.tagPreloader = false;
            });
        },
        detachTag(tag) {
            this.tagPreloader = true;
            this.axiosPut({
                url: this.postUrl,
                data: {tag_id:tag.id}
            }).then(response => {
                this.$emit("update-request");
                this.$toastr.s(response.data.message);
                this.tagPreloader = false;
            })
        },
    },
}
</script>
