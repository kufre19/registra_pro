<template>
    <app-modal modal-id="test-mail-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('send_test_email') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span><app-icon :name="'x'"></app-icon></span>
            </button>
        </template>

        <template slot="body">
            <app-overlay-loader v-if="loading"/>
            <form
                v-else
                ref="form"
                :data-url="route('test-mail.send')"
            >
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <h6>{{ $t('email_address') }}</h6>
                            <app-input
                                type="email"
                                :placeholder="$t('enter_email_address')"
                                v-model="formData.email"
                                :required="true"
                                :error-message="$errorMessage(errors, 'email')"
                            />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <h6>{{ $t('subject') }}</h6>
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
                            <h6>{{ $t('message') }}</h6>
                            <app-input
                                type="textarea"
                                :placeholder="$t('enter_message')"
                                v-model="formData.message"
                                :required="true"
                                :error-message="$errorMessage(errors, 'message')"
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

import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "TestMailModal",
    mixins: [FormSubmitMixin],
    data() {
        return {
            route,
            formData: {},
        }
    },
    methods: {
        closeModal() {
            $('#test-mail-modal').modal('hide');
            this.$emit('input', false);
        },
        submitData() {
            this.save(this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#test-mail-modal').modal('hide');
            this.$emit('input', false);
            this.$toastr.s(data.message);
        },
    },
}
</script>

