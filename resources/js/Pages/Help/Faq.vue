<template>
    <app-layout>
        <page-header title="Frequently Asked Questions" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="form-group">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Find questions"
                                    v-model="filterText"
                                    @keyup="handleFilterInput()"
                                />
                            </div>

                            <div v-if="!filteredFaqs?.length" class="card">
                                <div class="card body text-center lead">No FAQ found!</div>
                            </div>

                            <div
                                v-else-if="isFiltering"
                                class="d-flex flex-row justify-content-center mb-4 mt-1"
                            >
                                <div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <faq-accordion v-else :faqs="filteredFaqs" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import { onUnmounted, ref } from '@vue/runtime-core';
import { usePage } from '@inertiajs/inertia-vue3';

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import FaqAccordion from '@/Components/FaqAccordion.vue'
import PageHeader from "@/Components/Utils/PageHeader.vue";

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        FaqAccordion,
    },
    setup() {

        const { faqs } = usePage().props.value
        const breadcrumbLinks = [
            {
                link: route('help.index'),
                text: 'Help center',
            },
            {
                text: 'FAQ',
            },
        ];
        const filterText = ref('')
        const filteredFaqs = ref(faqs)
        const isFiltering = ref(false)

        const debounceTimer = ref(null)

        const filterFaqs = () => {
            console.log(`Fitlering using ${filterText.value}`)

            const newFaqs = faqs.filter(faq => {
                const question = faq.question.toLowerCase()
                const answer = faq.answer.toLowerCase()
                return question.includes(filterText.value) || answer.includes(filterText.value)
            })

            filteredFaqs.value = newFaqs
            isFiltering.value = false
        }

        const handleFilterInput = () => {
            isFiltering.value = true
            if (debounceTimer.value) {
                clearTimeout(debounceTimer.value)
            }

            debounceTimer.value = setTimeout(() => filterFaqs(), 1800)
        }



        onUnmounted(() => clearTimeout(debounceTimer))


        return {
            breadcrumbLinks,
            filteredFaqs,
            filterText,
            handleFilterInput,
            isFiltering,
        };
    },
};
</script>