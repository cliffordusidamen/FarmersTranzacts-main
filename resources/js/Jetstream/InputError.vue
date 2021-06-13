<template>
    <div v-show="message" class="invalid-feedback d-block" role="alert">
        <strong>{{ message }}</strong>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>
