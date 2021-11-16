<template>
    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" @click="toggleMobileMenu"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container mobile-menu-light">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close" @click="toggleMobileMenu"><i class="icon-close"></i></span>
            
            <form @submit.prevent="submitMobileSearch" class="mobile-search">
                <label for="mobile-search" class="sr-only">Search</label>
                <input type="search" class="form-control" name="mobile-search" id="mobile-search" v-model="form.q" placeholder="Search in..." />
                <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
            </form>

            <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab" role="tab" aria-controls="mobile-menu-tab" aria-selected="true">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab" role="tab" aria-controls="mobile-cats-tab" aria-selected="false">Categories</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel" aria-labelledby="mobile-menu-link">
                    <nav class="mobile-nav">
                        <ul class="mobile-menu"  style="font-weight: 500;">
                            <li class="active">
                                <inertia-link @click="toggleMobileMenu" :href="route('home')">Home</inertia-link>
                            </li>
                            <li>
                                <inertia-link @click="toggleMobileMenu" :href="route('shop.index')">Shop</inertia-link>
                            </li>
                            <li>
                                <inertia-link @click="toggleMobileMenu" :href="route('about_us')">About Us</inertia-link>
                            </li>
                            <li>
                                <inertia-link @click="toggleMobileMenu" :href="route('contact_us')">Contact Us</inertia-link>
                            </li>
                        </ul>
                    </nav><!-- End .mobile-nav -->
                </div><!-- .End .tab-pane -->
                <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">
                    <nav class="mobile-cats-nav">
                        <ul class="mobile-cats-menu list-unstyled" style="font-weight: 500;">
                            <li
                                v-for="category in $page.props.product_categories"
                                :key="category.id"
                            >
                                <inertia-link
                                    v-if="!category?.subcategories?.length"
                                    :href="route('shop.categorized', category.slug)"
                                    @click="toggleMobileMenu"
                                >
                                    {{ category.name }}
                                </inertia-link>
                                <span v-else>
                                    <a
                                        :href="`#category_nav_drawer_${category.id}`"
                                        class="with-caret collapsed d-block d-flex flex-row-reverse align-items-center justify-content-between"
                                        :aria-expanded="false"
                                        data-toggle="collapse"
                                        :data-target="`#category_nav_drawer_${category.id}`"
                                    >
                                        {{ category.name }}
                                    </a>
                                </span>
                                <div
                                    v-if="category?.subcategories?.length"
                                    :id="`category_nav_drawer_${category.id}`"
                                    class="collapse mx-4 mb-2 shadow-sm border mt-1"
                                >
                                    <div
                                        class="list-group list-group-flush"
                                    >
                                        <inertia-link
                                            v-if="category.subcategories.length > 1"
                                            :href="route('shop.categorized', category.slug)"
                                            class="list-group-item"
                                            @click="toggleMobileMenu"
                                        >
                                            All
                                        </inertia-link>

                                        <inertia-link
                                            :href="route('shop.subcategorized', {
                                                categorySlug: category.slug,
                                                subcategorySlug: subcategory.slug
                                            })"
                                            v-for="subcategory in category.subcategories"
                                            :key="subcategory.id"
                                            class="list-group-item"
                                            @click="toggleMobileMenu"
                                        >
                                            {{subcategory.name}}
                                        </inertia-link>
                                    </div>
                                </div>
                                <!-- END MENU -->
                            </li>
                        </ul><!-- End .mobile-cats-menu -->
                    </nav><!-- End .mobile-cats-nav -->
                </div><!-- .End .tab-pane -->
            </div><!-- End .tab-content -->

            <div class="social-icons">
                <a
                    v-if="social_facebook"
                    :href="`https://facebook.com/${social_facebook}`"
                    class="social-icon"
                    title="Connect with us on Facebook"
                    target="_blank"
                >
                    <i class="icon-facebook-f"></i>
                </a>

                <a
                    v-if="social_twitter"
                    :href="`https://twitter.com/@${social_twitter}`"
                    class="social-icon"
                    title="Follow us on Twitter"
                    target="_blank"
                >
                    <i class="icon-twitter"></i>
                </a>

                <a
                    v-if="social_instagram"
                    :href="`https://instagram.com/@${social_instagram}`"
                    class="social-icon"
                    title="Follow us on Instagram"
                    target="_blank"
                >
                    <i class="icon-instagram"></i>
                </a>

                <a
                    v-if="social_youtube"
                    :href="`https://youtube.com/@${social_youtube}`"
                    class="social-icon"
                    title="Subscribe to our Youtube channel"
                    target="_blank"
                >
                    <i class="icon-youtube"></i>
                </a>
            </div><!-- End .social-icons -->
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->
</template>

<script>
import { onMounted } from '@vue/runtime-core';
import { useForm, usePage } from '@inertiajs/inertia-vue3';

export default {
    setup() {

        onMounted(() => {
            $('ul.mobile-menu li a').on('click', function(event) {
                if ($(this).parent('li').find('ul').length > 0) {
                    event.preventDefault()
                    $(this).parent('li').toggleClass('open');
                    $(this).parent('li').find('ul').eq(0).toggleClass('d-block');
                }
            });
        })
        const toggleMobileMenu = () => {
            $('body').eq(0).toggleClass('mmenu-active');
        }

        const pageProps = usePage().props.value

        const form = useForm({
            q: pageProps?.rqProductText || '',
        })
    
        const submitMobileSearch = () => {
            toggleMobileMenu()
            form.get(route('shop.index'))
        }

        return {
            form,
            submitMobileSearch,
            toggleMobileMenu,
        }
    },
}
</script>