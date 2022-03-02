<script setup>
const props = defineProps({
    referralRequest: {
        type: Object,
        required: true,
    },
});

const fireDeleteEvent = event => {
    event.preventDefault()

    const confirmDeleteEvent = new CustomEvent('confirm-action', {
        detail: {
            message: 'Are you sure you want to delete this farmer record?',
            url: event.target.getAttribute('data-href'),
            method: 'DELETE',
        },
    })

    document.dispatchEvent(confirmDeleteEvent)
}
</script>

<template>
    <div class="btn-group">
        <button
            type="button"
            class="btn btn-link text-uppercase dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            actions
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <inertia-link
                :href="route('referrals.show', { id: referralRequest.id })"
                class="dropdown-item font-size-normal"
            >
                View
            </inertia-link>

            <inertia-link
                v-if="!!referralRequest?.is_editable"
                :href="route('referrals.edit', { id: referralRequest.id })"
                class="dropdown-item font-size-normal"
            >
                Edit
            </inertia-link>

            <button
                v-if="!!referralRequest?.is_deletable"
                class="dropdown-item font-size-normal"
                type="button"
                :data-href="route('referrals.destroy', { id: referralRequest.id })"
                @click="fireDeleteEvent"
            >
                Delete
            </button>

            <inertia-link
                v-if="!!referralRequest?.farm_id"
                :href="route('referrals.create_season', { id: referralRequest.id })"
                class="dropdown-item font-size-normal"
            >
                New season
            </inertia-link>
        </div>
    </div>
</template>
