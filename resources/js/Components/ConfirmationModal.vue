<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from '@vue/reactivity';
import DefaultModal from '@/Components/DefaultModal.vue';
import { onMounted } from '@vue/runtime-core';


const message = ref('')
const url = ref('')
const method = ref('')
const defaultMessage = 'Are you sure you want to do this?'
const showEventName = 'show-confirmation-modal'

const submit = () => {
    Inertia.post(url.value, {_method: method.value});

}

onMounted(() => {
    document.addEventListener('confirm-action', e => {
        console.log('Fir sdfsdfsd', e.detail)
        message.value = e.detail?.message || defaultMessage;
        url.value = e.detail?.url || '';
        method.value = e.detail?.method || 'DELETE';

        const showConfirmationEvent = new Event(showEventName);
        document.dispatchEvent(showConfirmationEvent);

    })
})

</script>

<template>
    <default-modal
        id="confirmation-modal"
        :show-event="showEventName"
    >
        <h4 className='p-3 border-bottom '>
            <i class="la la-exclamation-circle"></i>
            Confirm action
        </h4>
        <div class="px-3">
            <div className='lead'>{{message}}</div>
            <div className='text-right m-2'>
                <button className='btn btn-sm btn-success' data-dismiss="modal">
                    NO
                </button>
                <button className='btn btn-sm btn-danger-outline' data-dismiss="modal" @click="submit">
                    YES
                </button>
            </div>
        </div>
    </default-modal>
</template>