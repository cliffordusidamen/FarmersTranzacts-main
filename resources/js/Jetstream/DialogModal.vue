<template>
    <teleport to="body">
        <modal :id="id" :max-width="maxWidth">
            <div class="modal-content">
                <div
                    v-if="showHeader"
                    class="modal-header"
                >
                    <h5 class="modal-title">
                        <slot name="title"> </slot>
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="content"> </slot>
                </div>
                <div
                    v-if="showFooter"
                    class="modal-footer bg-light"
                >
                    <slot name="footer"> </slot>
                </div>
            </div>
        </modal>
    </teleport>
</template>

<script>
import Modal from "./Modal";

export default {
    components: {
        Modal,
    },

    props: {
        id: {
            type: String,
            required: true,
        },

        showHeader: {
            type: Boolean,
            default: true,
        },

        showFooter: {
            type: Boolean,
            default: true,
        },

        maxWidth: {
            default: "2xl",
        },
    },
};
</script>

<style scoped>
button.close {
    font-size: 2.3rem;
}
</style>
