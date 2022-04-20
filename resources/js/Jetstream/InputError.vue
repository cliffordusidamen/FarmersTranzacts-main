<template>
    <div v-show="message" class="text-danger small d-block" role="alert">
        <strong>{{ message }}</strong>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>
