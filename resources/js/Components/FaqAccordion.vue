<template>
    <div
        v-if="faqs.length"
    >
        <div
            v-for="faq in faqs"
            :key="faq.id"
            class="card card-box card-sm bg-light"
        >
            <div class="card-header" :id="`heading-${faq.id}`">
                <h2 class="card-title">
                    <a role="button" data-toggle="collapse" :href="`#collapse--${faq.id}`" aria-expanded="true" :aria-controls="`collapse--${faq.id}`">
                        {{ faq.question}}
                    </a>
                </h2>
            </div><!-- End .card-header -->
            <div
                :id="`collapse--${faq.id}`"
                :class="{
                    'collapse': true,
                    'show': faq.id == faqs[0].id,
                }"
                :aria-labelledby="`heading-${faq.id}`"
                data-parent="#accordion-1"
            >
                <div class="card-body" v-html="faq.answer">
                </div><!-- End .card-body -->
            </div><!-- End .collapse -->
        </div><!-- End .card -->
    </div>
</template>

<script>
export default {
    props: {
        faqs: {
            type: Array,
            default: () => [],
        },
    },
}
</script>