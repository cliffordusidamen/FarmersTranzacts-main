<template>
    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" @click="toggleMobileMenu"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container mobile-menu-light">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close" @click="toggleMobileMenu"><i class="icon-close"></i></span>
            
            <form action="#" method="get" class="mobile-search">
                <label for="mobile-search" class="sr-only">Search</label>
                <input type="search" class="form-control" name="mobile-search" id="mobile-search" placeholder="Search in..." required>
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
                        <ul class="mobile-menu">
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
                        <ul class="mobile-cats-menu">
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
                <a href="#" class="social-icon" target="_blank" title="Facebook"><i class="icon-facebook-f"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Twitter"><i class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Instagram"><i class="icon-instagram"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Youtube"><i class="icon-youtube"></i></a>
            </div><!-- End .social-icons -->
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->
</template>

<script>
export default {
    mounted() {
        $('ul.mobile-menu li a').on('click', function(event) {
            if ($(this).parent('li').find('ul').length > 0) {
                event.preventDefault()
                $(this).parent('li').toggleClass('open');
                $(this).parent('li').find('ul').eq(0).toggleClass('d-block');
            }
        });
    },
    methods: {
        toggleMobileMenu() {
            $('body').eq(0).toggleClass('mmenu-active');
        }
    }
}
</script>