<template>
    <footer class="footer footer-2">
        <div class="icon-boxes-container">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon">
                                <i class="icon-rocket"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">Free Shipping</h3><!-- End .icon-box-title -->
                                <p>Within Lagos State</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-3 -->
                    
                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon">
                                <i class="icon-rotate-left"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">Free Returns</h3><!-- End .icon-box-title -->
                                <p>Within 7 days</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-3 -->

                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon">
                                <i class="icon-info-circle"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">First Order Discount</h3><!-- End .icon-box-title -->
                                <p>Upto 10% discount on your first order</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-3 -->

                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon">
                                <i class="icon-life-ring"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">We Support</h3><!-- End .icon-box-title -->
                                <p>24/7 amazing services</p>
                            </div><!-- End .icon-box-content -->
                        </div><!-- End .icon-box -->
                    </div><!-- End .col-sm-6 col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .icon-boxes-container -->

        <div class="footer-middle border-0 pb-3">
            <div class="container">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-lg-5">
                        <div class="widget widget-about mb-0 d-flex flex-column flex-md-row justify-content-md-center flex-lg-column align-items-md-center  align-items-lg-start">
                            
                            <div class="widget-about-info flex-fill">
                                <div class="row">
                                    <div class="col-12 mt-0">
                                        <span class="widget-about-title text-uppercase font-weight-bold text-center text-md-left">Payment Method</span>
                                        <figure class="footer-payments d-flex justify-content-center justify-content-md-start">
                                            <img src="/molla-template/images/payments.png" alt="Payment methods" width="272" height="20">
                                        </figure><!-- End .footer-payments -->
                                    </div><!-- End .col-sm-6 -->
                                </div><!-- End .row -->
                            </div><!-- End .widget-about-info -->

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
                                    <h4 class="widget-title text-center text-md-left">Information</h4><!-- End .widget-title -->

                                    <ul class="widget-list text-center text-md-left">
                                        <li><inertia-link @click="toggleMobileMenu" :href="route('about_us')">About {{ this.$page.props.config_app.name }}</inertia-link></li>
                                        <li><a href="#">How to shop</a></li>
                                        <li><a href="#">FAQ</a></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="widget">
                                    <h4 class="widget-title text-center text-md-left">Customer Service</h4><!-- End .widget-title -->

                                    <ul class="widget-list text-center text-md-left">
                                        <li><a href="#">Terms and conditions</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                        <li><a href="#">Contact us</a></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-md-4 -->

                            <div class="col-md-4">
                                <div class="widget">
                                    <h4 class="widget-title text-center text-md-left">Make Money</h4><!-- End .widget-title -->

                                    <ul class="widget-list text-center text-md-left">
                                        <li><a href="#">Sell on {{ this.$page.props.config_app.name }}</a></li>
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