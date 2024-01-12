export default {
    data() {
        return {
            isBulkActionActive: false,
            isAllRowSelected: false,
            selectedRows: [],
            bulkDeleteModal: false,
            addLeadModalActive: false,
        }
    },
    computed: {
        selectedIds() {
            return this.selectedRows.map(item => item.id);
        }
    },
    methods: {
        afterBulkSelect(selectedRows, isAllSelect) {
            this.isAllRowSelected = isAllSelect;
            this.selectedRows = selectedRows
            this.isBulkActionActive = this.selectedRows.length > 0;
        },
        addLeadToOpposite() {
            this.addLeadModalActive= true
        },
        deleteBulkLead() {
            this.bulkDeleteModal = true;
        },
        bulkDeleteCancel() {
            this.bulkDeleteModal = false;
        },
        bulkDeleteConfirmed() {
            let url = route(`${this.bulkContext}.bulk-delete`),
                data = {
                    deletable_ids: this.selectedIds,
                    is_all_selected: this.isAllRowSelected
                }
            this.axiosPost({url, data}).then(res => {
                this.$toastr.s(res.data.message);
                this.$hub.$emit(`reload-${this.tableId}`);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            })
        }
    }
}