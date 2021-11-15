<template>
    <app-layout>
        <page-header title="Contact Us" />
        <breadcrumb-default :breadcrumbs="breadcrumbLinks" />
        <div class="page-content">
            <div class="page-content pb-0">
                <div class="container">
                	<div class="row">
                		<div class="col-lg-6 mb-2 mb-lg-0">
                            <h2 class="title mb-1">Contact Information</h2>
                            <div class="contact-info">
                                <ul class="contact-list">
                                    <li>
                                        <i class="icon-map-marker"></i>
                                        70 Washington Square South New York, NY 10012, United States
                                    </li>
                                    <li>
                                        <i class="icon-phone"></i>
                                        <a href="tel:#">+92 423 567</a>
                                    </li>
                                    <li>
                                        <i class="icon-envelope"></i>
                                        <a href="mailto:#">info@Molla.com</a>
                                    </li>
                                </ul><!-- End .contact-list -->
                            </div>
                        </div>
                		<div class="col-lg-6 mb-2">
                            <h2 class="title mb-1">Got Any Questions?</h2>
                			<p class="mb-2">Use the form below to send a message to us now.</p>

                            <form ref="frmContact" class="contact-form mb-3">
                				<div class="row">
                					<div class="col-sm-6">
                                        <jet-label
                                            class="required"
                                            for="name"
                                            value="Full name"
                                        />
                                        <jet-input
                                            name="name"
                                            max-length="50"
                                            id="name"
                                            type="text"
                                            v-model="form.name"
                                            required
                                            autofocus
                                            autocomplete="name"
                                        />
                                        <jet-input-error :message="form.errors.name" />
                					</div><!-- End .col-sm-6 -->

                					<div class="col-sm-6">
                                        <jet-label class="required" for="email" value="Email" />
                                        <jet-input
                                            name="email"
                                            max-length="255"
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                        />
                                        <jet-input-error :message="form.errors.email" />
                					</div><!-- End .col-sm-6 -->
                				</div><!-- End .row -->

                				<div class="row">
                					<div class="col-sm-6">
                                        <jet-label class="required" for="phone" value="Phone" />
                                        <jet-input
                                            name="phone"
                                            max-length="255"
                                            id="phone"
                                            type="text"
                                            v-model="form.phone"
                                        />
                                        <jet-input-error :message="form.errors.phone" />
                					</div><!-- End .col-sm-6 -->

                					<div class="col-sm-6">
                                        <jet-label for="subject" value="Subject" />
                                        <jet-input
                                            name="subject"
                                            max-length="255"
                                            id="subject"
                                            type="text"
                                            v-model="form.subject"
                                        />
                                        <jet-input-error :message="form.errors.subject" />
                					</div><!-- End .col-sm-6 -->
                				</div><!-- End .row -->

                				<div class="row">
                					<div class="col-sm-12">
                                        <jet-label for="message" value="Message" />
                                        <jet-textarea
                                            name="message"
                                            max-length="255"
                                            id="message"
                                            rows="4"
                                            v-model="form.message"
                                            required
                                        />
                                        <jet-input-error :message="form.errors.message" />
                					</div><!-- End .col-sm-12 -->
                				</div><!-- End .row -->

                                <div class="text-center p-3">
                                    <jet-button
                                        :class="{ 'text-white-50': form.processing }"
                                        :disabled="form.processing"
                                    >
                                        SEND MESSAGE
                                    </jet-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>


<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetTextarea from "@/Jetstream/Textarea";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';

export default {
    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        JetLabel,
        JetButton,
        JetInput,
        JetTextarea,
        JetInputError,
    },
    setup() {
        const breadcrumbLinks = [
            {
                text: "Shop",
                link: route("shop.index"),
            },
            {
                text: "Contact Us",
            },
        ];

        const form = useForm({
            name: '',
            email: '',
            phone: '',
            subject: '',
            message: '',
        })

        const jqValidator = ref(null)
        const frmContact = ref(null)

        const submit = () => {
            
            form.processing = true;
            form.post(route('send_contact_us'), {
                preserveScroll: false,
                onSuccess: () => {
                    form.reset();
                    jqValidator.value.resetForm();
                },
                onFinish: () => {
                    form.processing = false;
                },
            });
        }
        
        onMounted(() => {
            $.validator.addMethod( "fullName", function( value, element, param ) {
                const hasInvalidCharacters = (/[^a-z\s]/ig).test(value)
                return !hasInvalidCharacters
            }, 'Name should contain only alphabets')

            jqValidator.value = $(frmContact.value).validate({
                submitHandler: function(form) {
                    frmContact.value.classList.add("was-validated");
                    submit();
                },
                rules: {
                    name: {
                        required: true,
                        minlength: 5,
                        maxlength: 50,
                        fullName: true,
                    },
                    phone: {
                        required: () => {
                            return $('#email').val().trim() == ''
                        },
                        digits: true,
                        minlength: 11,
                        maxlength: 50,
                    },
                    email: {
                        required: () => {
                            return $('#phone').val().trim() == ''
                        },
                    },
                    subject: {
                        maxlength: 200,
                    },
                    message: {
                        required: true,
                        maxlength: 1000,
                    }
                }
            })
        })

        return {
            breadcrumbLinks,
            form,
            frmContact,
            submit,
        };
    },
};
</script>