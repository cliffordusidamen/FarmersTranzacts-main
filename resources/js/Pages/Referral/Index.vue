<template>
    <app-layout>
        <page-header title="My Farmers" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div v-if="!referralRequests?.data?.length" class="error-content text-center bg-white">
                        <h2 class="error-title">No farmer registered yet!</h2>
                        <inertia-link :href="route('referrals.create')" class="btn btn-success btn-minwidth-lg">
                            <i class="la la-plus"></i>
                            <span>ADD FARMER NOW</span>
                        </inertia-link>
                    </div>

                    <div v-else>
                        <div class="d-flex flex-row justify-content-between mb-3">
                            <inertia-link :href="route('referrals.create')" class="btn btn-success md-mr-4">
                                <i class="la la-plus"></i>
                                <span>ADD FARMER</span>
                            </inertia-link>
                            <inertia-link :href="route('referrals.search')" class="btn btn-link text-success border-success flex-grow-1">
                                <i class="la la-search"></i>
                                <span>SEARCH ALL FARMERS</span>
                            </inertia-link>
                        </div>

                        <div class="card shadow">
                            <table class="table table-sm mb-0 table-bordered">
                                <thead>
                                    <tr class="d-none d-md-table-row">
                                        <th class="d-none text-uppercase d-md-table-cell">Farmer name</th>
                                        <th class="text-uppercase">State</th>
                                        <th class="d-none d-md-table-cell text-uppercase">Product</th>
                                        <th class="d-none d-md-table-cell text-uppercase">Last season</th>
                                        <th class="d-none text-uppercase d-md-table-cell">Approval</th>
                                        <th class="d-none text-uppercase d-md-table-cell text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="refRequest in referralRequests.data"
                                        :key="refRequest.id"
                                    >
                                        <td class="text-uppercase">
                                            <inertia-link
                                                :href="route('referrals.show', { id: refRequest.id })"
                                            >
                                                <strong>{{ `${refRequest.first_name} ${refRequest.last_name}` }}</strong>
                                                <span v-if="refRequest?.business_name" class="text-muted d-block">
                                                    {{ refRequest.business_name }}
                                                </span>
                                                <span
                                                    :class="[
                                                        'badge text-uppercase d-inline d-md-none',
                                                        getReferralApprovalCssClass(refRequest?.status_number ?? 0)
                                                    ]"
                                                >
                                                    {{ refRequest.approval_status }}
                                                </span>

                                                <span class="text-uppercase d-block d-md-none">
                                                    Last season:
                                                    <span v-if="!!refRequest?.last_season?.id" class="ml-2">
                                                        {{ `${refRequest.last_season.year}, ${refRequest.last_season.season} season` }}
                                                    </span>
                                                    <span v-else class="ml-2">
                                                        {{ refRequest.season_year }}, {{ refRequest.season }} season
                                                    </span>
                                                </span>
                                            </inertia-link>
                                        </td>

                                        <td class="text-center">{{ refRequest.state.state_name }}</td>

                                        <td class="d-none d-md-table-cell ">
                                            <span v-if="!!refRequest?.crops_harvest" class="d-block">
                                                <span class="text-muted">CROPS:</span>
                                                {{ refRequest.crops_harvest }}
                                            </span>

                                            <span v-if="!!refRequest?.animals_harvest" class="d-block">
                                                <span class="text-muted">ANIMALS:</span>
                                                {{ refRequest.animals_harvest }}
                                            </span>
                                        </td>

                                        <td class="d-none d-md-table-cell text-uppercase">
                                            <span v-if="!!refRequest?.last_season?.id" class="d-block">
                                                {{ `${refRequest.last_season.year}, ${refRequest.last_season.season} season` }}
                                            </span>
                                            <span v-else>
                                                {{ refRequest.season_year }}, {{ refRequest.season }} season
                                            </span>
                                        </td>

                                        <td class="d-none d-md-table-cell text-uppercase">
                                            <span
                                                :class="[
                                                    'badge text-uppercase',
                                                    getReferralApprovalCssClass(refRequest?.status_number ?? 0)
                                                ]"
                                            >
                                                {{ refRequest.approval_status }}
                                            </span>
                                        </td>

                                        <td class="">
                                            <span class="d-flex justify-content-end">
                                                <referral-actions :referral-request="refRequest" />
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="my-4">
                            <pagination :paginator="referralRequests" />
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
import { getReferralApprovalCssClass } from '../../util/functions'
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import ReferralActions from "@/Components/Controls/ReferralActions.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import Pagination from '@/Components/Pagination.vue'

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
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

        const { referralRequests } = usePage().props.value
        const { formatDate } = useDate()

        return {
            breadcrumbLinks,
            formatDate,
            getReferralApprovalCssClass,
            referralRequests,
        };
    },
};
</script>