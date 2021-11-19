
import { ref } from '@vue/reactivity'
import { usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'

export default function useCartManager() {
    
    const processing = ref(false)
    const { cart } = usePage().props.value
    const cartItems = cart?.items || {}
    const cartItemsCount = Object.keys(cartItems).length

    const cartHasItems = cartItemsCount > 0

    const isInCart = (product) => {
        cart?.items && undefined != cart?.items[product.id]
    }

    const addItem = (product, quantity) => {
        processing.value = true
        const data = {
            'product_id': product.id,
            'quantity': undefined == quantity || !Number(quantity) ? 1 : quantity,
        }

        Inertia.post(route('shopping_cart.add_item'), data, {
            onFinish: () => window.location.reload(),
        })
    }

    const removeItem = (product) => {
        processing.value = true

        console.log('Removeing theitem form the cart', product.id)

        Inertia.post(route('shopping_cart.remove_item'), { 'product_id': product.id }, {
            onFinish: () => window.location.reload(),
        })
    }

    return  {
        cart,
        cartItems,
        cartItemsCount,
        cartHasItems,
        processing,
        isInCart,
        addItem,
        removeItem,
    }
}