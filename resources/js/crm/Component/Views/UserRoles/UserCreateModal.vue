<template>
    <app-modal modal-id="createUserModal" :modal-scroll="false" modal-size="large" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('add_user') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <template v-if="checkEmailDelivery != 1">
                <app-note :title="$t('no_email_settings_found')" :notes="note" content-type="html" padding-class="p-3"/>
            </template>
            <template v-else>
                <form ref="form"
                      @submit.prevent="submitData"
                      :data-url="selectedUrl ? selectedUrl : route('user.create')"
                >
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>{{ $t('first_name') }}</label>
                                <app-input
                                    :placeholder="$t('first_name')"
                                    v-model="formData.first_name"
                                    :error-message="$errorMessage(errors, 'first_name')"
                                />
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>{{ $t('last_name') }}</label>
                                <app-input
                                    :placeholder="$t('last_name')"
                                    v-model="formData.last_name"
                                    :error-message="$errorMessage(errors, 'last_name')"
                                />
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>{{ $t('email') }}</label>
                                <app-input
                                    :placeholder="$t('email')"
                                    v-model="formData.email"
                                    :error-message="$errorMessage(errors, 'email')"
                                />
                            </div>
                        </div>

<!--                        <div class="col-sm-12 col-md-12">-->
<!--                            <div class="form-group">-->
<!--                                <label>{{ $t('gender') }}</label>-->
<!--                                <app-input-->
<!--                                    :label="$t('gender')"-->
<!--                                    type="radio"-->
<!--                                    :list="genders"-->
<!--                                    v-model="formData.gender"-->
<!--                                    :error-message="$errorMessage(errors, 'gender')"-->
<!--                                />-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>{{ $t('roles') }}</label>
                                <app-input type="search-select"
                                           list-value-field="name"
                                           :list="roleList"
                                           :required="true"
                                           :placeholder="$t('select_one')"
                                           v-model="formData.role"/>
                                <small class="text-danger" v-if="errors.roles">{{ errors.roles[0] }}</small>
                            </div>
                        </div>
                    </div>

                </form>
            </template>
        </template>

        <template slot="footer">
            <template v-if="checkEmailDelivery != 1">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                    {{ $t('close') }}
                </button>
            </template>
            <template v-else>
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                    {{ $t('close') }}
                </button>
                <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                    <template v-if="!loading">{{ $t('save') }}</template>
                </button>
            </template>
        </template>
    </app-modal>

</template>

<script>

import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import {urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "UserCreateModal",
    mixins: [FormMixin],
    props: {
        tableId: String,
        checkEmailDelivery: {}
    },
    data() {
        return {
            route,
            note: `${this.$t('to_invite_warning')}
					<a href="${urlGenerator('settings/page?tab=Email%20setup')}">${this.$t('here')}</a> ${this.$t('to_add_email_setting')}`,

            invite: {
                roles: []
            },
            formData: {},
            loading: false,
            errors: {},
            genders : [
                {id:'male',value: this.$t('male')},
                {id:'female', value:  this.$t('female')},
                {id:'other', value:  this.$t('other')}
            ],
        }
    },
    computed: {
        roleList() {
            return this.$store.getters.getRole.filter(item => item.name !== 'Client');
        },
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },

        submitData() {
            // this.invite.roles.push(this.invite.role);
            this.save(this.formData);
        },
        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors ?? this.closeModal();
        },

        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.$hub.$emit('reload-role-modal');
            this.closeModal();
        },

        afterFinalResponse() {
            this.loading = false;
        },

        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    },

    created() {
        this.$store.dispatch('getRole');
    }
}
</script>

