<template>
    <app-layout>
        <page-header title="Cancel seller order" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <div class="d-flex flex-column-reverse justify-content-md-between flex-md-row">
                                <div>
                                    <strong class="mr-2 text-muted">Reference number:</strong>
                                    {{ vendorOrder.order.reference_number }}
                                </div>

                                <div class="text-right">
                                    <strong class="text-dark">Status: </strong>
                                    {{ vendorOrder.delivery_status.name }}
                                </div>
                            </div>

                            <div>
                                <p>
                                    <strong class="mr-2 text-muted">SHOP NAME:</strong>
                                    {{ vendorOrder.vendor.shop_name }}
                                </p>
                                <p>
                                    {{ vendorOrder.items.length > 0 ? '1 product' : `${vendorOrder.items.length} products` }}
                                    with delivery at 
                                    &#8358;{{ Number(vendorOrder.total_amount).toLocaleString() }}
                                </p>
                            </div>
                            <form ref="frmCancelOrder">
                                <div class="card border rounded-sm shadow mb-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <jet-label for="cancel_reason" value="Why do you want to cancel this order?" />
                                            <jet-textarea
                                                rows="3"
                                                name="cancel_reason"
                                                maxlength="500"
                                                id="cancel_reason"
                                                type="text"
                                                v-model="form.cancel_reason"
                                                autocomplete="off"
                                            />
                                            <jet-input-error :message="form.errors.cancel_reason" />
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <jet-button :disabled="form.processing">SUBMIT</jet-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import useDate from '@/composables/useDate'
import Pagination from '@/Components/Pagination.vue'
import JetLabel from '@/Jetstream/Label'
import JetTextarea from '@/Jetstream/Textarea'
import JetInputError from '@/Jetstream/InputError'
import JetButton from '@/Jetstream/Button'
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';

export default {

    components: {
        JetLabel,
        JetTextarea,
        JetButton,
        JetInputError,
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        Pagination,
    },

    setup() {

        const { vendorOrder } = usePage().props.value
        const { formatDate } = useDate()
        const breadcrumbLinks = [
            {
                link: route('my_orders.index'),
                text: 'My Orders'
            },
            {
                text: vendorOrder.order.reference_number,
            },
        ];

        const frmCancelOrder = ref(null)
        const jqueryValidator = ref(null)
        const form = useForm({
            _method: "POST",
            cancel_reason: "",
        })

        const submit = () => {
            form.processing = true;
            const actionRoute = route("my_orders.cancel_vendor_order", {vendor_order: vendorOrder.id});

            form.post(actionRoute, {
                preserveScroll: true,
                 onFinish: () => {
                    form.processing = false;
                },
            });
        }

        onMounted(() => {
            jqueryValidator.value = $(frmCancelOrder.value).validate({
                submitHandler() {
                    frmCancelOrder.value.classList.add("was-validated");
                    submit();
                },
                rules: {
                    cancel_reason: {
                        required: true,
                        minlength: 3,
                        maxlength: 500,
                    },
                },
            });
        })
        

        return {
            breadcrumbLinks,
            vendorOrder,
            formatDate,
            form,
            frmCancelOrder,
        };
    },
};
</script>