<template>
    <div v-if="banners?.length" class="container">
        <div class="row">
            <div class="col-12">
                <div
                    id="HomeCarousel"
                    class="carousel slide carousel-fade"
                    data-ride="carousel"
                    style="height: 300px;"
                >
                    <ol class="carousel-indicators">
                        <li
                            v-for="(banner, index) in banners"
                            :key="index"
                            data-target="#HomeCarousel"
                            :data-slide-to="index"
                            :class="{
                                'active' : index == 0,
                            }"
                        ></li>
                    </ol>
                    <div class="carousel-inner">
                        <div
                            v-for="(banner, index) in banners"
                            :key="index"
                            :class="{
                                'carousel-item': true,
                                'active': index == 0,
                            }"
                        >
                            <img
                                :src="banner.image_url"
                                class="d-block w-100"
                                :alt="banner.name"
                            />
                            <div
                                v-if="banner?.caption || banner?.description"
                                class="
                                    carousel-caption
                                    d-none d-md-block
                                "
                            >
                                <h5 v-if="banner?.caption">{{ banner?.caption }}</h5>
                                <p v-if="banner?.description">{{ banner?.description }}</p>
                            </div>
                        </div>
                    </div>
                    <a
                        class="carousel-control-prev"
                        href="#HomeCarousel"
                        role="button"
                        data-slide="prev"
                    >
                        <span
                            class="carousel-control-prev-icon"
                            aria-hidden="true"
                        ></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a
                        class="carousel-control-next"
                        href="#HomeCarousel"
                        role="button"
                        data-slide="next"
                    >
                        <span
                            class="carousel-control-next-icon"
                            aria-hidden="true"
                        ></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Intro Container -->
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    setup() {
        const { banners } = usePage().props.value

        console.log('The banners', { banners })

        return {
            banners,
        }
    },
}
</script>