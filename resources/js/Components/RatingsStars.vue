<template>
    <div
        v-if="stars > 0"
        class="ratings-container mb-0"
        :title="remark ? remark : ''"
    >
        <div class="ratings" style="font-size: 2rem;">
            <div class="ratings-val" :style="`width: ${width}%`"></div>
            <!-- End .ratings-val -->
        </div>
        <!-- End .ratings -->
        <a
            v-if="reviewCount"
            class="ratings-text"
            href="#product-review-link"
            id="review-link"
            >( {{ reviewCount }} {{ reviewCount > 1 ? 'Reviews' : 'Review' }} )</a
        >
    </div>
</template>


<style scoped>
    .ratings, .ratings-val {
        font-size: 2rem;
    }
</style>

<script>
export default {
    props: {
        stars: {
            type: Number,
            default: 1,
        },
        remark: {
            type: String,
            default: '',
        },
        reviewCount: {
            type: Number,
            default: 0,
        },
    },
    setup(props) {
        const width = props.stars * 20;

        return {
            width,
        }
    },
};
</script>