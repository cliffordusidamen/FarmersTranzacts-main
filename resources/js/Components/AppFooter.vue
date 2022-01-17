<template>
    <footer class="footer footer-2">
        <div class="icon-boxes-container">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-success">
                                <i class="icon-rocket"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">Prompt Delivery</h3><!-- End .icon-box-title -->
                                <p>Direct to your address</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-4 -->
                    
                    <div class="col-sm-6 col-lg-4">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-success">
                                <i class="icon-star-o"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">Secure Payments</h3><!-- End .icon-box-title -->
                                <p>Make payments without worry</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-4 -->

                    <div class="col-sm-6 col-lg-4">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-success">
                                <i class="icon-life-ring"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">We Support</h3><!-- End .icon-box-title -->
                                <p>24/7 amazing services</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-4 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .icon-boxes-container -->

        <div class="footer-middle border-0 pb-3">
            <div class="container">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-5">
                        <div class="widget widget-about mb-0 d-flex flex-column flex-md-row justify-content-md-center flex-lg-column align-items-md-center  align-items-lg-start">

                            <div class="flex-fill">
                                <span class="widget-about-title text-uppercase font-weight-bold mt-2 text-center text-md-left">Connect with us:</span>
                                <div class="d-flex justify-content-center justify-content-md-start">
                                    <a
                                        v-if="social_facebook"
                                        :href="`https://facebook.com/${social_facebook}`"
                                        class="social-icon social-facebook"
                                        title="Connect with us on Facebook"
                                        target="_blank"
                                    >
                                        <i class="icon-facebook-f"></i>
                                    </a>
                                    <a
                                        v-if="social_twitter"
                                        :href="`https://twitter.com/@${social_twitter}`"
                                        class="social-icon social-twitter"
                                        title="Follow us on Twitter"
                                        target="_blank"
                                    >
                                        <i class="icon-twitter"></i>
                                    </a>
                                    <a
                                        v-if="social_instagram"
                                        :href="`https://instagram.com/@${social_instagram}`"
                                        class="social-icon social-instagram"
                                        title="Follow us on Instagram"
                                        target="_blank"
                                    >
                                        <i class="icon-instagram"></i>
                                    </a>
                                    <a
                                        v-if="social_youtube"
                                        :href="`https://youtube.com/@${social_youtube}`"
                                        class="social-icon social-youtube"
                                        title="Subscribe to our Youtube channel"
                                        target="_blank"
                                    >
                                        <i class="icon-youtube"></i>
                                    </a>
                                </div><!-- End .soial-icons -->
                            </div>
                        </div><!-- End .widget about-widget -->
                    </div><!-- End .col-sm-12 col-lg-3 -->

                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="widget">
                                    <h4 class="widget-title text-center text-success text-md-left">Information</h4><!-- End .widget-title -->

                                    <ul class="widget-list list-unstyled text-center text-md-left">
                                        <li><inertia-link :href="route('about_us')">About {{ this.$page.props.config_app.name }}</inertia-link></li>
                                        <li><inertia-link :href="route('help.faq')">FAQ</inertia-link></li>
                                        <li><inertia-link :href="route('help.index')">Help center</inertia-link></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="widget">
                                    <h4 class="widget-title text-center text-success text-md-left">Customer Service</h4><!-- End .widget-title -->

                                    <ul class="widget-list list-unstyled text-center text-md-left">
                                        <li><inertia-link :href="route('terms')">Terms and conditions</inertia-link></li>
                                        <li><inertia-link :href="route('privacy_policy')">Privacy Policy</inertia-link></li>
                                        <li><inertia-link :href="route('contact_us')">Contact us</inertia-link></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="widget">
                                    <h4 class="widget-title text-center text-success text-md-left">Make Money</h4><!-- End .widget-title -->

                                    <ul class="widget-list list-unstyled text-center text-md-left">
                                        <li>
                                            <a
                                                :href="this.$page.props.config_app.seller_app_url"
                                                target="_blank"
                                            >
                                                Sell on {{ this.$page.props.config_app.name }}
                                            </a>
                                        </li>

                                        <li>
                                            <inertia-link
                                                :href="route('become_an_agent')"
                                            >
                                                Become an agent
                                            </inertia-link>
                                        </li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-md-4 -->
                        </div>
                    </div>

                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .footer-middle -->
    </footer><!-- End .footer -->
</template>

<script>
import useAppSettings from '@/composables/useAppSettings'

export default {
    setup() {
        const {
            social_facebook,
            social_youtube,
            social_instagram,
            social_twitter,
        } = useAppSettings()

        return {
            social_facebook,
            social_youtube,
            social_instagram,
            social_twitter,
        }
    },
}
</script>