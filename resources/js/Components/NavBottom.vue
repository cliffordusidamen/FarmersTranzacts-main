<template>
    <div class="sticky-wrapper">
        <div class="header-bottom sticky-header">
            <div class="container">
                <div v-show="showCategories" class="header-left">
                    <div class="dropdown category-dropdown show is-on" data-visible="true">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-display="static" title="Browse Categories">
                            Browse Categories
                        </a>

                        <div class="dropdown-menu show">
                            <nav class="side-nav">
                                <ul class="menu-vertical sf-arrows">
                                    <li><a href="#">Home Appliances</a></li>
                                    <li><a href="#">Healthy & Beauty</a></li>
                                    <li><a href="#">Shoes & Boots</a></li>
                                    <li><a href="#">Travel & Outdoor</a></li>
                                    <li><a href="#">Smart Phones</a></li>
                                    <li><a href="#">TV & Audio</a></li>
                                    <li><a href="#">Gift Ideas</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div><!-- End. category-dropdown -->
                </div><!-- End. header-left -->

                <div class="header-center">
                    <nav class="main-nav">
                        <ul class="menu sf-arrows">
                            <li class="d-none d-xl-block"><inertia-link :href="route('home')">Home</inertia-link></li>
                            <li><a href="#">Shop</a></li>
                            <li><a href="#">Distributors</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li v-if="is_super_admin">
                                <a href="#" class="sf-with-ul">Admin</a>
                                <ul>
                                    <li>
                                        <a href="#" class="sf-with-ul">Product Categories</a>

                                        <ul>
                                            <li><inertia-link :href="route('categories.create')">Add</inertia-link></li>
                                            <li><inertia-link :href="route('categories.index')">List</inertia-link></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="sf-with-ul">Users</a>

                                        <ul>
                                            <li><a href="#">Add</a></li>
                                            <li><a href="#">List</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Sample Link 1</a></li>
                                    <li><a href="#">Sample Link 1</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div><!-- End. header-center -->
                
                <div class="header-right">
                    <i class="la la-lightbulb-o"></i><p><span>HOTLINE: +0123 456 789</span></p>
                </div><!-- End. header-right -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showCategories: {
            type: Boolean,
            default: true,
        }
    },

    computed: {
        is_super_admin() {
            return this.$page.props.user && this.$page.props.user.is_super_admin;
        }
    }
}
</script>
