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
                        <div
                            v-if="helpPage?.faqs?.length > 0"
                            class="col-md-4"
                        >
                            <h4>Related questions</h4>
                            <faq-accordion :faqs="helpPage.faqs" />
                        </div>
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
import { usePage } from '@inertiajs/inertia-vue3';

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import FaqAccordion from '@/Components/FaqAccordion.vue'

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        FaqAccordion,
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