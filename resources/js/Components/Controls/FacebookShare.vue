<template>
    <div
        class="fb-share-button"
        :data-href="link"
        data-layout="button"
        data-size="large"
    >
        <a
            target="_blank"
            :href="shareLink"
            class="fb-xfbml-parse-ignore"
            >Share</a
        >
    </div>
</template>

<script>
import { computed } from '@vue/reactivity';
export default {
    name: "FacebookShare",
    props: {
        link: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const { link } = props;
        const shareLink = computed(() => {
            return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(link)}&src=sdkpreparse`;
        });

        return {
            link,
            shareLink,
        }
        
    },
}
</script>