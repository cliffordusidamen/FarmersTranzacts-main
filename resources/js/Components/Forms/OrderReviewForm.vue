<template>
    <form ref="vendorOrderReviewForm">
        <h5>Rate your experience with this seller:</h5>
        <div class="form-group">
            <jet-label class="required" for="rating" value="Rating" />
            <custom-select
                name="customer_review_rating"
                maxlength="255"
                v-model="form.customer_review_rating"
                :options="ratingOptions"
                autocomplete="off"
                required
            />
            <jet-input-error :message="form.errors.customer_review_rating" />
        </div>

        <div class="form-group">
            <jet-label class="required" for="review" value="Review" />
            <jet-textarea
                name="customer_review_comment"
                maxlength="500"
                rows="2"
                v-model="form.customer_review_comment"
                autocomplete="off"
                required
            />
            <jet-input-error :message="form.errors.customer_review_comment" />
        </div>

        <div class="form-group text-center">
            <jet-button :disabled="form.processing">SUBMIT</jet-button>
        </div>
    </form>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import { onMounted, ref } from "@vue/runtime-core";

import CustomSelect from '../../Components/CustomSelect';
import JetTextarea from '../../Jetstream/Textarea';
import JetLabel from '../../Jetstream/Label';
import JetButton from '../../Jetstream/Button';
import JetInputError from '../../Jetstream/InputError';

export default {
    components: {
        CustomSelect,
        JetLabel,
        JetInputError,
        JetTextarea,
        JetButton,
    },

    props: {
        vendorOrder: {
            required: true,
            type: Object,
        },
        ratingOptions: {
            required: true,
            default: () => ({}),
        },
    },

    setup(props) {

        const vendorOrderReviewForm = ref(null);
        
        const form = useForm({
            vendor_order_id: props.vendorOrder.id,
            customer_review_rating: '',
            customer_review_comment: '',
        })

        const submit = () => {
            form.processing = true
            const routeUrl = route('my_orders.review_vendor_order', {
                vendor_order: props.vendorOrder.id,
            });

            form.post(routeUrl, {
                onFinish: () => window.location.reload(),
            })
        }
        
        onMounted(() => {
            $(vendorOrderReviewForm.value).validate({
                rules: {
                    customer_review_rating: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 5,
                    },
        
                    customer_review_comment: {
                        maxlength: 1000,
                        required: true,
                    },
        
                },
                submitHandler: (theForm) => {
                    console.log({...theForm})
                    vendorOrderReviewForm.value.classList.add("was-validated");
                    submit();
                },
            });
        })

        return {
            form,
            vendorOrderReviewForm,
        }
    },
}
</script>