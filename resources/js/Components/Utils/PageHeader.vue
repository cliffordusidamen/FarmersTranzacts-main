<template>
    <div
        class="page-header text-center"
        style="background-image: url('/molla-template/images/page-header-bg.jpg')"
    >
        <div class="container">
            <h1 class="page-title">
                {{title}}
                <span v-if="subTitle.trim().length > 0">{{subTitle}}</span>
            </h1>
        </div>
        <!-- End .container -->
    </div>
    <!-- End .page-header -->
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        subTitle: {
            type: String,
            default: '',
        },
    },
}
</script>
