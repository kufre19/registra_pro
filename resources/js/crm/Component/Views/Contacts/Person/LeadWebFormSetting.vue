<template>
    <div>
        <app-note :notes="note" :show-title="false" :content-type="'html'"/>
        <div class="form-group mt-3">
            <div class="row">
                <div class="mb-0 col-sm-3 d-flex align-items-center">
                    <label>{{ $t("embed_code") }}</label>
                </div>
                <div class="col-sm-9">
                    <app-input
                        type="textarea"
                        :placeholder="$t('embed_code')"
                        v-model="embedCode"
                    />
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="mb-0 col-sm-3 d-flex align-items-center">
                    <label>{{ $t("direct_link") }}</label>
                </div>
                <div class="col-sm-9">
                    <app-input
                        type="text"
                        :placeholder="$t('direct_link')"
                        v-model="directLink"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {urlGenerator} from "../../../../Helpers/AxiosHelper";

export default {
    name: "LeadWebFormSetting",
    data() {
        return {
            directLink: urlGenerator('lead-web-form'),
            embedCode: `<iframe width="650" height="900" src="${urlGenerator('lead-web-form')}" frameborder="0" allowfullscreen></iframe>`,
            note: `${this.$t('lead_web_form_notification') + this.$t('or_you_can')} <a href="${urlGenerator('lead-web-form')}" target="_blank" class="btn-link">${this.$t('click_here')}</a>`,
        }
    },
    methods: {},

};
</script>
