<template>
    <app-modal modal-id="mailing-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('send_email') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span><app-icon :name="'x'"></app-icon></span>
            </button>
        </template>

        <template slot="body">
            <app-overlay-loader v-if="loading"/>
            <form
                v-else
                ref="form"
                :data-url="route('person.send_email', { id: 1 })"
            >
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <app-input
                                type="search-select"
                                :placeholder="$t('choose_an_email_address')"
                                list-value-field="value"
                                :list="emails"
                                v-model="formData.email"
                                :required="true"
                                :error-message="$errorMessage(errors, 'email')"
                            />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <app-input
                                type="text"
                                :placeholder="$t('enter_subject')"
                                v-model="formData.subject"
                                :required="true"
                                :error-message="$errorMessage(errors, 'subject')"
                            />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <app-input
                                type="text-editor"
                                :placeholder="$t('write_a_message_here')"
                                v-model="formData.mail"
                                :required="true"
                                :error-message="$errorMessage(errors, 'mail')"
                            />
                        </div>
                    </div>
                </div>
            </form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('close') }}
            </button>

            <button type="button" class="btn btn-primary" @click.prevent="submitData">
                <span class="w-100">
                    <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                </span>
                <template v-if="!loading">{{ $t("send") }}</template>
            </button>
        </template>
    </app-modal>
</template>

<script>

import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: 'MailingModal',
    mixins: [FormSubmitMixin],
    props: {
        userInfoData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            route,
            formData: {
                email: '',
                subject: '',
                mail: '',
            },
            emails: []
        }
    },
    watch: {
        userInfoData(data) {
            this.emails = _.map(data.email, (mail) => {
                return {id: mail.value, value: mail.value}
            });
        }
    },
    mounted() {
        this.emails = _.map(this.userInfoData.email, (mail) => {
            return {id: mail.value, value: mail.value}
        });
    },
    methods: {
        closeModal() {
            $('#mailing-modal').modal('hide');
            this.$emit('input', false);
        },
        submitData() {
            this.save(this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#mailing-modal').modal('hide');
            this.$emit('input', false);
            this.$toastr.s(data.message);
        },
    }
}
</script>
