<template>
    <app-layout>
        <page-header title="My Farmers" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <referral-request-form :app-states="app_states" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script setup>
import { usePage } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import ReferralRequestForm from '@/Components/Forms/ReferralRequestForm.vue'


const {app_states} = usePage().props.value
const breadcrumbLinks = [
    {
        text: "My Farmers",
        link: route('referrals.index'),
    },
    {
        text: 'New referral',
    },
];

console.log('app_states', app_states)
</script>
