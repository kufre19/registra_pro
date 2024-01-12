<template>
    <div>
        <app-table
            :id="taxTableId"
            :options="taxOptions"
            @action="getAction"
        />

        <app-tax-modal
            v-if="isModalActive"
            :tableId="taxTableId"
            :selected-url="taxSelectedUrl"
            @close-modal="closeModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="tax-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "Tax",
    mixins: [FormMixin],
    props: ['id'],
    data() {
        return {
            urlGenerator,
            isModalActive: false,
            confirmationModalActive: false,
            taxTableId: 'tax-table',
            taxSelectedUrl: '',
                taxOptions: {
                    tableShadow: false,
                    tablePaddingClass: 'px-0 py-primary',
                    name: 'TaxTable',
                    url: route('tax.index'),
                    showHeader: true,
                    columns: [
                        {
                            title: this.$t('name'),
                            type: 'text',
                            key: 'name'
                        },
                        {
                            title: this.$t('value'),
                            type: "custom-html",
                            key: 'value',
                            modifier:(value)=> value +'%'
                        },
                        {
                            title: 'Action',
                            type: 'action',
                            key: 'invoice',
                        },
                    ],
                    filters: [],
                    showSearch: true,
                    showFilter: true,
                    showManageColumn: false,
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    showAction: true,
                    orderBy: 'desc',
                    actionType: "default",
                    actions: [
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'modal',
                            component: 'app-tax-modal',
                            modalId: 'tax-modal',
                            url: '',
                        }, {
                            title: this.$t('delete'),
                            icon: 'trash',
                            type: 'modal',
                            component: 'app-confirmation-modal',
                            modalId: 'tax-delete-modal',
                            url: '',
                        },
                    ],
                }
            }
        },

        methods: {
            getAction(row, action, active) {
                this.taxSelectedUrl = route('tax.show', {'id': row.id})
                if (action.title === this.$t('edit')) {
                    this.isModalActive = true;
                }else if (action.title === this.$t('delete')) {
                    this.confirmationModalActive = true;
                }
            },
            confirmed() {
                this.axiosDelete(this.taxSelectedUrl).then(response => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit('reload-' + this.taxTableId);
                }).catch(({error}) => {
                    this.$toastr.e(response.data.message);
                });
            },
            cancelled() {
                this.confirmationModalActive = false;
            },
            openModal() {
                this.isModalActive = true;
                $('#tax-modal').modal('show');
            },
            closeModal() {
                this.isModalActive = false;
                this.taxSelectedUrl = '';
                $("#tax-modal").modal('hide');
            },
        },

        mounted(){
            this.$hub.$on('headerButtonClicked-'+this.id, () => {
                this.isModalActive = true
                this.taxSelectedUrl = '';
            })
        }
    }
</script>

