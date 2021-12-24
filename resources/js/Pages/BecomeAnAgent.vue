<template>
    <app-layout>
        <agent-banner />
        <div class="page-content py-4 bg-light">
            <div class="checkout">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10 px-3">
                            <h2 class="text-center my-4">How it works</h2>
                            <div class="row">
                                <div class="col-md-4 my-4">
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

                                <div class="col-md-4 my-4">
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

                                <div class="col-md-4 my-4">
                                    <div class="icon-box icon-box-circle text-center">
                                        <span class="icon-box-icon bg-success">
                                            <span>3</span>
                                        </span>
                                        <div class="icon-box-content">
                                            <h3 class="icon-box-title">Shop &amp; Earn</h3><!-- End .icon-box-title -->
                                            <p>
                                                Start buying volume items and earn commission.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="my-4 text-center">
                                <inertia-link
                                    :href="route('registration.agent')"
                                    class="btn btn-primary btn-lg mr-2">
                                    GET STARTED NOW
                                </inertia-link>

                                <inertia-link
                                    :href="route('login')"
                                    class="btn btn-secondary btn-lg">
                                    LOGIN NOW
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import AgentBanner from '@/Components/AgentBanner.vue'

export default {
    components: {
        AppLayout,
        PageHeader,
        AgentBanner,
    },
    setup() {
    },
};
</script>