<template>
    <form ref="form">
        <div class="form-group">
            <label class="d-block">{{ $t('mail_subject') }}</label>
            <span class="text-muted font-size-90">
        {{ $t('this_field_will_be_used_as_the_email_subject_and_identify_the_template') }}</span>
            <app-input type="text" v-model="template.subject"
                       :required="true"/>
        </div>

        <div class="form-group">
            <label>{{ $t('contents') }}</label>
            <app-input
                type="text-editor"
                v-model="template.custom_content"
                :required="true"
                id="text-editor-for-template"
                :text-editor-hints="textEditorHints(Object.keys(tags))"
            />
        </div>
        <div class="form-group text-center">
            <button
                type="button"
                class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                data-toggle="tooltip"
                data-placement="top"
                v-for="tag in all_tags"
                :title="tag.description"
                @click="addTag(tag.tag)"
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
import {textEditorHints} from "@app/Helpers/helpers";

export default {
    name: "MailTemplate",
    mixins: [TemplateMixins],
    props: ['props'],
    data() {
        return {
            textEditorHints,
            tags: {
                '{name}': this.$t('the_resource_name_of_the_event'),
                '{app_name}': this.$t('name_of_the_app'),
                '{app_logo}': this.$t('logo_of_the_app'),
                '{receiver_name}': this.$t('the_user_who_will_receive_the_notification'),
                '{deal_name}': this.$t('name_of_the_deal_name'),
                '{pipeline_name}': this.$t('name_of_the_pipeline_name'),
                '{action_by}': this.$t('the_user_who_performed_the_action'),
                '{resource_url}': this.$t('page_link_of_resource'),
                '{invitation_url}': this.$t('invitation_url_for_the_user'),
                '{reset_password_url}': this.$t('reset_password_url_of_user'),
            },
        }
    },
    computed: {
        setTemplateObj() {
            let data = this.$store.getters.editNotificationEvent;
            this.template = data.templates.find(item => {
                item.custom_content = item.custom_content ? item.custom_content : item.default_content;
                return item.type === 'mail'
            });
            return this.template;
        },
        all_tags() {
            let notificationEventName = this.$store.getters.editNotificationEvent;
            const tags = Object.keys(this.tags).filter(tag => {
                if ('user_invitation' === notificationEventName.name) {

                    return ['{action_by}', '{app_name}', '{app_logo}', '{receiver_name}', '{invitation_url}'].includes(tag)

                } else if ('password_reset' === notificationEventName.name) {

                    return ['{app_name}', '{app_logo}', '{receiver_name}', '{reset_password_url}'].includes(tag)

                } else if ('user_joined' === notificationEventName.name
                    || 'roles_created' === notificationEventName.name
                    || 'roles_updated' === notificationEventName.name) {

                    return ['{app_name}', '{app_logo}', '{name}', '{receiver_name}', '{resource_url}'].includes(tag)

                } else if ('roles_deleted' === notificationEventName.name) {

                    return ['{app_logo}', '{app_name}', '{name}', '{action_by}', '{receiver_name}'].includes(tag)

                } else if ('pipeline_created' === notificationEventName.name || 'pipeline_updated' === notificationEventName.name) {

                    return ['{app_name}', '{app_logo}', '{pipeline_name}', '{receiver_name}', '{action_by}', '{resource_url}'].includes(tag)

                } else if ('pipeline_deleted' === notificationEventName.name) {

                    return ['{app_name}', '{app_logo}', '{pipeline_name}', '{receiver_name}', '{action_by}'].includes(tag)

                } else if ('deal_created' === notificationEventName.name) {

                    return ['{deal_name}', '{app_name}', '{app_logo}', '{pipeline_name}', '{receiver_name}', '{action_by}', '{resource_url}'].includes(tag)

                } else if ('deal_updated' === notificationEventName.name || 'deal_deleted' === notificationEventName.name) {

                    return ['{deal_name}', '{app_name}', '{app_logo}', '{receiver_name}', '{action_by}'].includes(tag)

                } else if ('deal_assigned' === notificationEventName.name) {

                    return ['{app_name}', '{deal_name}', '{app_logo}', '{receiver_name}', '{action_by}'].includes(tag)

                } else {

                    return !['{reset_password_url}', '{invitation_url}'].includes(tag)
                }
            })
            return tags.map(tag => {
                return {tag, description: this.tags[tag]}
            })
        }
    },
    methods: {
        addTag(tag_name = '{name}') {
            $("#text-editor-for-template").summernote('editor.saveRange');
            $("#text-editor-for-template").summernote('editor.restoreRange');
            $("#text-editor-for-template").summernote('editor.focus');
            $("#text-editor-for-template").summernote('editor.insertText', tag_name);
        }
    }

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

