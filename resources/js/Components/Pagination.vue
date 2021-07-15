<template>
    <nav v-show="paginator.last_page > 1" aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li v-for="link in paginator.links" :key="link.label" class="page-item" :class="{ 'active': Number(link.label) == paginator.current_page }">
                <inertia-link class="page-link" :href="link.url ?? '#'" v-html="link.label"></inertia-link>
            </li>
        </ul>
    </nav><!-- End .pagination -->
</template>

<script>
export default {
    props: ['paginator'],
}
</script>