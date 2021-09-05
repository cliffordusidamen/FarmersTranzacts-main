<template>
    <app-layout>
        <page-header title="Contact Us" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
    },
    setup() {
        const breadcrumbLinks = [
            {
                text: "Shop",
                link: route("shop.index"),
            },
            {
                text: "Contact Us",
            },
        ];

        return {
            breadcrumbLinks,
        };
    },
};
</script>