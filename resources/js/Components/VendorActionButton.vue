<template>
    <div class="dropdown">
        <button
            class="btn btn-link dropdown-toggle"
            type="button"
            id="dropdownMenuSizeButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            :disabled="processingAction"
        >
            Actions
        </button>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuSizeButton">
            <a href="#" class="dropdown-item text-left font-size-normal">View</a>
            <a v-if="approvalPending" @click.prevent="setApprovalStatus" :href="`#${approvalModalId}`" class="dropdown-item text-left font-size-normal" data-toggle="modal">Approve</a>
            <a v-if="approvalPending" @click.prevent="setDisapprovalStatus" :href="`#${disapproveModalId}`" class="dropdown-item text-left font-size-normal" data-toggle="modal">Disapprove</a>
        </div>
    </div>

    <!-- APPROVAL MODAL -->
    <confirmation-modal :ref="approvalModalId" :id="approvalModalId">
        <template #title>Confirm action</template>
        <template #content>
            <strong>Are you sure you want to approve this vendor?</strong>
        </template>
        <template #footer>
            <div class="text-center">
                <button type="button" v-show="!processingAction" class="btn btn-primary mr-3" data-dismiss="modal" id="btnCancelApproval">No</button>
                <button type="button" v-show="!processingAction" class="btn btn-outline-dark" @click="sendRequest">Yes</button>
                <p class="text-italic text-muted text-end" v-show="processingAction">
                    Please wait...
                </p>
            </div>
        </template>
    </confirmation-modal>

    <!-- DISAPPROVAL MODAL -->
    <confirmation-modal :ref="disapproveModalId" :id="disapproveModalId">
        <template #title>Confirm action</template>
        <template #content>
            <strong>Enter reason for disapproval</strong>
            <jet-textarea v-model="reject_reason" placeholder="Comments here.." required />
        </template>
        <template #footer>
            <div class="text-center">
                <button type="button" v-show="!processingAction" class="btn btn-primary mr-3" data-dismiss="modal" id="btnCancelDisapproval">Cancel</button>
                <button type="button" v-show="!processingAction" class="btn btn-outline-dark" @click="sendRequest">Submit</button>
                <p class="text-italic text-muted text-end" v-show="processingAction">
                    Please wait...
                </p>
            </div>
        </template>
    </confirmation-modal>
</template>

<script>
import JetTextarea from "@/Jetstream/Textarea";
import ConfirmationModal from "@/Jetstream/ConfirmationModal";

export default {
    props: ['vendor'],

    components: {
        JetTextarea,
        ConfirmationModal,
    },

    computed: {
        approvalPending() {
            return this.refVendor.approval_status.toLowerCase() == 'awaiting approval';
        },

        approvalModalId() {
            return 'approveModal_' + this.vendor.id;
        },

        disapproveModalId() {
            return 'disapproveModal_' + this.vendor.id;
        },

        refVendor() {
            return this.responseVendor || this.vendor;
        },
    },

    data() {
        return {
            approval_status: '',
            reject_reason: '',
            processingAction: false,
            responseVendor: null,
        };
    },

    methods: {

        setDisapprovalStatus() {
            this.approval_status = 'disapproved'
        },

        setApprovalStatus() {
            this.approval_status = 'approved'
            this.reject_reason = '';
        },

        closeOpenModal() {
            if (this.approval_status == 'disapproved') {
                return $('#btnCancelDisapproval').trigger('click');
            }

            $('#btnCancelApproval').trigger('click');
        },

        sendRequest() {
            console.log('Processing request...', this.approval_status, {
                'approval_status': this.approval_status,
                'reject_reason': this.reject_reason,
            });

            this.processingAction = true

            const routeUrl = route('vendors.change_status', this.vendor.id);

            axios.post(routeUrl, {
                'approval_status': this.approval_status,
                'reject_reason': this.reject_reason,
            })
            .then((response) => {
                console.log(response)
                if (undefined == response.data.vendor) {
                    return alert('An error occured! Please, try again.');
                }
                this.responseVendor = response.data.vendor;
                this.$emit('statusChanged', response.data.vendor)

                this.closeOpenModal();
                this.processingAction = false
            })
            .catch((error) => {
                console.log(error)
                this.processingAction = false
                alert('An error occured! Please, try again.');
            });
        },
    },

}
</script>
