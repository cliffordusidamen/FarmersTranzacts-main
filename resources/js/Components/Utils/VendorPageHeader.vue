<template>
    <div
        class="page-header text-center"
        :style="`background-image: url('${getBackgroundUrl()}')`"
    >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-start align-items-md-center">
                        <div class="logo-container">
                            <img
                                :src="`${getLogoUrl()}`"
                                class="bg-white img-fluid rounded-lg p-1"
                                style="max-height: 80px"
                            />
                        </div>
                        <div class="flex-grow-1 pl-2">
                            <h3 :class="{
                                'page-title text-white text-shadow': true,
                                'text-left': getLogoUrl() ? true : false,
                            }">
                                {{ vendor.shop_name }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End .container -->
    </div>
    <!-- End .page-header -->
</template>

<script>
export default {
    props: {
        vendor: {
            type: Object,
            required: true,
        },
    },

    setup(props) {
        const { vendor } = props

        const getBackgroundUrl = () => {
            return !vendor?.cover_picture_url
                ? '/molla-template/images/page-header-bg.jpg'
                : vendor?.cover_picture_url
        }

        const getLogoUrl = () => {
            return !vendor?.logo_url
                ? ''
                : vendor?.logo_url
        }

        return {
            getBackgroundUrl,
            getLogoUrl,
        }
    }
}
</script>
