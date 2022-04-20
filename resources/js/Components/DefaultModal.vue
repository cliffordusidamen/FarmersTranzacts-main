<script setup>
import { ref } from "@vue/reactivity"
import { onMounted, onUnmounted } from "@vue/runtime-core"

const props = defineProps({
    id: {
        type: String,
        required: true,
    },

    title: {
        type: String,
        default: '',
    },

    size: {
        type: String,
        default: 'md',
    },

    handleClose: {
        type: Function,
        default: (() => console.log(`Closing modal`)),
    },

    showEvent: {
        type: String,
        required: true,
    },

    static: {
        type: Boolean,
        default: false,
    },
})

const modal = ref(null)
const modalEventListener = ref(null)


onMounted(() => {

    modal.value = $(`#${props.id}`).modal({
        show: false,
        backdrop: props.static == 'static' ? 'static' : true,
        keyboard: false,
        focus: false,
    })
    modalEventListener.value = document.addEventListener(props.showEvent, e => {
        modal.value.modal('show')
    })

    $(`#${props.id}`).on('hidden.bs.modal', () => {
        props.handleClose()
    })

})

onUnmounted(() => {
    document.removeEventListener(props.showEvent, modalEventListener.value, false)
    modal.value.modal('dispose')
})
</script>

<template>
    <teleport to="body">
        <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="`${id}Label`" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div
                        v-if="title?.length"
                        class="modal-header"
                    >
                        <h5 class="modal-title" :id="`${id}Label`">
                            {{ title }}
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <i class="la la-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <slot></slot>
                    </div>

                    <div class="modal-footer p-0">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>