<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div v-if="!this.$page.props.userAgent.data.is_approved" class="row justify-content-center">
                    <div class="col-md-6">
                        <div
                            v-if="this.$page.props.userAgent.data.is_disapproved"
                            class="my-3 alert-2 alert-2-danger">
                            <h4 class="alert-title">
                                <icon-alert-circle classes="alert-icon" />
                                Agent registration was disapproved
                            </h4>
                            <div v-if="this.$page.props.userAgent.data.reject_reason">
                                <strong class="mr-2">Reason:</strong>
                                {{ this.$page.props.userAgent.data.reject_reason }}
                            </div>
                            <inertia-link
                                :href="route('profile.edit_agent_disapproval')"
                                class="btn btn-primary btn-link"
                            >
                                EDIT &amp; RE-SUBMIT
                            </inertia-link>
                        </div>
                        <div
                            v-else
                            class="my-3 alert-2 alert-2-default">
                            <h4 class="alert-title">
                                <icon-alert-circle classes="alert-icon" />
                                Agent registration awaiting approval
                            </h4>
                            Your registration as agent is currently being reviewed. You can still shop and purchase items.
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="row justify-content-center">
                        <div class="col-11">                            
                            <!-- Sales trend chart -->
                            <div>
                                <div class="d-block">
                                    <div class="row justify-content-between mb-2">
                                        <div class="col-md-6 col-lg-3 text-center text-md-left my-3">
                                            <div class="text-xl">
                                                <h4 class="text-dark font-weight-bolder">
                                                    Purchases
                                                    <small class="d-block text-success">
                                                        Last {{ Object.keys(spendingTrend).length }} months
                                                    </small>
                                                </h4>
                                            </div>
                                        </div>
                                        <div v-if="Number(currMonthSpending.month_total_amount)" class="col-md-6 col-lg-3 text-center text-md-left my-3">
                                            <div>
                                                <div class="display-4 text-dark">
                                                    &#8358;{{ shortenNumber(Number(currMonthSpending.month_total_amount)) }}
                                                    <span v-if="spendingDifference > 0" class="icon-arrow-up text-success"></span>
                                                    <span v-if="spendingDifference < 0" class="icon-arrow-up text-danger"></span>
                                                </div>
                                                <div class="text-success font-weight-bold">
                                                    SPENT THIS MONTH
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="mostOrderedProduct?.product?.id" class="col-md-6 col-lg-3 text-center text-md-left my-3">
                                            <div class="h6 text-dark mb-0">
                                                {{ mostOrderedProduct.product.name }}
                                                <small class="d-block text-muted pt-1">
                                                    Ordered {{ Number(mostOrderedProduct.total_orders).toLocaleString() }} time(s)
                                                </small>
                                            </div>
                                            <div class="text-success font-weight-bold">
                                                MOST ORDERED PRODUCT
                                            </div>
                                        </div>

                                        <div v-if="preferredVendor?.vendor?.id" class="col-md-6 col-lg-3 text-center text-md-left my-3">
                                            <div class="h6 text-dark mb-0">
                                                {{ preferredVendor.vendor.shop_name }}
                                                <small class="d-block text-muted">
                                                    {{ Number(preferredVendor.total_orders).toLocaleString() }} Orders(s)
                                                </small>
                                            </div>
                                            <div class="text-success font-weight-bold">
                                                PREFERED SELLER
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="sales-trend" class="w-full"></div>
                            </div><!-- / Sales trend chart -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import IconAlertCircle from "@/Components/Icons/AlertCircle"
import { usePage } from '@inertiajs/inertia-vue3'
import useShortenNumber from '@/composables/useShortenNumber'
import ApexCharts from 'apexcharts'
import { onMounted } from '@vue/runtime-core'

export default {

    components: {
        AppLayout,
        IconAlertCircle,
    },

    setup() {
        const {
            currMonthSpending,
            spendingDifference,
            spendingTrend,
            mostOrderedProduct,
            preferredVendor,
        } = usePage().props.value

        const {
            shortenNumber,
        } = useShortenNumber()

        
        const showChart = () => {
            const amounts = []
            Object.values(spendingTrend).forEach(
                ({ month_total_amount }) => {
                amounts.push(Number(month_total_amount || 0))
            })
            const options = {
                chart: {
                    type: 'bar',
                    height: 350,
                },
                series: [
                    {
                        name: 'amount',
                        data: amounts
                    },
                ],
                xaxis: {
                    categories: Object.keys(spendingTrend)
                }
            }
            setTimeout(() => {
                var chart = new ApexCharts(document.querySelector("#sales-trend"), options);
                chart.render();
            }, 150)
        }

        onMounted(() => {
            showChart()
        })

        return {
            currMonthSpending,
            spendingDifference,
            shortenNumber,
            spendingTrend,
            mostOrderedProduct,
            preferredVendor,
        }
    }
}
</script>
