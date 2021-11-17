<template>
    <div
      v-if="!isEditAgentPage && user.email_verified_at && (isPendingAgentApproval || isDisapprovedAgent)"
      :class="{
          'alert alert-dismissible': true,
          'alert-secondary': isPendingAgentApproval,
          'alert-warning': isDisapprovedAgent,
        }"
      role="alert"
    >
        <div class="d-flex">
            <div>
                <icon-info-circle v-if="isPendingAgentApproval" classes="alert-icon" />
                <icon-alert-triangle v-else classes="alert-icon" />
            </div>
            <div class="text-dark">
                <p class="text-dark" v-if="isPendingAgentApproval">
                    Your registration as agent is currently being reviewed. You can still shop and purchase items.
                </p>

                <p class="text-white" v-else>
                    Your registration as agent was not approved!
                    <Link
                        v-if="!isEditAgentPage"
                        :href="route('profile.edit_agent_disapproval')"
                        class="btn btn-primary btn-link"
                    >
                        VIEW DETAILS
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'
import IconInfoCircle from "@/Components/Icons/InfoCircle"
import IconAlertTriangle from "@/Components/Icons/AlertTriangle"
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        IconInfoCircle,
        IconAlertTriangle,
        Link,
    },

    setup() {
        const {user, userAgent, url} = usePage().props.value
        const isPendingAgentApproval = userAgent.approval_status == 'pending'
        const isDisapprovedAgent = userAgent.approval_status == 'disapproved'
        const isEditAgentPage = usePage().url.value == '/profile/edit-agent-disapproval'

        return {
            user,
            isPendingAgentApproval,
            isDisapprovedAgent,
            isEditAgentPage,
        }
    },
}
</script>