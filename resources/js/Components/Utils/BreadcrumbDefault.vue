<template>
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <inertia-link class="text-primary" href="/">Home</inertia-link>
                </li>
                <li v-for="crumb in breadcrumbs" :key="crumb?.text || crumb" class="breadcrumb-item">
                    <span v-if="crumb.contructor == String">{{ crumb }}</span>
                    <inertia-link v-else-if="crumb?.link" :href="crumb.link" class="text-primary">{{crumb.text}}</inertia-link>
                    <span v-else>{{ crumb?.text }}</span>
                </li>
            </ol>
        </div>
    </nav>
</template>

<script>
export default {
    props: {
        breadcrumbs: {
            type: Array,
            required: true,
        },
    },
}
</script>
