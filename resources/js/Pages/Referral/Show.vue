<script setup>
import { usePage } from '@inertiajs/inertia-vue3';
import { ref } from '@vue/reactivity';

import { getReferralApprovalCssClass } from '../../util/functions'
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import ReferralActions from "@/Components/Controls/ReferralActions.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import DefaultModal from "@/Components/DefaultModal";
import { computed } from '@vue/runtime-core';

const { referralRequest, seasons } = usePage().props.value
const breadcrumbLinks = [
    {
        text: "My Farmers",
        link: route('referrals.index'),
    },

    {
        text: referralRequest.full_name,
    },
]

const displaySeason = ref(null)
const displaySeasonTitle = computed(() => {
    if (displaySeason.value) {
        return `${displaySeason.value?.year}, ${displaySeason.value?.season.toUpperCase()} SEASON`;
    }
    return ''
})
const seasonDetailModelEventName = 'showSeasonDetailsModal';
const showSeasonDetails = (season) => {
    displaySeason.value = season
    const seasonDetailModalEvent = new CustomEvent(seasonDetailModelEventName);
    document.dispatchEvent(seasonDetailModalEvent);
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
                                <span
                                    :class="[
                                        'badge rounded ml-2',
                                        getReferralApprovalCssClass(referralRequest.status_number),
                                    ]"
                                >
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
                                <strong class="d-block text-uppercase">business name:</strong>
                                <span>{{ referralRequest.business_name ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">phone:</strong>
                                <span>{{ referralRequest.phone }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">Age:</strong>
                                <span>{{ referralRequest.age }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">email:</strong>
                                <span>{{ referralRequest.email ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">Local Govt. Area:</strong>
                                <span>{{ referralRequest?.local_government_area?.local_govt_name ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-4 border-bottom mb-3">
                                <strong class="d-block text-uppercase">Ward:</strong>
                                <span>{{ referralRequest?.ward ?? 'N/A' }}</span>
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


                        <div v-if="!!referralRequest.farm_id">
                            <h4 class="text-uppercase">season records</h4>

                            <div class="card shadow table-responsive">
                                <table class="table table-hover table-sm m-0 p-0">
                                    <thead>
                                        <tr class="small bg-light text-uppercase">
                                            <th>Year</th>
                                            <th>Season</th>
                                            <th>Farm produce</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="season in seasons"
                                            :key="season.id"
                                            class="cursor-pointer"
                                            title="click for details"
                                            @click="() => showSeasonDetails(season)"
                                        >
                                            <td>{{ season.year }}</td>
                                            <td class="text-uppercase">{{ season.season }}</td>
                                            <td>
                                                {{
                                                    season.season_produces.map(
                                                        (season_produce) => season_produce.name
                                                    ).join(', ')
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <default-modal
                    :id="'seasonDetailsModal'"
                    :show-event="seasonDetailModelEventName"
                    :title="displaySeasonTitle"
                >
                    <div class="p-3">
                        <h5 class="text-uppercase px-4 mb-2">season input/expense</h5>
                        <div
                            v-if="!!displaySeason?.season_inputs.length"
                            class="card mb-1"
                        >
                            <div class="table-responsive">
                                <table class="table table-hover table-sm m-0 p-0">
                                    <thead>
                                        <tr class="small bg-light text-uppercase">
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th class="text-center">Cost per Unit<br>(&#8358;)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="season_input in displaySeason.season_inputs"
                                            :key="season_input.id"
                                        >
                                            <td>{{ season_input.name }}</td>
                                            <td>{{ Number(season_input.quantity).toLocaleString() }}</td>
                                            <td class="text-uppercase">{{ season_input.unit }}</td>
                                            <td class="text-center">{{ Number(season_input.cost_per_unit).toLocaleString() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h5 class="text-uppercase px-4 mt-4 mb-2">season produce</h5>
                        <div
                            v-if="!!displaySeason?.season_produces.length"
                            class="card"
                        >
                            <div class="table-responsive">
                                <table class="table table-hover table-sm m-0 p-0">
                                    <thead>
                                        <tr class="small bg-light text-uppercase">
                                            <th>Item</th>
                                            <th>Unit</th>
                                            <th>Quantity produces</th>
                                            <th>Quantity sold</th>
                                            <th class="text-right">Price(&#8358;)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="season_produce in displaySeason.season_produces"
                                            :key="season_produce.id"
                                        >
                                            <td>{{ season_produce.name }}</td>
                                            <td class="text-uppercase">{{ season_produce.unit }}</td>
                                            <td>{{ Number(season_produce.quantity_produced).toLocaleString() }}</td>
                                            <td>{{ Number(season_produce.quantity_sold).toLocaleString() }}</td>
                                            <td class="text-right">{{ Number(season_produce.price_per_unit).toLocaleString() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </default-modal>
            </div>
        </div>
    </app-layout>
</template>