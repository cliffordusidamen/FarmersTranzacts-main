<template>
    <app-layout>
        <div class="intro-slider-container">
            <div class="container intro-content">
                <div class="row">
                    <div class="col-auto offset-lg-3 intro-col">
                        <h2 class="h4 font-weight-bold">
                            Dashboard
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <welcome />
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'

    export default {
        components: {
            AppLayout,
            Welcome,
        },
    }
</script>
