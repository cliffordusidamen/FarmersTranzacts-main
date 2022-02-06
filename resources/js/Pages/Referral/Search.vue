<template>
    <app-layout>
        <page-header title="All farmers" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    
                    <div class="mb-3">
                        <inertia-link :href="route('referrals.index')" class="btn btn-success btn-minwidth-lg">
                            <i class="la la-arrow-left mr-1"></i>
                            <span>MY FARMERS</span>
                        </inertia-link>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <farmer-search-form />
                        </div>
                    </div>


                    <div v-if="!isSearch" class="text-center bg-white">
                        <h4 class="error-title">Enter name and/or select a state to search!</h4>
                    </div>

                    <div v-else-if="!referrals?.data?.length" class="text-center bg-white">
                        <h2 class="error-title">No farmer found!</h2>
                    </div>

                    <div v-else>

                        <div class="card shadow">
                            <table class="table table-sm mb-0 table-bordered">
                                <thead>
                                    <tr class="">
                                        <th width="40%" class="text-uppercase">Farmer</th>
                                        <th class="text-uppercase">State</th>
                                        <th class="text-uppercase">date registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="refRequest in referrals.data"
                                        :key="refRequest.id"
                                    >
                                        <td class="text-uppercase">
                                            <strong>{{ `${refRequest.first_name} ${refRequest.last_name}` }}</strong>
                                            <span v-if="refRequest?.business_name" class="text-muted d-block">
                                                {{ refRequest.business_name }}
                                            </span>
                                        </td>

                                        <td class="text-center">{{ refRequest.state.state_name }}</td>

                                        <td class="text-uppercase">{{ formatDate(refRequest.created_at, 'DD/MM/YYYY')  }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-4">
                            <pagination :paginator="referrals" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import { usePage } from '@inertiajs/inertia-vue3';

import useDate from '@/composables/useDate'
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import ReferralActions from "@/Components/Controls/ReferralActions.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import Pagination from '@/Components/Pagination.vue'
import FarmerSearchForm from '@/Components/Forms/FarmerSearchForm.vue'

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        FarmerSearchForm,
        PageHeader,
        Pagination,
        ReferralActions,
    },
    setup() {
        const breadcrumbLinks = [
            {
                text: "My Orders",
            },
        ];

        const { referrals, isSearch } = usePage().props.value
        const { formatDate } = useDate()

        return {
            breadcrumbLinks,
            referrals,
            formatDate,
            isSearch,
        };
    },
};
</script>