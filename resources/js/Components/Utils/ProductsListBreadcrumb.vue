<template>
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><inertia-link href="/">Home</inertia-link></li>
                <li
                    :class="{
                        'breadcrumb-item': true,
                        'active': !category?.id
                    }"
                >
                    <inertia-link v-if="category?.id" :href="route('shop.index')">Shop</inertia-link>
                    <span v-else>Shop</span>
                </li>
                <li v-if="category?.id" class="breadcrumb-item active" aria-current="page">
                    {{category.name}}
                </li>
                <li v-if="isSearch" class="breadcrumb-item active" aria-current="page">
                    Search results
                </li>
            </ol>
        </div>
        <!-- End .container -->
    </nav>
</template>


<script>
import { usePage } from '@inertiajs/inertia-vue3'
export default {
    props: {
        category: {
            type: Object,
            default: () =>({}),
        },
    },

    setup() {
        const isSearch = usePage().props.value

        return {
            isSearch,
        }
    }
}
</script>