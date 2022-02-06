<script setup>
const props = defineProps({
    referralRequest: {
        type: Object,
        required: true,
    },
});
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
                v-if="!!referralRequest?.is_editable"
                class="dropdown-item font-size-normal" type="button">
                Delete
            </button>
        </div>
    </div>
</template>
