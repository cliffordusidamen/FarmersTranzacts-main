<template>
    <app-layout :show-categories="false">

        <div class="page-content">
            <div class="container">
                <div class="row d-flex mt-3 justify-content-center">

                    <!-- Side menu -->
                    <div class="d-none d-md-block col-md-3">
                        <h4>Categories</h4>
                        <div class="list-group list-group-flush">
                            <div
                                :class="{
                                    'list-group-item': true,
                                    'active': isCurrentCategory(category.slug) && !category?.subcategories?.length
                                }"
                                v-for="category in pageProps.product_categories"
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

                    <div class="col-md-9">
                        <products-list-breadcrumb :category="pageProps?.productCategory || {}" />
                        <div v-if="isCategorized()">
                            <h3>
                                {{
                                    !isSubcategorized()
                                    ? pageProps.productCategory.name
                                    : pageProps.subcategory.name
                                }}
                            </h3>
                        </div>

                        <div v-if="!productsAvailable()" class="border p-3 rounded-md lead">
                            {{
                                isSearch
                                    ? `No product found for your search "${rqProductText}"`
                                    : 'No product available at the moment!'
                            }}
                            
                        </div>

                        <div v-else>
                            <p class="text-muted my-2 lead" v-html="getShownPortion()"></p>

                            <div class="products mb-3">
                                <div class="row justify-content-start">
                                    <div v-for="product in pageProps.products.data" :key="product.id" class="col-11 col-sm-6 col-md-4 col-lg-4">
                                        <product-box :product="product" />

                                    </div>
                                </div>
                            </div><!-- End .products(all) -->

                            <pagination :paginator="pageProps.products" />
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
import ProductBox from '@/Components/ProductBox.vue'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    components: {
        AppLayout,
        ProductsListBreadcrumb,
        Pagination,
        ProductBox,
    },

    setup() {
        const pageProps = usePage().props.value
        const rqProductCat = pageProps?.rqProductCat || ''
        const rqProductText = pageProps?.rqProductText || ''
        const isSearch = pageProps?.isSearch || false
        const productsAvailable = () => {
            return pageProps.products.data.length > 0;
        }

        const isCategorized = () => {
            return pageProps?.productCategory?.id
        }

        const isSubcategorized = () => {
            return pageProps?.subcategory?.id
        }

        const isCurrentCategory = (slug) => {
            return isCategorized() && pageProps.productCategory.slug == slug
        }

        const isCurrentSubcategory = (slug) => {
            return isSubcategorized() && pageProps.subcategory.slug == slug
        }

        const getShownPortion = () => {
            const prevPage = pageProps.products.current_page - 1;
            const prevPageLastSerialNumber = (prevPage *  pageProps.products.per_page)
            const firstItemSerialNumber = prevPageLastSerialNumber + 1;
            const lastItemSerialNumber = prevPageLastSerialNumber + pageProps.products.data.length;

            return `
                Showing ${firstItemSerialNumber} - ${lastItemSerialNumber}
                of ${pageProps.products.total}
                ${!isSearch ? '' : `for <em class="font-weight-bold">"${rqProductText}"</em>`}
            `;
        }

        return {
            productsAvailable,
            isCategorized,
            isSubcategorized,
            isCurrentCategory,
            isCurrentSubcategory,
            getShownPortion,
            rqProductCat,
            rqProductText,
            isSearch,
            pageProps,
        }
    }
}
</script>
