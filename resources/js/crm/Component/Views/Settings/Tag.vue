<template>
    <div>
        <app-table
            :id="tagTableId"
            :options="tagOptions"
            @action="getAction"
        />

        <app-tag-modal
            v-if="isModalActive"
            :tableId="tagTableId"
            :selected-url="tagSelectedUrl"
            @close-modal="closeModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="tag-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "Tag",
    mixins: [FormMixin],
    props: ['id'],
    data() {
        return {
            urlGenerator,
            isModalActive: false,
            confirmationModalActive: false,
            tagTableId: 'tag-table',
            tagSelectedUrl: '',
            tagOptions: {
                    tableShadow: false,
                    tablePaddingClass: 'px-0 py-primary',
                    name: 'TagTable',
                    url: route('selectable.tags'),
                    showHeader: true,
                    columns: [
                        {
                            title: this.$t('name'),
                            type: 'text',
                            key: 'name'
                        },
                        {
                            title: this.$t('color_code'),
                            type: 'text',
                            key: 'color_code'
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
                            component: 'app-tag-modal',
                            modalId: 'tag-modal',
                            url: '',
                        }, {
                            title: this.$t('delete'),
                            icon: 'trash',
                            type: 'modal',
                            component: 'app-confirmation-modal',
                            modalId: 'tag-delete-modal',
                            url: '',
                        },
                    ],
                }
            }
        },

        methods: {
            getAction(row, action, active) {
                this.tagSelectedUrl = route('tags.show', {'id': row.id})
                if (action.title === this.$t('edit')) {
                    this.isModalActive = true;
                }else if (action.title === this.$t('delete')) {
                    this.confirmationModalActive = true;
                }
            },
            confirmed() {
                this.axiosDelete(this.tagSelectedUrl).then(response => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit('reload-' + this.tagTableId);
                }).catch(({response}) => {
                    console.log(response)
                    this.$toastr.e(response.data.message);
                });
            },
            cancelled() {
                this.confirmationModalActive = false;
            },
            openModal() {
                this.isModalActive = true;
                $('#tag-modal').modal('show');
            },
            closeModal() {
                this.isModalActive = false;
                this.tagSelectedUrl = '';
                $("#tag-modal").modal('hide');
            },
        },

        mounted(){
            this.$hub.$on('headerButtonClicked-'+this.id, () => {
                this.isModalActive = true
                this.tagSelectedUrl = '';
            })
        }
    }
</script>

