<template>
    <header class="header header-10 header-intro-clearance">
        <TopBar />
        <NavMiddle />
        <NavBottom />
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
    }
}
</script>