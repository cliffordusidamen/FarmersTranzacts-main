<template>
    <span>
        <span>
            <slot :show-modal="showModal" />
        </span>

        <jet-dialog-modal
            :id="id"
            :show-header="false"
            :show-footer="false"
            data-backdrop="static"
            data-keyboard="false"
        >
            <template #content>
                <div
                    v-show="closeBtnVisible"
                    class="text-right p-2"
                >
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <h4 class="mb-3">
                        Where will your order will be delivered?
                    </h4>

                    <delivery-state-form
                        :product-id="product?.id || ''"
                        @submitting-delivery-state="showCloseButton(false)"
                        @submitting-finished="closeModal"
                    />
                </div>
            </template>
        </jet-dialog-modal>
    </span>
</template>

<script>
import { ref } from '@vue/reactivity';
import JetButton from "../Jetstream/Button";
import JetDialogModal from "../Jetstream/DialogModal";
import { useForm } from '@inertiajs/inertia-vue3'
import DeliveryStateForm from '@/Components/Forms/DeliveryStateForm.vue'

export default {

    components: {
        JetButton,
        JetDialogModal,
        DeliveryStateForm,
    },

    props: {
        id: {
            type: String,
            required: true,
        },
        product: {
            type: Object,
            default: () => ({}),
        },
    },

    setup(props, context) {
        
        const bootstrap = ref(null)
        const closeBtnVisible = ref(true)

        const form = useForm({
            state_id: "",
            error: "",
        })

        const showModal = () => {
            bootstrap.value = $(`#${props.id}`);
            bootstrap.value.modal({
                show: true,
                backdrop: 'static',
            });
        }

        const showCloseButton = (show) => {
            closeBtnVisible.value = show
        }

        const closeModal = () => {
            showCloseButton(true)
            bootstrap.value.modal('hide')
        }

        return {
            bootstrap,
            form,
            showModal,
            showCloseButton,
            closeBtnVisible,
            closeModal,
        }

    }

};
</script>

<style scoped>
button.close {
    font-size: 2.3rem;
}
</style>