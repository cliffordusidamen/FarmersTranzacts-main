<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import ReferralActions from "@/Components/Controls/ReferralActions.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import useDate from '@/composables/useDate'

const {referralRequest} = usePage().props.value
const breadcrumbLinks = [
    {
        text: "My Farmers",
        link: route('referrals.index'),
    },

    {
        text: referralRequest.full_name,
    },
]

const getStatusClass = status_number => {
    switch (status_number) {
        case 0:
            return 'bg-light'
        case 1:
            return 'bg-success'
        case -1:
            return 'bg-danger'
        default:
            return ''
    }
}

</script>

<template>
    <app-layout>
        <page-header title="My Farmers" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="d-flex flex-col flex-md-row justify-content-md-between">

                            <h4 class="text-uppercase">
                                status:
                                <span :class="['badge rounded ml-2', getStatusClass(referralRequest.status_number)]">
                                    {{ referralRequest.approval_status }}
                                </span>
                            </h4>

                            <div
                                v-if="!!referralRequest?.is_editable"
                                class="d-flex justify-content-end"
                            >
                                <referral-actions :referral-request="referralRequest" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">Name:</strong>
                                <span>{{ referralRequest.full_name }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">phone:</strong>
                                <span>{{ referralRequest.phone }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">email:</strong>
                                <span>{{ referralRequest.email ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">business name:</strong>
                                <span>{{ referralRequest.business_name ?? 'N/A' }}</span>
                            </div>

                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">farm size:</strong>
                                <span>{{ referralRequest.farm_size ?? 'N/A' }}</span>
                            </div>
                            

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">number of workers:</strong>
                                <span>{{ referralRequest.number_of_workers ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">crops grown:</strong>
                                <span>{{ referralRequest.crops_grown ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">animals reared:</strong>
                                <span>{{ referralRequest.animals_reared ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">seed demand:</strong>
                                <span>{{ referralRequest.seed_demand ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">animal feed demand:</strong>
                                <span>{{ referralRequest.animal_feed_demand ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">fertilizer demand:</strong>
                                <span>{{ referralRequest.fertilizer_demand ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">agro_chemical demand:</strong>
                                <span>{{ referralRequest.agro_chemical_demand ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">address:</strong>
                                <span>{{ referralRequest.address_line_1 }},</span>
                                <span
                                    v-if="referralRequest.address_line_2"
                                    class="d-block"
                                >
                                    {{ referralRequest.address_line_2 }},
                                </span>
                                <span
                                    v-if="referralRequest.city"
                                    class="d-block"
                                >
                                    {{ referralRequest.city }},
                                </span>
                                <span
                                    v-if="referralRequest?.state?.id"
                                    class="d-block"
                                >
                                    {{ referralRequest?.state?.name }}
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>