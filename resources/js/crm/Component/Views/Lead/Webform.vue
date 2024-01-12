<template>
    <section class="lead-web-form vh-100">
        <form
            class="webform mt-3 px-5"
            ref="form"
            :data-url="formSubmitEndpoint"
            @submit.prevent="handleSubmit"
        >
            <h3 class="form-title my-4 text-center">{{$t('person_web_form')}}</h3>
            <div class="name-details mb-5">
                <!-- NAME -->
                <div class="row d-flex align-items-center mb-4">
                    <label class="col-12 col-sm-4" for="name">{{
                        $t('name')
                    }}</label>
                    <app-input
                        :error-message="$errorMessage(errors, 'name')"
                        class="col-12 col-sm-8"
                        id="name"
                        name="name"
                        :required="true"
                        :placeholder="$t('enter_name')"
                        type="text"
                        v-model="formData.name"
                    />
                </div>
            </div>

            <div class="contact-details">
                <h5 class="group-title">{{ $t('contact_info') }}</h5>

                <!-- PHONE -->
                <div class="row d-flex align-items-center my-4">
                    <label class="col-12 col-sm-4" for="phone">{{
                        $t('phone')
                    }}</label>
                    <app-input
                        class="col-12 col-sm-5"
                        id="phone"
                        name="phone"
                        type="tel-input"
                        :placeholder="$t('enter_number')"
                        v-model="formData.phone[0].value"
                    />
                    <app-input
                        class="pt-3 pt-sm-0 col-6 col-sm-3"
                        v-model="formData.phone[0].type_id"
                        type="search-and-select"
                        :options="paginateInputsOptions.phoneEmailTypeOptions"
                        :placeholder="$t('type')"
                    />
                </div>
                <!-- EMAIL -->
                <div class="row d-flex align-items-center my-4">
                    <label class="col-12 col-sm-4" for="email">{{
                        $t('email')
                    }}</label>
                    <app-input
                        class="col-12 col-sm-5"
                        id="email"
                        name="email"
                        type="email"
                        :placeholder="$t('enter_email')"
                        v-model="formData.email[0].value"
                    />
                    <app-input
                        class="pt-3 pt-sm-0 col-6 col-sm-3"
                        v-model="formData.email[0].type_id"
                        type="search-and-select"
                        :options="paginateInputsOptions.phoneEmailTypeOptions"
                        :placeholder="$t('type')"
                    />
                </div>
            </div>

            <h6 class="pb-3 pt-3">{{ $t("address_details") }}</h6>
            <div class="form-group">
                <div class="row">
                    <div class="mb-0 col-sm-4 d-flex align-items-center">
                        <label>{{ $t('country') }}</label>
                    </div>
                    <div class="col-sm-8">
                        <app-input
                            type="search-select"
                            list-value-field="name"
                            :list="countryList"
                            :placeholder="$t('choose_a_country')"
                            v-model="formData.country_id"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="mb-0 col-sm-4 d-flex align-items-center">
                        <label>{{ $t('area') }}</label>
                    </div>
                    <div class="col-sm-8">
                        <app-input
                            type="text"
                            :placeholder="$t('enter_area')"
                            v-model="formData.area"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="mb-0 col-sm-4 d-flex align-items-center">
                        <label>{{ $t('city') }}</label>
                    </div>
                    <div class="col-sm-8">
                        <app-input
                            type="text"
                            :placeholder="$t('enter_city')"
                            v-model="formData.city"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="mb-0 col-sm-4 d-flex align-items-center">
                        <label>{{ $t('state') }}</label>
                    </div>
                    <div class="col-sm-8">
                        <app-input
                            type="text"
                            :placeholder="$t('enter_state')"
                            v-model="formData.state"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="mb-0 col-sm-4 d-flex align-items-center">
                        <label>{{ $t('zip_code') }}</label>
                    </div>
                    <div class="col-sm-8">
                        <app-input
                            type="text"
                            :placeholder="$t('enter_zip_code')"
                            v-model="formData.zip_code"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-0 col-sm-4 d-flex align-items-center">
                    <label>{{ $t("address") }}</label>
                </div>
                <div class="col-sm-8">
                    <app-input
                        type="textarea"
                        :placeholder="$t('add_address_details_here')"
                        v-model="formData.address"
                    />
                </div>
            </div>

            <div class="contact-details" v-if="customFields.length && customFieldDataLoaded">
                <h5 class="group-title">{{ $t('other_info') }}</h5>
                <div class="row d-flex align-items-center my-4" v-for="(field, fieldIndex) in customFields" :key="'c_field_' + field.name + '_' + fieldIndex">
                    <label class="col-12 col-sm-4" :for="field.name + '_' + fieldIndex">{{ field.name }}</label>
                    <template v-if="field.custom_field_type.name === 'text'">
                        <app-input
                            type="text"
                            class="col-12 col-sm-8"
                            :id="field.name + '_' + fieldIndex"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'textarea'">
                        <app-input
                            type="textarea"
                            class="col-12 col-sm-8"
                            :id="field.name + '_' + fieldIndex"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'radio'">
                        <app-input
                            type="radio"
                            class="col-12 col-sm-8"
                            :radio-checkbox-name="field.name"
                            :list="generateInputList(field)"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'checkbox'">
                        <app-input
                            type="checkbox"
                            class="col-12 col-sm-8"
                            :radio-checkbox-name="field.name"
                            :list="generateInputList(field)"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'select'">
                        <app-input
                            type="select"
                            class="col-12 col-sm-8"
                            :list="generateInputList(field)"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'number'">
                        <app-input
                            type="number"
                            class="col-12 col-sm-8"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                    <template v-if="field.custom_field_type.name === 'date'">
                        <app-input
                            type="date"
                            class="col-12 col-sm-8"
                            v-model="customFieldValue[field.name]"
                        />
                    </template>
                </div>
            </div>
            <div class="row d-flex align-items-center my-4">
                <label class="col-12 col-sm-4"></label>

                <button
                    type="submit"
                    class="btn btn-primary ml-3"
                    @click.prevent="submit"
                >
                    {{ $t('save') }}
                </button>

                <button
                    type="submit"
                    class="btn btn-secondary ml-3"
                    @click.prevent="clearForm"
                >
                    {{ $t('clear') }}
                </button>
            </div>
        </form>
    </section>
</template>

<script>
import { FormSubmitMixin } from "../../../Mixins/Global/FormSubmitMixin";

export default {
    name: 'lead-web-form',
    mixins: [FormSubmitMixin],
    data() {
        return {
            errors: {},
            formSubmitEndpoint: route('lead-web-form.store'),
            formHasErrors: false,
            countryList: [],
            paginateInputsOptions: {
                phoneEmailTypeOptions: {
                    url: route('phone_email_types.web-form'),
                    query_name: 'name',
                    per_page: 5,
                    modifire: (row) => ({ id: row.id, value: this.$t(row.name)}),
                    loader: 'app-pre-loader',
                },
            },
            formData: {
                name: '',
                phone: [{ value: '', type_id: '' }],
                email: [{ value: '', type_id: '' }],
            },

            customFields: {},
            customFieldValue: {},
            customFieldDataLoaded: false,
        };
    },
    created() {
        this.getCountries();
        this.getCustomFields();
    },
    methods: {
        getCustomFields() {
            this.axiosGet(route('lead.web-form-custom-fields')).then(({data}) => {
                this.customFields = data;
                this.customFields.forEach((el, i) => {
                    if (this.formData.custom_fields) {
                        //edit
                        let targetField = this.formData.custom_fields.find(
                            (e) => e.custom_field_id == el.id
                        );
                        if (el.custom_field_type.name == "checkbox") {
                            //checkbox

                            let options = [];
                            if (targetField) {
                                el.meta.split(",").map((e, i) => {
                                    if (targetField.value.split(",").find((el) => el == e))
                                        options.push(i);
                                });
                            }
                            this.customFieldValue[el.name] = options;
                            // console.log(options, "if if edit checkbox");
                        } else if (
                            el.custom_field_type.name == "select" ||
                            el.custom_field_type.name == "radio"
                        ) {
                            let v = undefined;
                            // select & radio
                            if (targetField) {
                                el.meta.split(",").map((e, i) => {
                                    if (targetField.value.split(",").find((el) => el == e)) v = i;
                                });
                            }
                            this.customFieldValue[el.name] = v;
                        } else if (el.custom_field_type.name == "date") {
                            //other

                            this.customFieldValue[el.name] = targetField
                                ? new Date(targetField.value)
                                : "";
                            // console.log("if else edit other");
                        } else {
                            //other

                            this.customFieldValue[el.name] = targetField
                                ? targetField.value
                                : "";
                            // console.log("if else edit other");
                        }
                    } else {
                        //add
                        if (
                            el.custom_field_type.name == "select" ||
                            el.custom_field_type.name == "radio"
                        ) {
                            // select & radio
                            this.customFieldValue[el.name] = undefined;
                        } else {
                            this.customFieldValue[el.name] =
                                el.custom_field_type.name == "checkbox" ? [] : "";
                        }
                        // console.log("else add");
                    }
                });
                setTimeout(() => {
                    this.dataLoaded = true;
                    this.customFieldDataLoaded = true;
                }, 300)
            });
        },
        generateInputList({meta}) {
            if (meta) {
                return meta.split(",").map((m, i) => {
                    return {id: i, value: m};
                });
            }
        },
        submit() {
            let customData = []
            this.customFields.map((el) => {
                let item = {
                    value:
                        el.custom_field_type.name == "checkbox"
                            ? el.meta.split(",").filter((e, i) => {
                                if (
                                    this.customFieldValue[el.name].includes(String(i)) ||
                                    this.customFieldValue[el.name].includes(i)
                                ) {
                                    return e;
                                }
                            })
                            : (el.custom_field_type.name == "select" ||
                                el.custom_field_type.name == "radio")
                                ? el.meta.split(",").find((e, i) => {
                                    return i == Number(this.customFieldValue[el.name]);
                                })
                                : this.customFieldValue[el.name],
                    custom_field_id: el.id,
                };
                customData.push(item);
            });

            this.formData.customs = customData;

            this.save(this.formData);
        },
        afterError(err) {
            this.errors = err.response.data.errors;
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.clearForm();
        },
        clearForm() {
            this.formData = {
                name: '',
                phone: [{ value: '', type_id: '' }],
                email: [{ value: '', type_id: '' }],
            };
            this.getCustomFields("person");
        },
        getCountries() {
            this.axiosGet(route('countries.web-form')).then(({data}) => {
                this.countryList = data;
            });
        },
    },
};
</script>

<style>
@media only screen and (min-width: 1024px) {
    .lead-web-form {
        width: 70%;
        margin: 0 auto;
    }
}
</style>
