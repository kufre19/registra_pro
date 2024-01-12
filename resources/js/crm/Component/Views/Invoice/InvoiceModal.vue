<template>
    <app-modal
        modal-id="invoice-modal"
        modal-size="extra-large"
        modal-alignment="top"
        @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">
                {{ selectedUrl ? $t("edit_invoice") : $t("add_invoice") }}
            </h5>
            <button
                type="button"
                class="close outline-none"
                data-dismiss="modal"
                aria-label="Close">
                <span>
                  <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form ref="form" v-if="dataLoaded" :data-url="selectedUrl ? selectedUrl : route('invoice.store')">
                <div class="row">
                    <div class="col-md-6">
                        <span>
                            <!--  If loggedIn user if agent -->
                            <div v-if="isAgentUser" class="form-group">
                                <label>{{ $t('owner') }}</label>
                                <app-input
                                    :required="true"
                                    :disabled="true"
                                    v-model="full_name"
                                    type="text"
                                />
                                <app-input
                                    id="agent_owner"
                                    :required="true"
                                    v-model="formData.owner_id"
                                    :error-message="$errorMessage(errors, 'owner_id')"
                                    type="hidden"
                                />
                                <span class="text-danger" v-if="errors.owner_id">{{errors.owner_id[0] }}</span>
                            </div>

                            <!--  If loggedIn user is app admin-->
                            <div v-else class="form-group">
                                <label for="owner">{{ $t('owner') }}</label>
                                <app-input
                                    id="owner"
                                    :error-message="$errorMessage(errors, 'owner_id')"
                                    :list="ownerList"
                                    :required="true"
                                    v-model="formData.owner_id"
                                    type="search-select"
                                    :placeholder="$t('choose_a_owner')"
                                    list-value-field="full_name"
                                    @input="getDealByOwner(formData.owner_id)"
                                />
                                <span class="text-danger" v-if="errors.owner_id">{{errors.owner_id[0] }}</span>
                            </div>
                        </span>


                        <div class="form-group">
                            <label for="due_date">{{ $t('due_date') }}</label>
                            <app-input
                                id="due_date"
                                :required="true"
                                v-model="formData.due_date"
                                :error-message="$errorMessage(errors, 'due_date')"
                                type="date"
                                :min-date="formData.issue_date"
                            />
                            <span class="text-danger" v-if="errors.due_date">{{errors.due_date[0] }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="issue_date">{{ $t('issue_date') }}</label>
                            <app-input
                                :error-message="$errorMessage(errors, 'issue_date')"
                                id="issue_date"
                                :required="true"
                                v-model="formData.issue_date"
                                type="date"
                            />
                            <span class="text-danger" v-if="errors.issue_date">{{errors.issue_date[0] }}</span>
                        </div>

                        <div class="form-group">
                            <label for="status">{{ $t('status') }}</label>
                            <app-input
                                id="status"
                                :required="true"
                                v-model="formData.status_id"
                                :error-message="$errorMessage(errors, 'status_id')"
                                :list="invoiceStatus"
                                :placeholder="$t('choose_a_status')"
                                list-value-field="translated_name"
                                type="search-select"
                            />
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-white bg-dark px-3 py-2 rounded-top">
                        <div class="row">
                            <div class="col-md-4">{{ $t('deal') }}</div>
                            <div class="col-md-2 text-right">{{ $t('quantity') }}</div>
                            <div class="col-md-3 text-right">{{ $t('price') }}</div>
                            <div class="col-md-3 text-right">{{ $t('amount') }}</div>
                        </div>
                    </div>

                    <div v-for="(product, index) in invoice_details" class="default-base-color p-3" :key="`product-item-${index}`">
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <app-input
                                    id="deal"
                                    :error-message="$errorMessage(errors, 'deal_id')"
                                    :list="dealListByOwner"
                                    :required="true"
                                    v-model="formData.deal_id"
                                    type="search-select"
                                    :placeholder="$t('choose_a_deal')"
                                    list-value-field="title"
                                    @input="getDealDetailsById(formData.deal_id)"
                                />
                                <span class="text-danger" v-if="errors.deal_id">{{errors.deal_id[0] }}</span>
                            </div>

                            <div class="col-md-2 mb-2 mb-md-0">
                                <app-input
                                    v-model="formData.quantity"
                                    :placeholder="$t('add_quantity')"
                                    type="number"
                                    :input-class="'text-right'"
                                    :value="formData.quantity"
                                />
                                <span class="text-danger" v-if="errors.quantity">{{errors.quantity[0] }}</span>
                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">
                                <app-input
                                    v-model="formData.price"
                                    :placeholder="$t('price')"
                                    :input-class="'text-right'"
                                    type="number"
                                />
                                <span class="text-danger" v-if="errors.price">{{errors.price[0] }}</span>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <app-input
                                    :placeholder="$t('amount')"
                                    type="text"
                                    :input-class="'text-right'"
                                    :disabled="true"
                                    v-model="totalAmount"
                                    :value="totalAmount"
                                    @disables="true"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end mb-primary">
                        <div class="col-lg-6 col-xl-6">
                            <div class="text-size-16 border-bottom mb-2 mr-3">
                                <div class="row align-items-center justify-content-between py-2">
                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <p class="text-muted mb-0">{{ $t('tax') }}</p>
                                    </div>
                                    <div class="col-7">
                                        <app-input
                                            :placeholder="$t('choose_a_tax')"
                                            v-model="formData.tax_id"
                                            :list="taxes"
                                            list-value-field="name"
                                            type="select"
                                            @input="applyTax"
                                        />
                                        <span class="text-danger" v-if="errors.tax_id">{{errors.tax_id[0] }}</span>
                                    </div>
                                    <div class="col-5">
                                        <p class="mb-0 text-right">{{ numberWithCurrencySymbol(calculateTotalTax) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-size-16 border-bottom mb-2 mr-3">
                                <div class="d-flex align-items-center justify-content-between py-2">
                                    <p class="text-muted mb-0">{{ $t('sub_total') }}</p>
                                    <p class="mb-0">{{ numberWithCurrencySymbol(subtotal) }}</p>
                                </div>
                                <div class="row align-items-center justify-content-between py-2">
                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <p class="text-muted mb-0">{{ $t('discount') }}</p>
                                    </div>
                                    <div class="col-7">
                                        <app-input
                                            id="discount_type"
                                            v-model="formData.discount_type"
                                            :list="discountTypeList"
                                            list-value-field="name"
                                            type="select"
                                            @input="changeDiscountAmount()"
                                        />
                                    </div>
                                    <div class="col-5">
                                        <app-input
                                            v-model="formData.discount"
                                            :input-class="'text-right'"
                                            :disabled="formData.discount_type === 'none'"
                                            type="number"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mr-3">
                                <h4 class="text-muted mb-0">{{ $t('total') }}</h4>
                                <h4 class="mb-0">{{ numberWithCurrencySymbol(grandTotal) }}</h4>
                            </div>
                        </div>
                    </div>

                    <template v-if="addNote">
                        <div class="form-group mt-primary">
                            <label for="note">{{ $t('note') }}</label>
                            <app-input v-if="editorShow"
                                       id="note"
                                       v-model="formData.note"
                                       :minimal="true"
                                       type="textarea"
                                       :text-area-rows="12"
                            />
                        </div>
                    </template>
                    <button v-if="!addNote"
                            :key="`plus`"
                            class="btn btn-primary"
                            type="button"
                            @click.prevent="showNote()">
                        <app-icon name="plus"/>
                        {{ $t('add_note') }}
                    </button>
                    <button v-else
                            :key="`minus`"
                            class="btn btn-warning"
                            type="button"
                            @click.prevent="hideNote()">
                        <app-icon name="minus"/>
                        {{ $t('remove_note') }}
                    </button>
                </div>
            </form>
            <app-overlay-loader v-else/>
        </template>
        <template slot="footer">
            <button
                type="button"
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                @click.prevent="closeModal"
            >
                {{ $t("cancel") }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submit">
                <span class="w-100">
                  <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>
        </template>
    </app-modal>
</template>
<script>

import moment from 'moment';
import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";
import {getAllCustomFields} from "@app/Mixins/Global/CustomFieldMixin";
import {mapGetters} from "vuex";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import {axiosGet} from "../../../Helpers/AxiosHelper";
import { numberWithCurrencySymbol } from "../../../Helpers/helpers";

export default {
    name: "InvoiceModal",
    mixins: [FormSubmitMixin, getAllCustomFields],
    props: {},
    data() {
        return {
            isAgentUser: false,
            agentUser: {},
            full_name: '',
            taxes: [],
            errors: [],
            dealDetails: {},
            dealListByOwner: [],
            editorShow: true,
            addNote: false,
            numberWithCurrencySymbol,
            clientList: [],
            route,
            addAddressDetails: false,
            dataLoaded: false,
            formData: {
                owner_id: null,
                deal_id: null,
                client_id: '',
                invoice_number: null,
                due_date: DateFunction.getDateFormat(new Date(), 'YYYY-MM-DD'),
                issue_date: DateFunction.getDateFormat(new Date(), 'YYYY-MM-DD'),
                status_id: '',
                price: '',
                quantity: 1,
                discount_type: 'none',
                discount: '',
                note: null,
                tax_id: null,
            },
            totalTax:0,
            selectedTaxValue: 0,
            totalSummation: {
                totalAmount: 0,
            },
            deal_details: {
                value: ''
            },
            invoice_details:  [
                {product_id: '', quantity: null, price: '', amount: ''}
            ],
            discountTypeList: [
                {id: 'none', name: this.$t('discount_type')},
                {id: 'fixed', name: this.$t('fixed')},
                {id: 'percentage', name: this.$t('percentage')}
            ],
            addEditData: {},
            customFieldValue: [],
        };
    },
    computed: {
        ...mapGetters({
            ownerList: "getOwner",
            organizationList: "getOrganization",
            phoneEmailTypeList: "phoneEmailType",
            contactTypeList: "contentType",
            countryList: "getCountry",
            invoiceStatus: "getInvoiceStatus",
            getDeals: "getDeal"
        }),
        grandTotal() {
            if (this.formData.discount_type === 'none') {
                return this.subtotal;
            } else if (this.formData.discount_type === 'fixed') {
                return this.formData.discount ?
                    ((this.subtotal) - Number(this.formData.discount)) : this.subtotal;
            } else if (this.formData.discount_type === 'percentage') {
                let par = ((this.subtotal) * this.formData.discount / 100);
                let result = this.subtotal - par;
                return this.formData.discount ? result : this.subtotal;
            }
        },
        subtotal(){
            return Number(this.totalAmount) + Number(this.calculateTotalTax);
        },
        totalAmount(){
            return Number(this.formData.price * this.formData.quantity);
        },
        calculateTotalTax(){
            return Number((this.totalAmount * this.selectedTaxValue) / 100);
        },
    },

    methods: {
        applyTax(value){
            let item = this.taxes.find(i => i.id === Number(value))
            this.selectedTaxValue = item.value || 0
        },
        getAllTaxes(){
            axiosGet(route('selectable.all_taxes'))
            .then((response) => {
                this.taxes = response.data;
            }).catch((error) => console.log(error));
        },
        changeDiscountAmount(){
          this.formData.discount = 0
        },
        hideNote(){
            this.addNote = false;
        },
        showNote(){
            this.addNote = true;
        },
        resetFormData(){
            this.formData.price = ''
            this.formData.quantity = 1
            this.formData.deal_id = ''
            this.formData.discount_type = 'none'
            this.formData.discount = ''
        },
        async getLoggedInAgentUserData() {
            await axiosGet(route('crm.logged_agent_user'))
                .then(({data}) => {
                    if (data){
                        this.isAgentUser = true;
                        this.formData.owner_id = data.id;
                        this.full_name = data.full_name;
                        this.agentUser = data;

                        this.getDealByOwner(data.id)
                    }

                })
                .catch((error) => console.log(error))
        },
        async getDealByOwner(owner_id) {
            //this.resetFormData(); //To reset last data

            this.dealListByOwner =  [];
            await axiosGet(route('deals.owner',{owner_id}))
                .then(({data}) => {
                    this.dealListByOwner =  data
                })
                .catch((error) => console.log(error))
        },
        async getDealDetailsById(id) {
            this.dealDetails = {}
            await axiosGet(route('deal.details',{id}))
                .then(({data}) => {
                    this.dealDetails =  data;
                    this.formData.price = data.value;
                }).catch((error) => console.log(error));
        },
        submit() {
            let formData = this.formData;
            formData.issue_date = moment(this.formData.issue_date).format('YYYY-MM-DD');
            formData.due_date = moment(this.formData.due_date).format('YYYY-MM-DD');
            formData.discount = this.formData.discount ? this.formData.discount : 0;
            formData.note = this.formData.note;
            formData.total = this.grandTotal;
            formData.discount_amount = this.formData.discount ? this.formData.discount : 0;
            this.save(this.formData);
        },
        afterSuccessFromGetEditData(response) {
            this.dealListByOwner =  this.getDeals;
            this.formData = response.data;

            //for tax update..
            this.selectedTaxValue = response.data?.tax?.value ? response.data.tax.value : 0;

            this.formData = {
                owner_id: response.data.deal.owner.id,
                deal_id: response.data.deal.id,
                note: response.data.note,
                discount: response.data.discount_amount,
            };
            this.formData = {
                ...this.formData,
                ...response.data,
            };
            if (response.data.note){
                this.editorShow = true;
                this.showNote();
            }

            this.dealListByOwner =  this.getDeals;
            this.getAllCustomFields("person");
        },
        openModal() {
            this.$emit("openOrgModal");
        },
    },
    mounted() {
        this.getAllTaxes();
        this.getLoggedInAgentUserData();
        this.$store.dispatch('getDeal');

        this.$store.dispatch('getInvoiceStatus');
        if (!this.selectedUrl) {
            this.getAllCustomFields("person");
        }
        $("#organization-modal").on("hidden.bs.modal", () => {
            this.isOrganizationModalActive = false;
        });
    },
};
</script>
