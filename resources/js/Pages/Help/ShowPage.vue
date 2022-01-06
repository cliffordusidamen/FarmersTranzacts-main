<template>
    <app-layout>
        <page-header :title="helpPage.title" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div
                            class="col-md-8"
                            v-html="helpPage.content"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<style scoped>
.help-page-card:hover {
    border-color: blue;
}

@media screen and (min-width: 768px) {
    .help-page-card {
        height: 12rem;
    }
}
</style>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
    },
    setup() {

        const { helpPage } = usePage().props.value
        const breadcrumbLinks = [
            {
                link: route('help.index'),
                text: 'Help center',
            },
            {
                text: helpPage.title,
            },
        ];


        return {
            breadcrumbLinks,
            helpPage,
        };
    },
};
</script>