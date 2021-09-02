
import { ref } from '@vue/reactivity'
import { usePage } from '@inertiajs/inertia-vue3'
import axios from 'axios'

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

        axios.post(route('shopping_cart.add_item'), {
            'product_id': product.id,
            'quantity': undefined == quantity || !Number(quantity) ? 1 : quantity,
        })
        .then(() => {
            window.location.reload()
        })
        .catch(errors => {
            console.log(`Error while adding item`, {
                product,
                error: errors,
            })
            processing.value = false
        })
    }

    const removeItem = (product) => {
        processing.value = true

        axios.post(route('shopping_cart.remove_item'), { 'product_id': product.id })
        .then(() => {
            window.location.reload()
        })
        .catch(errors => {
            console.log(`Error while removing item`, {
                product,
                error: errors,
            })
            processing.value = false
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