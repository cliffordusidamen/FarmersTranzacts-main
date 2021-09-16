<template>
    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler" @click="toggleMobileMenu">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>
                
                <a href="/" class="logo">
                    LOGO
                </a>
            </div><!-- End .header-left -->

            <div class="header-center">
                <div class="header-search header-search-extended header-search-visible header-search-no-radius d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form action="#" method="get">
                        <div class="header-search-wrapper search-wrapper-wide">
                            <div class="select-custom d-none">
                                <select id="cat" name="cat">
                                    <option value="">All Departments</option>
                                    <option value="1">Fashion</option>
                                    <option value="2">- Women</option>
                                    <option value="3">- Men</option>
                                    <option value="4">- Jewellery</option>
                                    <option value="5">- Kids Fashion</option>
                                    <option value="6">Electronics</option>
                                    <option value="7">- Smart TVs</option>
                                    <option value="8">- Cameras</option>
                                    <option value="9">- Games</option>
                                    <option value="10">Home &amp; Garden</option>
                                    <option value="11">Motors</option>
                                    <option value="12">- Cars and Trucks</option>
                                    <option value="15">- Boats</option>
                                    <option value="16">- Auto Tools &amp; Supplies</option>
                                </select>
                            </div><!-- End .select-custom -->
                            <label for="q" class="sr-only">Search</label>
                            <input type="search" class="form-control" name="q" id="q" placeholder="Search product ..." required>
                            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
                        </div><!-- End .header-search-wrapper -->
                    </form>
                </div><!-- End .header-search -->
            </div><!-- End .header-center -->

            <div class="header-right">
                <div class="header-dropdown-link">
                    <div class="dropdown compare-dropdown d-none">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static" title="Compare Products" aria-label="Compare Products">
                            <i class="icon-random"></i>
                            <span class="compare-txt">Compare</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="compare-products">
                                <li class="compare-product">
                                    <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                    <h4 class="compare-product-title"><a href="#">Blue Night Dress</a></h4>
                                </li>
                                <li class="compare-product">
                                    <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                    <h4 class="compare-product-title"><a href="#">White Long Skirt</a></h4>
                                </li>
                            </ul>

                            <div class="compare-actions">
                                <a href="#" class="action-link">Clear All</a>
                                <a href="#" class="btn btn-outline-primary-2"><span>Compare</span><i class="icon-long-arrow-right"></i></a>
                            </div>
                        </div><!-- End .dropdown-menu -->
                    </div><!-- End .compare-dropdown -->

                    <a href="#" class="wishlist-link d-none">
                        <i class="icon-heart-o"></i>
                        <span class="wishlist-count">0</span>
                        <span class="wishlist-txt">Wishlist</span>
                    </a>

                    <div class="dropdown cart-dropdown">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                            <i class="icon-shopping-cart"></i>
                            <span v-show="cartHasItems" class="cart-count">{{ cartItemsCount > 10 ? '*' : cartItemsCount }}</span>
                            <span class="cart-txt">Cart</span>
                        </a>

                        <div v-if="cartHasItems" class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-cart-products">
                                <div class="product" v-for="{product, quantity} in cartItems" :key="product.id">
                                    <div class="product-cart-details">
                                        <h4 class="product-title">
                                            <inertia-link href="#">{{product.name}}</inertia-link>
                                        </h4>

                                        <span class="cart-product-info">
                                            <span class="cart-product-qty">{{Number(quantity).toLocaleString()}}</span>
                                            x &#8358;{{Number(product.price).toLocaleString()}}
                                        </span>
                                    </div><!-- End .product-cart-details -->

                                    <figure class="product-image-container">
                                        <a href="#" class="product-image">
                                            <img src="assets/images/products/cart/product-1.jpg" alt="product">
                                        </a>
                                    </figure>
                                    <a href="#" class="btn-remove" @click.prevent="removeItem(product)" title="Remove Product"><i class="icon-close"></i></a>
                                </div><!-- End .product -->
                            </div><!-- End .cart-product -->

                            <div class="dropdown-cart-total">
                                <span>Total</span>

                                <span class="cart-total-price">&#8358;{{cart?.grand_total.toLocaleString()}}</span>
                            </div><!-- End .dropdown-cart-total -->

                            <div v-show="!processing" class="dropdown-cart-action">
                                <inertia-link :href="route('shopping_cart.view')" class="btn btn-primary">View Cart</inertia-link>
                                <inertia-link :href="route('shopping_cart.checkout')" class="btn btn-outline-primary-2"><span>Checkout</span><i class="icon-long-arrow-right"></i></inertia-link>
                            </div><!-- End .dropdown-cart-total -->
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
                            <inertia-link v-if="isAuthenticated"  :href="route('profile.show')" class="dropdown-item">Profile</inertia-link>
                            <inertia-link v-if="isAuthenticated"  :href="route('my_orders.index')" class="dropdown-item">My Orders</inertia-link>
                            <inertia-link v-if="this.$page.props.userIsVendor"  :href="route('profile.edit_vendor_details')" class="dropdown-item">Vendor Details</inertia-link>
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
import { usePage } from '@inertiajs/inertia-vue3';
import useCartManager from '@/composables/useCartManager'

export default {

    mixins: [utility],

    setup() {
        const {
            cart,
            cartItems,
            cartItemsCount,
            cartHasItems,
            processing,
            removeItem,
        } =  useCartManager()

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

        return {
            cart,
            cartItems,
            cartItemsCount,
            cartHasItems,
            processing,
            removeItem,
            toggleMobileMenu,
            logout,
        }
    },
}
</script>
