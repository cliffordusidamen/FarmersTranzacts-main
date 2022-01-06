<template>
    <app-layout>
        <page-header :title="'How can we help you?'" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div
                            v-for="page in helpPages"
                            :key="page.id"
                            class="col-md-6 col-lg-4"
                        >
                            <inertia-link
                                :href="route('help.page', {slug: page.slug})"
                                class="text-decoration-none d-block mx-2 mb-4"
                            >
                                <div class="card border border-success help-page-card">
                                    <div class="card-body text-center">
                                        <span class="h5 text-success d-block border-bottom border-success pb-1 mb-0">{{ page.title }}</span>
                                        <span class="text-dark" v-if="page?.description">{{page.description}}</span>
                                    </div>
                                </div>
                            </inertia-link>
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
        const breadcrumbLinks = [
            {
                text: 'Help center',
            },
        ];

        const { helpPages } = usePage().props.value


        return {
            breadcrumbLinks,
            helpPages,
        };
    },
};
</script>