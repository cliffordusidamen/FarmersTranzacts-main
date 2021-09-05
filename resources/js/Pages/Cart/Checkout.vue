<template>
    <app-layout>
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <form ref="checkoutForm" class="row">
                        <div class="col-lg-9">
                            <h2 class="checkout-title">Shipping Details</h2>
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >Address Line 1</label
                                    >
                                    <input
                                        name="line_1"
                                        v-model="form.line_1"
                                        type="text"
                                        maxlength="150"
                                        class="form-control mb-n1"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.line_1" />
                                </div>
                                <!-- End .col-sm-6 -->

                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >Address Line 2</label
                                    >
                                    <input
                                        name="line_2"
                                        v-model="form.line_2"
                                        maxlength="150"
                                        type="text"
                                        class="form-control mb-n1"
                                    />
                                    <jet-input-error :message="form.errors.line_2" />
                                </div>
                                <!-- End .col-sm-6 -->
                            </div>
                            <!-- End .row -->

                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >Town/City</label
                                    >
                                    <input
                                        name="city"
                                        v-model="form.city"
                                        maxlength="25"
                                        type="text"
                                        class="form-control mb-n1"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.city" />
                                </div>
                                <!-- End .col-sm-6 -->

                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >State</label
                                    >
                                    <custom-select
                                        id="type"
                                        name="state"
                                        v-model="form.state_id"
                                        :options="app_states"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.state_id" />
                                </div>
                                <!-- End .col-sm-6 -->
                            </div>
                            <!-- End .row -->

                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >Postal Code</label
                                    >
                                    <input
                                        type="text"
                                        maxlength="10"
                                        name="postal_code"
                                        v-model="form.postal_code"
                                        class="form-control mb-n1"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.postal_code" />
                                </div>
                                <!-- End .col-sm-6 -->

                                <div class="col-sm-6 mb-2">
                                    <label class="required font-weight-bold"
                                        >County</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control bg-white"
                                        value="Nigeria"
                                        readonly
                                    />
                                </div>
                                <!-- End .col-sm-6 -->
                            </div>
                            <!-- End .row -->

                            <label>Order notes (optional)</label>
                            <textarea
                                class="form-control mb-n1"
                                maxlength="200"
                                name="order_notes"
                                v-model="form.order_notes"
                                cols="30"
                                rows="4"
                                placeholder="Notes about your order, e.g. special notes for delivery"
                            ></textarea>
                            <jet-input-error :message="form.errors.order_notes" />
                        </div>
                        <aside class="col-lg-3">
                            <div class="summary">
                                <h3 class="summary-title">Your Order</h3>

                                <table class="table table-summary">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="{
                                                product,
                                                quantity,
                                                total,
                                            } in cartItems"
                                            :key="product.id"
                                        >
                                            <td>
                                                <inertia-link href="#">{{
                                                    product.name
                                                }}</inertia-link>
                                                <br />
                                                <strong>x{{ quantity }}</strong>
                                            </td>
                                            <td>
                                                &#8358;{{
                                                    Number(
                                                        total
                                                    ).toLocaleString()
                                                }}
                                            </td>
                                        </tr>
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>
                                                &#8358;{{
                                                    Number(
                                                        cart.grand_total
                                                    ).toLocaleString()
                                                }}
                                            </td>
                                        </tr>
                                        <!-- End .summary-subtotal -->
                                        <tr>
                                            <td>Shipping:</td>
                                            <td>
                                                &#8358;{{
                                                    (0.0).toLocaleString()
                                                }}
                                            </td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>
                                                &#8358;{{
                                                    Number(
                                                        cart.grand_total
                                                    ).toLocaleString()
                                                }}
                                            </td>
                                        </tr>
                                        <!-- End .summary-total -->
                                    </tbody>
                                </table>

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-order btn-block"
                                    :disabled="processing"
                                >
                                    <span v-if="!processing">
                                        <span class="btn-text">Place Order</span>
                                        <span class="btn-hover-text">
                                            Proceed to Checkout
                                        </span>
                                    </span>
                                    <span v-else>Please wait...</span>
                                </button>
                            </div>
                        </aside>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import useCartManager from "@/composables/useCartManager";
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { ref } from "@vue/reactivity";
import { onMounted } from "@vue/runtime-core";
import CustomSelect from "@/Components/CustomSelect";
import JetInputError from "@/Jetstream/InputError";

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        CustomSelect,
        JetInputError,
    },
    setup() {
        const breadcrumbLinks = [
            {
                text: "Shop",
                link: route("shop.index"),
            },
            {
                text: "Cart",
                link: route("shopping_cart.view"),
            },
            {
                text: "Checkout",
            },
        ];

        const { cart, cartItems } = useCartManager();

        const { app_states } = usePage().props.value;
        const jqueryValidator = ref(null);
        const checkoutForm = ref(null);
        const processing = ref(false);
        const form = useForm({
            line_1: "",
            line_2: "",
            city: "",
            state_id: "",
            postal_code: "",
        });

        const submit = () => {
            processing.value = true;
            form.post(route("shopping_cart.order"), {
                preserveState: true,
                onFinish: () => {
                    processing.value = false;
                },
                onSuccess: () => {
                    processing.value = false;
                },
                onError: () => {
                    processing.value = false;
                },
            });
        };

        const validationConfig = () => {
            return {
                submitHandler() {
                    checkoutForm.value.classList.add("was-validated");
                    submit();
                },
                rules: {
                    line_1: {
                        required: true,
                        minlength: 3,
                        maxlength: 150,
                    },
                    line_2: {
                        required: false,
                        minlength: 3,
                        maxlength: 150,
                    },
                    city: {
                        required: true,
                        minlength: 2,
                        maxlength: 25,
                    },
                    state_id: "required",
                    order_notes: {
                        require: false,
                        maxlength: 200,
                    },
                },
            };
        };

        onMounted(() => {
            jqueryValidator.value = $(checkoutForm.value).validate(
                validationConfig()
            );
        });

        return {
            breadcrumbLinks,
            form,
            cart,
            cartItems,
            checkoutForm,
            processing,
            app_states,
        };
    },
};
</script>
