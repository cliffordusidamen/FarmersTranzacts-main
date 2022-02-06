<script setup>
import { ref } from "@vue/reactivity"
import { onMounted } from "@vue/runtime-core"

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


onMounted(() => {

    modal.current = $(`#${props.id}`).modal({
        show: false,
        backdrop: props.static == 'static' ? 'static' : true,
        keyboard: false,
        focus: false,
    })
    document.addEventListener(props.showEvent, e => {
        modal.current.modal('show')
    })

    $(`#${props.id}`).on('hidden.bs.modal', () => {
        props.handleClose()
    })

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
                            <i class="la la-exclamation-circle mr-2"></i>
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