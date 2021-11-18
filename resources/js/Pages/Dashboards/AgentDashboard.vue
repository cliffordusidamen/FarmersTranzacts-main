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
                    <div class="row justify-content-center card-deck">
                        <div class="card">
                            <div class="card-body">
                                <span class="fa fa-money display-3"></span>
                                <div>
                                    SPENT THIS MONTH
                                </div>
                            </div>
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

export default {

    components: {
        AppLayout,
        IconAlertCircle,
    },
}
</script>
