<template>
    <app-layout>
        <agent-banner />
        <div class="page-content py-4 bg-light">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12 px-3">
                            <h2 class="text-center my-4">How it works</h2>
                            <div class="row">
                                <div class="col-sm-6 col-md-3 my-4">
                                    <div class="icon-box icon-box-circle text-center">
                                        <span class="icon-box-icon bg-success">
                                            <span>1</span>
                                        </span>
                                        <div class="icon-box-content">
                                            <h3 class="icon-box-title">Register online</h3><!-- End .icon-box-title -->
                                            <p>
                                                This takes less than 5 minutes
                                                enter your details, indicate your purchase volume and verify your email address.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3 my-4">
                                    <div class="icon-box icon-box-circle text-center">
                                        <span class="icon-box-icon bg-success">
                                            <span>2</span>
                                        </span>
                                        <div class="icon-box-content">
                                            <h3 class="icon-box-title">Await approval</h3><!-- End .icon-box-title -->
                                            <p>
                                                This may take up to 72 hours.
                                                You can start make purchases at this point.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3 my-4">
                                    <div class="icon-box icon-box-circle text-center">
                                        <span class="icon-box-icon bg-success">
                                            <span>3</span>
                                        </span>
                                        <div class="icon-box-content">
                                            <h3 class="icon-box-title">Refer farmers</h3><!-- End .icon-box-title -->
                                            <p>
                                                Login to your account and start referring farmers.
                                                The farmers' details will be verified and you will be notified.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3 my-4">
                                    <div class="icon-box icon-box-circle text-center">
                                        <span class="icon-box-icon bg-success">
                                            <span>4</span>
                                        </span>
                                        <div class="icon-box-content">
                                            <h3 class="icon-box-title">Shop &amp; Earn</h3><!-- End .icon-box-title -->
                                            <p>
                                                Start buying volume items on behalf of the farmers you referred and earn regulary commissions on bulk purchases.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="my-4 text-center">
                                <inertia-link
                                    v-if="!isLoggedIn"
                                    :href="route('registration.agent')"
                                    class="btn btn-success btn-lg mr-md-4">
                                    REGISTER NOW
                                </inertia-link>

                                <div class="d-md-none my-4 text-center">OR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script setup>
import { usePage } from '@inertiajs/inertia-vue3';

import AppLayout from "@/Layouts/AppLayout.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import AgentBanner from '@/Components/AgentBanner.vue'

const pageProps = usePage().props.value
const isLoggedIn = !!Number(pageProps?.user?.id)
</script>