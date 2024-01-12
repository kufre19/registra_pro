<template>
    <form ref="form">
        <div class="form-group">
            <label>{{ $t('contents') }}</label>
            <app-input
                id="database-template-title"
                name="custom_content"
                v-model="template.custom_content"
                :required="true"/>
        </div>
        <div class="form-group text-center">
            <button
                type="button"
                class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                data-toggle="tooltip"
                data-placement="top"
                v-for="tag in all_tags"
                @click="insertAtCaret('database-template-title', tag.tag)"
                :title="tag.description"
            >{{ tag.tag }}
            </button>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>


        </div>
    </form>
</template>

<script>

import {TemplateMixins} from "./Mixins/TemplateMixins";

export default {
    name: "DatabaseTemplate",
    mixins: [TemplateMixins],
    props: ['props'],
    data() {
        return {
            loaded: false,
            tags: {
                '{name}': this.$t('the_resource_name_of_the_event'),
                '{app_name}': this.$t('name_of_the_app'),
                '{pipeline_name}': this.$t('name_of_the_pipeline_name'),
                '{deal_name}': this.$t('name_of_the_deal_name'),
                '{action_by}': this.$t('the_user_who_performed_the_action'),
            },
        }
    },
    computed: {
        setTemplateObj() {
            let data = this.$store.getters.editNotificationEvent;
            this.template = data.templates.find(item => {
                item.custom_content = item.custom_content ? item.custom_content : item.default_content;
                return item.type === 'database'
            });
            return this.template;
        },
        all_tags() {
            let notificationEventName = this.$store.getters.editNotificationEvent;
            const tags = Object.keys(this.tags).filter(tag => {
                if ('user_joined' === notificationEventName.name) {
                    return ['{app_name}'].includes(tag)
                } else if ('pipeline_created' === notificationEventName.name ||
                    'pipeline_updated' === notificationEventName.name ||
                    'pipeline_deleted' === notificationEventName.name) {
                    return ['{pipeline_name}', '{action_by}'].includes(tag)
                } else if ('deal_created' === notificationEventName.name ||
                    'deal_updated' === notificationEventName.name ||
                    'deal_deleted' === notificationEventName.name) {
                    return ['{deal_name}', '{action_by}'].includes(tag)
                } else {
                    return ['{name}', '{action_by}'].includes(tag)
                }
            })
            return tags.map(tag => {
                return {tag, description: this.tags[tag]}
            })
        }
    },

}
</script>

<style scoped>
.margin-left-2 {
    margin-left: 4px;
}

.margin-left-2:first-child {
    margin-left: 0;
}
</style>
