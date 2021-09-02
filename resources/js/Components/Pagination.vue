<template>
    <div class="d-flex">
        <ul class="pagination ms-auto">
            <li v-if="prev_page_url" class="page-item">
                <inertia-link class="page-link page-link-prev" :href="getPaginationUrl(current_page - 1)" tabIndex="-1" aria-disabled="true">
                    <span aria-hidden="true"><i class="icon-long-arrow-left"></i></span>
                    Prev
                </inertia-link>
            </li>

            <li v-if="minLinkOnLeft > 3" class="page-item">
                <inertia-link class="page-link" :href="getPaginationUrl(1)">1</inertia-link>
            </li>

            <li v-if="minLinkOnLeft > 3" class="page-item">
                <span>...</span>
            </li>

            <li
                v-for="link in pageLinks"
                :class="`page-item ${link.active ? 'active' : ''} ${link.url ? '' : 'disabled'}`"
                :key="link.label"
            >
                <inertia-link
                    v-if="link.url"
                    class="page-link"
                    :href="getPaginationUrl(link.label)"
                >
                    {{link.label}}
                </inertia-link>
                <span v-else>{{ link.label }}</span>
            </li>

            <li v-if="minLinkOnLeft < 3 && maxLinkOnRight < (last_page - 3)" class="page-item">
                <span>...</span>
            </li>

            <li v-if="minLinkOnLeft < 3 && maxLinkOnRight < (last_page - 3)" class="page-item">
                <inertia-link
                    class="page-link"
                    :href="getPaginationUrl(last_page)"
                >
                    {{last_page}}
                </inertia-link>
            </li>

            <li v-if="next_page_url" class="page-item">
                <inertia-link
                    class="page-link page-link-next"
                    :href="getPaginationUrl(current_page + 1)"
                    tabIndex="-1"
                    aria-disabled="true"
                >
                    Next
                    <span aria-hidden="true"><i class="icon-long-arrow-right"></i></span>
                </inertia-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        paginator: {
            required: true,
            type: Object,
        },
    },

    setup(props) {
        const {
            prev_page_url, next_page_url, links, current_page, last_page,
            first_page_url, last_page_url,
        } = props.paginator;

        const numLinks = 6
        const linksOnSides = Math.floor((numLinks - 1)/2)
        let maxLinkOnRight = (last_page - current_page) > linksOnSides
            ? current_page + linksOnSides
            : last_page;

        let minLinkOnLeft = current_page - 1 < linksOnSides
            ? 1 : current_page - linksOnSides
        
        let addMoreLinks = numLinks - (maxLinkOnRight - minLinkOnLeft + 1)

        if (addMoreLinks) {
            minLinkOnLeft = minLinkOnLeft == 1
                ? minLinkOnLeft
                : Math.min(minLinkOnLeft, Math.abs(minLinkOnLeft - addMoreLinks))

            addMoreLinks = numLinks - (maxLinkOnRight - minLinkOnLeft + 1)

            maxLinkOnRight = addMoreLinks == 0
                ? maxLinkOnRight
                : Math.min(last_page, maxLinkOnRight + addMoreLinks)
        }

        const penultimatePage = last_page - 1;
        const pageLinks = links.filter(link => {
            const isWithinRange = link.label >= minLinkOnLeft && link.label <= maxLinkOnRight
            
            return Number(link.label) && isWithinRange
        })

        const getPaginationUrl = (pageNumber) => {
            return route(
                route().current(),
                {
                    ...route().params,
                    page: pageNumber,
                }
            )
        }

        return {
            prev_page_url,
            next_page_url,
            links,
            current_page,
            last_page,
            first_page_url,
            last_page_url,
            maxLinkOnRight,
            minLinkOnLeft,
            penultimatePage,
            pageLinks,
            getPaginationUrl,
        };
    }
}
</script>