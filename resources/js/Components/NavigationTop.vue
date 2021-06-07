<template>
    <header class="header header-10 header-intro-clearance">
        <NavMiddle />
        <NavBottom :show-categories="showCategories" />
    </header>
</template>

<script>
import TopBar from "@/Components/TopBar"
import NavMiddle from "@/Components/NavMiddle"
import NavBottom from "@/Components/NavBottom"

export default {
    components: {
        TopBar,
        NavMiddle,
        NavBottom,
    },
    props: {
        showCategories: {
            type: Boolean,
            default: true,
        }
    },
}
</script>