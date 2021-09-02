<template>
    <app-layout :show-categories="false">

        <div class="page-content">
            <div class="container">
                <div class="row mt-3 justify-content-center">

                    <!-- Side menu -->
                    <div class="col-lg-3">
                        <h4>Categories</h4>
                        <div class="list-group list-group-flush">
                            <div
                                :class="{
                                    'list-group-item': true,
                                    'active': isCurrentCategory(category.slug) && !category?.subcategories?.length
                                }"
                                v-for="category in $page.props.product_categories"
                                :key="category.id"
                            >
                                <inertia-link
                                    v-if="(
                                        !isCurrentCategory(category.slug)
                                        && !category?.subcategories?.length
                                    )"
                                    :href="route('shop.categorized', category.slug)"
                                    :class="{
                                        'd-block': true,
                                        'text-white': isCurrentCategory(category.slug)
                                    }"
                                >
                                    {{ category.name }}
                                </inertia-link>
                                <div
                                    v-else-if="category?.subcategories?.length"
                                >
                                    <a
                                        :href="`#category_menu_${category.id}`"
                                        class="with-caret d-block d-flex flex-row-reverse align-items-center justify-content-between"
                                        :class="{
                                            'collapsed': !isCurrentCategory(category.slug)
                                        }"
                                        :aria-expanded="isCurrentCategory(category.slug) ? 'true' : 'false'"
                                        data-toggle="collapse"
                                        :data-target="`#category_menu_${category.id}`"
                                    >
                                        {{ category.name }}
                                    </a>
                                </div>
                                <span v-else>{{ category.name }}</span>
                                <div
                                    v-if="category?.subcategories?.length"
                                    :id="`category_menu_${category.id}`"
                                    class="collapse mx-2 border mt-1"
                                    :class="{
                                        'show': isCurrentCategory(category.slug)
                                    }"
                                >
                                    <div
                                        class="list-group list-group-flush"
                                    >
                                        <inertia-link
                                            v-if="category.subcategories.length > 1"
                                            :href="route('shop.categorized', category.slug)"
                                            :class="{
                                                'list-group-item': true,
                                                'active': isCurrentCategory(category.slug) && !isSubcategorized(),
                                            }"
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
                                            :class="{
                                                'list-group-item': true,
                                                'active': isCurrentSubcategory(subcategory.slug)
                                            }"
                                        >
                                            {{subcategory.name}}
                                        </inertia-link>
                                    </div>
                                </div>
                                <!-- END MENU -->
                            </div>
                        </div>
                    </div>
                    <!-- [END] side menu -->

                    <div class="col-lg-9">
                        <products-list-breadcrumb :category="$page.props?.productCategory || {}" />
                        <div v-if="isCategorized()">
                            <h3>
                                {{
                                    !isSubcategorized()
                                    ? $page.props.productCategory.name
                                    : $page.props.subcategory.name
                                }}
                            </h3>
                        </div>

                        <div v-if="!productsAvailable()" class="border p-3 rounded-md lead">
                            No product available at the moment!
                        </div>

                        <div v-else>
                            <p class="text-muted my-2 lead">
                                {{ getShownPortion() }}
                            </p>

                            <div class="products mb-3">
                                <div class="row justify-content-center">
                                    <div v-for="product in $page.props.products.data" :key="product.id" class="col-11 col-sm-6 col-md-4 col-lg-4">
                                        <div class="product product-7 text-center mb-3 shadow">
                                            <figure class="product-media bg-light">
                                                <span v-if="!Number(product.in_stock)" class="product-label label-out">Out of Stock</span>
                                                <a href="#">
                                                    <img src="/img/default_product.png" alt="Product image" class="product-image">
                                                </a>

                                                <div class="product-action-vertical">
                                                    <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                                                    <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                                                    <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                                                </div><!-- End .product-action-vertical -->

                                                <div class="product-action">
                                                    <cart-update-button :product="product" />
                                                </div><!-- End .product-action -->
                                            </figure><!-- End .product-media -->

                                            <div class="product-body">
                                                <div class="product-cat">
                                                    <a href="#">{{ product.category.name }}</a>
                                                </div><!-- End .product-cat -->
                                                <h3 class="product-title"><a href="#">{{ product.name }}</a></h3><!-- End .product-title -->
                                                <div class="product-price">
                                                    &#8358;{{ Number(product.price).toLocaleString() }}
                                                </div><!-- End .product-price -->

                                                <div class="mt-n4 product-packaging">
                                                    per {{product.packaging.name}}
                                                </div>

                                                <div v-if="product.images > 0" class="product-nav product-nav-thumbs">
                                                    <a href="#" class="active">
                                                        <img src="assets/images/products/product-4-thumb.jpg" alt="product desc">
                                                    </a>
                                                    <a href="#">
                                                        <img src="assets/images/products/product-4-2-thumb.jpg" alt="product desc">
                                                    </a>

                                                    <a href="#">
                                                        <img src="assets/images/products/product-4-3-thumb.jpg" alt="product desc">
                                                    </a>
                                                </div><!-- End .product-nav -->
                                            </div><!-- End .product-body -->
                                        </div><!-- End .product -->

                                    </div>
                                </div>
                            </div><!-- End .products(all) -->

                            <pagination :paginator="$page.props.products" />
                        </div>

                    </div><!-- End .col-lg-9 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import ProductsListBreadcrumb from '@/Components/Utils/ProductsListBreadcrumb.vue'
import Pagination from '@/Components/Pagination.vue'
import CartUpdateButton from '@/Components/Controls/CartUpdateButton.vue'

export default {
    components: {
        AppLayout,
        ProductsListBreadcrumb,
        Pagination,
        CartUpdateButton,
    },

    methods: {
        productsAvailable() {
            return this.$page.props.products.data.length > 0;
        },

        isCategorized() {
            return this.$page.props?.productCategory?.id
        },

        isSubcategorized() {
            return this.$page.props?.subcategory?.id
        },

        isCurrentCategory(slug) {
            return this.isCategorized() && this.$page.props.productCategory.slug == slug
        },

        isCurrentSubcategory(slug) {
            return this.isSubcategorized() && this.$page.props.subcategory.slug == slug
        },

        getShownPortion() {
            const prevPage = this.$page.props.products.current_page - 1;
            const prevPageLastSerialNumber = (prevPage *  this.$page.props.products.per_page)
            const firstItemSerialNumber = prevPageLastSerialNumber + 1;
            const lastItemSerialNumber = prevPageLastSerialNumber + this.$page.props.products.data.length;

            return `Showing ${firstItemSerialNumber} - ${lastItemSerialNumber} of ${this.$page.props.products.total} `;
        },
    }
}
</script>
