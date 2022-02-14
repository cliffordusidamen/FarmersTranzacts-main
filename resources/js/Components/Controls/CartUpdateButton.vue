<template>
    <form class="d-block w-100">
        <toggles-delivery-state-modal
            :id="`addToCart_${product.id}`"
            :product="product"
            v-slot="slotProps"
        >
            <div
                v-if="showQuantityInput && !isInCart"
                class="cart-product-quantity"
            >
                <input
                    type="number"
                    class="form-control qty-spinner"
                    v-model="quantity"
                    min="1"
                    :max="product.maximum_order_quantity"
                    step="1"
                    data-decimals="0"
                    required=""
                    style="display: none;"
                />
            </div>
            <span
                v-if="!processing && showQuantityInput && isInCart"
                class="mr-2 text-uppercase"
            >
                {{Number(quantityInCart).toLocaleString()}} in cart
            </span>
            <button
                type="button"
                :class="{
                    'btn-block': !showQuantityInput,
                    'text-uppercase': true,
                    'btn-product': !isInCart && !processing,
                    'bg-danger border-danger text-white': isInCart && !processing,
                    'btn-product-processing': processing,
                }"
                @click="
                    !cart?.state?.id
                        ? slotProps.showModal()
                        : updateCart()
                "
                :disabled="processing"
            >
                <span v-if="processing">processing</span>
                <span v-else-if="isInCart" class="text-white">
                    remove <span class="d-none d-sm-inline text-white">from cart</span>
                </span>
                <span class="text-white" v-else>
                    add <span class="d-none d-sm-inline text-white">to cart</span>
                </span>
            </button>
        </toggles-delivery-state-modal>
    </form>
</template>

<script>
import { ref } from '@vue/reactivity'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import { onMounted, onUnmounted } from '@vue/runtime-core'

import Button from '../../Jetstream/Button.vue'
import TogglesDeliveryStateModal from '@/Components/TogglesDeliveryStateModal.vue'


export default {
    components: {
        Button,
        TogglesDeliveryStateModal,
    },
    props: {
        product: {
            required: true,
            type: Object,
        },

        showQuantityInput: {
            type: Boolean,
            default: false,
        },
    },

    setup({product}) {

        const processing = ref(false)
        const quantity = ref(1)
        const { cart } = usePage().props.value

        console.log('cart', cart)

        const isInCart = cart?.items && undefined != cart?.items[product.id]
        const quantityInCart = !isInCart ? 0 : cart?.items[product.id].quantity

        const targetRoute = isInCart
            ? route('shopping_cart.remove_item')
            : route('shopping_cart.add_item');

        const updateCart = () => {
            processing.value = true
            const data = {
                product_id: product.id,
                quantity: quantity.value,
            }
            Inertia.post(targetRoute, data, {
                onFinish: () => window.location.reload(),
                onError: errors => {
                    console.log(`Error while ${isInCart ? 'removing' : 'adding'} item`, {
                        product,
                        error: errors,
                    })
                    processing.value = false
                },
            })

        }

        const getProductQuantityOptions = () => {
            const options = {}

            for (let i = 1; i <= product.maximum_order_quantity; i++) {
                options[i] = i
            }

            return options;
        }

        onMounted(() => {
            $("input.qty-spinner").inputSpinner({
                decrementButton: '<i class="icon-minus"></i>',
                incrementButton: '<i class="icon-plus"></i>',
                groupClass: 'input-spinner',
                buttonsClass: 'btn-spinner',
                buttonsWidth: '26px'
            });
        })

        onUnmounted(() => {
            $("input.qty-spinner").inputSpinner("destroy")
        })

        return  {
            cart,
            getProductQuantityOptions,
            isInCart,
            processing,
            quantity,
            quantityInCart,
            updateCart,
        }
    },
}
</script>