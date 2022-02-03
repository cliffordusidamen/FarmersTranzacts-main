<template>
    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler" @click="toggleMobileMenu">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>
                
                <a href="/" class="logo">
                    <img src="/img/logo-with-slogan-small.png" />
                </a>
            </div><!-- End .header-left -->

            <div class="header-center">
                <div class="header-search header-search-extended header-search-visible header-search-no-radius d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form @submit.prevent="submitMainSearch">
                        <div class="header-search-wrapper search-wrapper-wide">
                            <div class="select-custom">
                                <custom-select
                                    id="cat"
                                    name="cat"
                                    v-model="form.cat"
                                    :options="categoriesList"
                                    placeholder="All categories"
                                />
                            </div><!-- End .select-custom -->
                            <label for="q" class="sr-only">Search</label>
                            <input type="search" class="form-control" name="q" id="q" v-model="form.q" placeholder="Search product ..." />
                            <button class="btn btn-success" type="submit"><i class="icon-search"></i></button>
                        </div><!-- End .header-search-wrapper -->
                    </form>
                </div><!-- End .header-search -->
            </div><!-- End .header-center -->

            <div class="header-right">
                <div class="header-dropdown-link">

                    <div class="dropdown cart-dropdown">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                            <i class="icon-shopping-cart"></i>
                            <span v-show="cartHasItems" class="cart-count bg-dark">{{ cartItemsCount > 10 ? '*' : cartItemsCount }}</span>
                            <span class="cart-txt">Cart</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right border">
                            <div v-if="cartHasItems">
                                <div class="dropdown-cart-products">
                                    <div class=" border rounded-sm px-2 mx-n4 py-1 mb-2 text-xs d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            Delivery to:
                                        </span>
                                        <span class="text-uppercase text-right">
                                            {{ cart.state.state_name }}
                                            <toggles-delivery-state-modal
                                                :id="`addToCart_general`"
                                                v-slot="slotProps"
                                            >
                                                <a href="#" @click.prevent="slotProps.showModal()" class="btn btn-link text-success border-success">
                                                    CHANGE
                                                </a>
                                            </toggles-delivery-state-modal>
                                        </span>
                                    </div>
                                    <div class="product" v-for="{product, quantity, can_be_delivered} in cartItemsToDisplay" :key="product.id">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <inertia-link :href="route('shop.show_product', {slug: product.slug})">{{product.name}}</inertia-link>
                                            </h4>
                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">{{Number(quantity).toLocaleString()}}</span>
                                                x &#8358;{{Number(product.price).toLocaleString()}}
                                            </span>
                                            <span v-if="!can_be_delivered" class="d-block text-danger text-sm">
                                                Not deliverable to {{ cart.state.state_name }}
                                            </span>
                                        </div><!-- End .product-cart-details -->

                                        <figure class="product-image-container">
                                            <inertia-link
                                                :href="route('shop.show_product', { slug: product.slug })"
                                                class="product-image"
                                            >
                                                <img
                                                    :src="product.feature_image"
                                                    alt="Product image"
                                                    class="product-image"
                                                />
                                            </inertia-link>
                                        </figure>
                                        <a href="#" class="btn-remove text-dark" @click.prevent="removeItem(product)" title="Remove Product"><i class="icon-close"></i></a>
                                    </div><!-- End .product -->

                                    <div
                                        v-if="cartItemsCount > 2"
                                    >
                                        <inertia-link :href="route('shopping_cart.view')" class="btn btn-link">
                                            +{{ cartItemsCount - 2 }} 
                                            {{ cartItemsCount > 3 ? 'more items' : 'more item' }}
                                        </inertia-link>
                                    </div>

                                </div><!-- End .cart-product -->

                                <div class="dropdown-cart-total">
                                    <span>
                                        Delivery ({{ Number(cart.total_delivery_days) }} Days)
                                    </span>

                                    <span class="cart-total-price">&#8358;{{cart?.total_delivery_amount.toLocaleString()}}</span>
                                </div><!-- End .dropdown-cart-total -->

                                <div class="dropdown-cart-total">
                                    <span>Total</span>

                                    <span class="cart-total-price">&#8358;{{cart?.grand_total_with_delivery.toLocaleString()}}</span>
                                </div><!-- End .dropdown-cart-total -->

                                <div v-show="!processing" class="dropdown-cart-action">
                                    <inertia-link :href="route('shopping_cart.view')" class="btn btn-success">View Cart</inertia-link>
                                    <inertia-link v-show="cart?.can_be_delivered" :href="route('shopping_cart.checkout')" class="btn btn-outline-success"><span>Checkout</span><i class="icon-long-arrow-right"></i></inertia-link>
                                </div><!-- End .dropdown-cart-total -->
                            </div><!-- End .dropdown-menu -->
                            <div v-else class="text-muted m-1 p-1 rounded-sm">
                                No items in your cart!
                            </div>
                        </div><!-- End .dropdown-menu -->
                    </div><!-- End .cart-dropdown -->

                    <div class="dropdown compare-dropdown user-dropdown">
                        <a
                            href="#"
                            class="dropdown-toggle"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-display="static"
                            :title="!isAuthenticated ? 'Account' : truncatedUserFullName()"
                        >
                            <i class="icon-user"></i>
                            <span class="compare-txt">
                                {{ !isAuthenticated ? 'Account' : truncatedUserName() }}
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right user_nav_menu px-0">
                            <inertia-link
                                v-if="isAuthenticated"
                                :href="
                                    this.$page.props?.user.email_verified_at
                                        ? route('profile.show')
                                        : route('profile.unverified_profile')
                                "
                                class="dropdown-item"
                            >Profile</inertia-link>
                            <inertia-link v-if="isAuthenticated && this.$page.props?.userAgent?.data?.id"  :href="route('dashboard')" class="dropdown-item">Dashboard</inertia-link>
                            <inertia-link v-if="isAuthenticated"  :href="route('my_orders.index')" class="dropdown-item">My Orders</inertia-link>
                            <a v-if="isAuthenticated" href="#" class="dropdown-item" @click.prevent="logout">Log out</a>
                            <inertia-link v-if="!isAuthenticated" :href="route('login')" class="dropdown-item">
                                Log in
                            </inertia-link>

                            <inertia-link v-if="!isAuthenticated" :href="route('register')" class="dropdown-item">
                                Register
                            </inertia-link>
                        </div>
                    </div><!-- End .user-dropdown -->
                </div>
            </div><!-- End .header-right -->
        </div><!-- End. container -->
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
import utility from "@/Mixins/utility.js"
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import useCartManager from '@/composables/useCartManager'
import TogglesDeliveryStateModal from '@/Components/TogglesDeliveryStateModal.vue'
import CustomSelect from "@/Components/CustomSelect.vue";

export default {

    mixins: [utility],

    components: {
        TogglesDeliveryStateModal,
        CustomSelect,
    },

    setup() {
        const {
            cart,
            cartItems,
            cartItemsCount,
            cartHasItems,
            processing,
            removeItem,
        } =  useCartManager()

        const cartItemsToDisplay = Object.keys(cartItems).slice(0, 2).map(key => cartItems[key])

        const pageProps = usePage().props.value
        const product_categories = pageProps?.product_categories || {}
        const categoriesList = {}
        Object.keys(product_categories).forEach(key => {
            categoriesList[product_categories[key]['id']] = product_categories[key]['name']
        })

        const logout = () => {
            Inertia.post(route('logout'), {}, {
                onFinish: () => {
                    // window.location = '/login'
                },
            });
        }

        const toggleMobileMenu = () => {
            $('body').eq(0).toggleClass('mmenu-active');
        }

        const form = useForm({
            cat: pageProps?.rqProductCat || '',
            q: pageProps?.rqProductText || '',
        })
        const submitMainSearch = () => {
            form.get(route('shop.index'))
        }

        return {
            cart,
            cartHasItems,
            cartItems,
            cartItemsCount,
            cartItemsToDisplay,
            processing,
            removeItem,
            toggleMobileMenu,
            logout,
            form,
            categoriesList,
            submitMainSearch,
        }
    },
}
</script>
