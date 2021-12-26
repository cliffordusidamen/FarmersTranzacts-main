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
                    'btn-block text-uppercase': true,
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
                <span class="text-white" v-if="processing">processing</span>
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