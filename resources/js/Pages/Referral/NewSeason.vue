<template>
    <app-layout>
        <page-header title="My Farmers" sub-title="New season record" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <farm-season-form
                        :referral-request="referralRequest"
                        :years="years"
                        :seasons="seasons"
                    />
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
import FarmSeasonForm from '@/Components/Forms/FarmSeasonForm.vue'


const {referralRequest, years, seasons} = usePage().props.value
const farmName = referralRequest?.business_name || referralRequest.full_name
const breadcrumbLinks = [
    {
        text: "My Farmers",
        link: route('referrals.index'),
    },
    {
        text: farmName.substr(0, 20)
            + (farmName.length > 20 ? '...' : ''),
        link: route('referrals.show', {id: referralRequest.id}),
    },
    {
        text: 'New season record',
    },
];

</script>
