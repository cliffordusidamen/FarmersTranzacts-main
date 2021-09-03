<template>
    <app-layout :show-categories="false">
        <div class="page-content">
            <div class="cart">
                <div class="container">
                    <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
                    <div v-if="!cartItemsCount" class="error-content text-center bg-white">
                        <h2 class="error-title">No item in you cart</h2>
                        <p class="h4 text-muted">We have a lot of great items &amp; offers for you!</p>
                        <inertia-link :href="route('shop.index')" class="btn btn-primary btn-minwidth-lg">
                            <span>START SHOPPING NOW</span>
                            <i class="icon-long-arrow-right"></i>
                        </inertia-link>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <form @submit="updateCart">
                                <table class="table table-cart table-mobile">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="{product, quantity } in cartItems" :key="product.id">
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="assets/images/products/table/product-1.jpg" alt="Product image">
                                                        </a>
                                                    </figure>

                                                    <h3 class="product-title">
                                                        <a href="#">{{ product.name }}</a>
                                                    </h3><!-- End .product-title -->
                                                </div><!-- End .product -->
                                            </td>
                                            <td class="price-col">&#8358;{{ Number(product.price).toLocaleString() }}</td>
                                            <td class="quantity-col">
                                                <div class="cart-product-quantity">
                                                    <input type="number" class="form-control" @change="$event => updateProductQuantity(product, $event.target.value)" :value="quantity" min="1" max="10" step="1" data-decimals="0" required="" style="display: none;">
                                                </div>
                                            </td>
                                            <td class="total-col">&#8358;{{ Number(itemTotalPricesRx[product.id]).toLocaleString() }}</td>
                                            <td class="remove-col">
                                                <button class="btn-remove" @click="removeItem(product)"><i class="icon-close"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            
                                <div class="cart-bottom">
                                    <div class="cart-discount">
                                    </div><!-- End .cart-discount -->

                                    <button type="submit" :disabled="updatingCart" class="btn btn-primary">
                                        <span>UPDATE CART</span><i class="icon-refresh"></i>
                                    </button>
                                </div><!-- End .cart-bottom -->
                            </form>
                        </div>

                        <aside class="col-md-3">
                            <div class="summary summary-cart">
                                <h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <tbody>
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td>&#8358;{{ Number(grandTotal).toLocaleString()}}</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr class="summary-shipping">
                                            <td>Shipping:</td>
                                            <td>&#8358;0.00</td>
                                        </tr>

                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td>&#8358;{{ Number(grandTotal).toLocaleString()}}</td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->

                                <inertia-link :href="route('shopping_cart.checkout')" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO CHECKOUT</inertia-link>
                            </div><!-- End .summary -->

                            <inertia-link :href="route('shop.index')" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i class="icon-refresh"></i></inertia-link>
                        </aside><!-- End .col-lg-3 -->
                    </div>
                </div><!-- End .container -->


            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout'
import BreadcrumbDefault from '@/Components/Utils/BreadcrumbDefault.vue'
import useCartManager from '@/composables/useCartManager'
import { onMounted, reactive, ref } from '@vue/runtime-core'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
    },

    setup() {
        const breadcrumbLinks = [
            {
                'text': 'Shop',
                'link': route('shop.index')
            },
            {text: 'Cart'},
        ]

        const {
            cart,
            cartItems,
            cartItemsCount,
            cartHasItems,
            processing,
            removeItem,
        } =  useCartManager()

        const updatingCart = ref(false)

        const cartItemsRx = ref({...cart.items});

        const updateCart = (event) => {
            event.preventDefault()
            updatingCart.value = true

            const postData = {}

            for (const key in cartItemsRx.value) {
                if (Object.hasOwnProperty.call(cartItemsRx.value, key)) {
                    postData[key] = {
                        product_id: cartItemsRx.value[key].product.id,
                        quantity: cartItemsRx.value[key].quantity,
                    }
                }
            }

            Inertia.post(route('shopping_cart.update_all'), {items: postData}, {
                onSuccess: () => {
                    window.location.reload()
                },
                onError: (errors) => {
                    updatingCart.value = false
                    console.log('Error updating cart', errors)
                }
            })
        }

        onMounted(() => {
            window.$('input[type="number"]').inputSpinner()
        })

        let itemTotalPrices = {}
        for (const productId in cartItems) {
            if (Object.hasOwnProperty.call(cartItems, productId)) {
                itemTotalPrices[productId] = Number(cartItems[productId]['total']);
            }
        }

        const itemTotalPricesRx = reactive({...itemTotalPrices})

        const grandTotal = ref(cart.grand_total)
        const updateGrandTotal = () => {
            let newGrandTotal = 0;
            for (const productId in itemTotalPricesRx) {
                if (Object.hasOwnProperty.call(itemTotalPricesRx, productId)) {
                    newGrandTotal += Number(itemTotalPricesRx[productId]);
                }
            }
            grandTotal.value = newGrandTotal
        }
        
        const updateProductQuantity = (product, quantity) => {
            cartItemsRx.value[product.id].quantity = quantity
            console.log('New quantity for "' + product.name + '"', quantity)
            itemTotalPricesRx[product.id] = Number(quantity) * Number(product.price)
            updateGrandTotal()
        }
        return {
            breadcrumbLinks,
            cart,
            cartItems,
            cartItemsCount,
            cartHasItems,
            updatingCart,
            processing,
            removeItem,
            updateProductQuantity,
            updateCart,
            itemTotalPricesRx,
            grandTotal,
        }
    }
}
</script>