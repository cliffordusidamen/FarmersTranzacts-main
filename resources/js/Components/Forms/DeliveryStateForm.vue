<template>
    <form @submit.prevent="submit">
        <!-- select field -->
        <custom-select v-model="form.state_id" :options="options" />
        <p class="text-right">
            <button
                type="submit"
                class="btn btn-primary btn-sm"
                :disabled="processing"
            >
                SUBMIT
                <div v-show="processing" class="spinner-border text-light ml-3" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </p>
    </form>
</template>

<script>
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import CustomSelect from "@/Components/CustomSelect.vue";
import { ref } from "@vue/reactivity";
import { Inertia } from '@inertiajs/inertia';

export default {
    emits: ["submittingDeliveryState", "submittingFinished"],

    components: {
        CustomSelect,
    },

    props: {
        productId: {
            type: String,
            default: "",
        },
    },

    setup(props, context) {
        const pageProps = usePage().props.value;
        const processing = ref(false);
        const options = pageProps?.states || {};
        const cart = pageProps?.cart || {};
        const form = useForm({
            product_id: props?.productId || "",
            state_id: cart?.state?.id || '',
        });
        console.log('Submiting', cart)

        const submit = () => {
            processing.value = true;
            context.emit("submittingDeliveryState");
            const data = {
                state_id: form.state_id,
                product_id: form.product_id,
            }
            Inertia.post(route("shopping_cart.set_delivery_state"), data, {
                onFinish: () => {
                    processing.value = false;
                    context.emit("submittingFinished");
                },
                onError: (errors) => {
                    processing.value = false;
                    console.log('Failed', {...errors})
                }
            })
        };

        return {
            options,
            processing,
            form,
            submit,
        };
    },
};
</script>
