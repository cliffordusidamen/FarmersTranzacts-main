<template>
    <div class="sticky-wrapper">
        <div class="header-bottom sticky-header">
            <div class="container">
                <div v-show="showCategories && $page.props.product_categories.length > 0" class="header-left">
                    <div class="dropdown category-dropdown show is-on" ::data-visible="$page.props.showMenu ? 'true' : 'false'">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-display="static" title="Browse Categories">
                            Browse Categories
                        </a>

                        <div :class="{
                            'dropdown-menu': true,
                            'show': $page.props.showMenu,
                        }">
                            <nav class="side-nav">
                                <ul class="menu-vertical sf-arrows">
                                    <li v-for="category in $page.props.product_categories" :key="category.id" :class="{'sf-with-ul': hasSubcategory(category) }">
                                        <inertia-link :href="route('shop.categorized', category.slug)">{{ category.name }}</inertia-link>

                                        <ul v-if="hasSubcategory(category)">
                                            <li v-for="subcategory in category.subcategories" :key="subcategory.id">
                                                <inertia-link
                                                    :href="route('shop.subcategorized', {
                                                        categorySlug: category.slug,
                                                        subcategorySlug: subcategory.slug,
                                                    })"
                                                >
                                                    {{ subcategory.name }}
                                                </inertia-link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div><!-- End. category-dropdown -->
                </div><!-- End. header-left -->

                <div class="header-center">
                    <nav class="main-nav">
                        <ul class="menu sf-arrows">
                            <li :class="{
                                'd-none d-xl-block': true,
                                'active': isCurrentRoute('home')
                            }">
                                <inertia-link :href="route('home')">Home</inertia-link>
                            </li>
                            <li :class="{
                                'active': isCurrentRoute(route('shop.index'))
                            }">
                                <inertia-link :href="route('shop.index')">Shop</inertia-link>
                            </li>
                            <li :class="{
                                'active': isCurrentRoute(route('about_us'))
                            }">
                                <inertia-link :href="route('about_us')">About Us</inertia-link>
                            </li>
                            <li :class="{
                                'active': isCurrentRoute(route('contact_us'))
                            }">
                                <inertia-link :href="route('contact_us')">Contact Us</inertia-link>
                            </li>
                        </ul>
                    </nav>
                </div><!-- End. header-center -->
                
                <div class="header-right">
                    <i class="la la-lightbulb-o"></i><p><span>HOTLINE: {{ hotline }}</span></p>
                </div><!-- End. header-right -->
            </div>
        </div>
    </div>
</template>

<script>
import useAppSettings from '@/composables/useAppSettings'
import { onMounted } from '@vue/runtime-core'

export default {
    props: {
        showCategories: {
            type: Boolean,
            default: true,
        },
    },

    setup() {
        const { hotline } = useAppSettings()

        onMounted(() => {
            // Sticky header 

            var catDropdown = $('.category-dropdown'),
                catInitVal = catDropdown.data('visible');
                
            if ( $('.sticky-header').length && $(window).width() >= 992 ) {
                var sticky = new Waypoint.Sticky({
                    element: $('.sticky-header')[0],
                    stuckClass: 'fixed',
                    offset: -300,
                    handler: function ( direction ) {
                        // Show category dropdown
                        if ( catInitVal &&  direction == 'up') {
                            catDropdown.addClass('show').find('.dropdown-menu').addClass('show');
                            catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'true');
                            return false;
                        }

                        // Hide category dropdown on fixed header
                        if ( catDropdown.hasClass('show') ) {
                            catDropdown.removeClass('show').find('.dropdown-menu').removeClass('show');
                            catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'false');
                        } 
                    }
                });
            }
        })

        const hasSubcategory = (category) => {
            return category.subcategories.length > 0;
        }

        const isCurrentRoute = (route) => {
            if (route == 'home') {
                return window.location.href == `${window.location.origin}/`
            }

            return window.location.href.startsWith(route)
        }

        return {
            hotline,
            hasSubcategory,
            isCurrentRoute,
        }
    },
}
</script>
