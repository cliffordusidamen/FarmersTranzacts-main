<template>
    <form class="d-block w-100">
        <toggles-delivery-state-modal
            :id="`addToCart_${product.id}`"
            :product="product"
            v-slot="slotProps"
        >
            <button
                type="button"
                :class="{
                    'btn-product btn-block btn-cart text-white': true,
                    'bg-primary bg-primary': !isInCart && !processing,
                    'bg-danger border-danger': isInCart && !processing,
                    'bg-secondary border-secondary': processing,
                }"
                @click="
                    !cart?.state?.id
                        ? slotProps.showModal()
                        : updateCart()
                "
                :disabled="processing"
            >
                <span class="text-white">
                    {{
                        processing
                            ? 'processing'
                            : (isInCart ? 'remove from cart' : 'add to cart')
                    }}
                </span>
            </button>
        </toggles-delivery-state-modal>
    </form>
</template>

<script>
import { ref } from '@vue/reactivity'
import { usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import Button from '../../Jetstream/Button.vue'
import TogglesDeliveryStateModal from '@/Components/TogglesDeliveryStateModal.vue'
import { Inertia } from '@inertiajs/inertia'


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
    },

    setup({product}) {

        const processing = ref(false)
        const { cart } = usePage().props.value

        console.log('cart', cart)

        const isInCart = cart?.items && undefined != cart?.items[product.id]

        const targetRoute = isInCart
            ? route('shopping_cart.remove_item')
            : route('shopping_cart.add_item');

        const updateCart = () => {
            processing.value = true

            Inertia.post(targetRoute, { 'product_id': product.id }, {
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

        return  {
            cart,
            isInCart,
            updateCart,
            processing,
        }
    },
}
</script>