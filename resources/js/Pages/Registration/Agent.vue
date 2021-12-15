<template>
    <app-layout>
        <page-header :title="'Become an agent'" />
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-8 mx-4">
                    <jet-validation-errors class="mb-3" />

                    <form ref="registerForm" id="registerForm">
                        <h4 class="text-center text-success border-bottom mb-3 pb-2 text-uppercase">
                            Personal Information
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="first_name" value="First Name" />
                                    <jet-input
                                        name="first_name"
                                        max-length="25"
                                        id="first_name"
                                        type="text"
                                        v-model="form.first_name"
                                        required
                                        autofocus
                                        data-cfilter-allow="alpha"
                                        autocomplete="first_name"
                                    />
                                    <jet-input-error :message="form.errors.first_name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="last_name" value="Last Name" />
                                    <jet-input
                                        name="last_name"
                                        max-length="25"
                                        id="last_name"
                                        type="text"
                                        v-model="form.last_name"
                                        required
                                        data-cfilter-allow="alpha"
                                        autocomplete="last_name"
                                    />
                                    <jet-input-error :message="form.errors.last_name" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="email" value="Email" />
                                    <jet-input
                                        name="email"
                                        max-length="255"
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        data-cfilter="email"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.email" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label for="phone" value="Phone" />
                                    <div>
                                        <jet-input
                                            name="phone"
                                            id="phone"
                                            max-length="15"
                                            type="text"
                                            v-model="form.phoneInput"
                                        />
                                        <jet-input-error :message="form.errors.phone" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="password" value="Password" />
                                    <jet-input
                                        name="password"
                                        id="password"
                                        max-length="50"
                                        type="password"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <jet-input-error :message="form.errors.password" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label
                                        class="required"
                                        for="password_confirmation"
                                        value="Confirm Password"
                                    />
                                    <jet-input
                                        name="password_confirmation"
                                        max-length="50"
                                        id="password_confirmation"
                                        type="password"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                    />
                                    <jet-input-error :message="form.errors.password_confirmation" />
                                </div>
                            </div>
                        </div>

                        <h4 class="text-center text-success border-bottom mb-3 pb-2 text-uppercase">
                            Business information
                        </h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label for="business_name" value="Business name" />
                                    <jet-input
                                        name="business_name"
                                        max-length="100"
                                        id="business_name"
                                        type="text"
                                        v-model="form.business_name"
                                    />
                                    <jet-input-error :message="form.errors.business_name" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="store_address_line_1" value="Address Line 1" />
                                    <jet-input
                                        name="store_address_line_1"
                                        max-length="50"
                                        id="store_address_line_1"
                                        type="text"
                                        v-model="form.store_address_line_1"
                                        required
                                        autocomplete="store_address_line_1"
                                    />
                                    <jet-input-error :message="form.errors.store_address_line_1" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label for="store_address_line_2" value="Address Line 2" />
                                    <jet-input
                                        name="store_address_line_2"
                                        max-length="50"
                                        id="store_address_line_2"
                                        type="text"
                                        v-model="form.store_address_line_2"
                                        autocomplete="store_address_line_2"
                                    />
                                    <jet-input-error :message="form.errors.store_address_line_2" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label for="store_city" value="City" />
                                    <jet-input
                                        name="store_city"
                                        max-length="50"
                                        id="store_city"
                                        type="text"
                                        v-model="form.store_city"
                                        autocomplete="store_city"
                                    />
                                    <jet-input-error :message="form.errors.store_city" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="store_state" value="State" />
                                    <custom-select
                                        id="type"
                                        name="store_state"
                                        v-model="form.store_state"
                                        :options="app_states"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.store_state" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <jet-label class="required" for="spending_volume_id" value="Target spending volume" />
                                    <custom-select
                                        id="spending_volume_id"
                                        name="spending_volume_id"
                                        v-model="form.spending_volume_id"
                                        :options="this.$page.props.spendingVolumes"
                                        required
                                    />
                                    <jet-input-error :message="form.errors.spending_volume_id" />
                                </div>
                            </div>
                        </div>

                        <!-- PRODUCTS CATERGORIES -->
                        
                        <p class="mb-4  text-muted h5 text-center">
                            What product categories are you interested in?
                        </p>
                        <div class="form-group row justify-content-center">
                            <span class="is-invalid text-danger invalid-feedback">Select at least one(1) product category!</span>
                            <div
                                v-for="category in product_categories" :key="category.id"
                                class="col-5"
                            >
                                <div class="custom-control custom-checkbox">
                                    <input
                                        class="custom-control-input"
                                        name="product_categories[]"
                                        type="checkbox"
                                        :id="'category_' + category.id"
                                        :value="category.id"
                                        v-model="form.product_categories"
                                        required
                                    />

                                    <label class="custom-control-label" :for="'category_' + category.id">
                                        {{ category.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- [END] - PRODUCTS CATERGORIES -->

                        <!-- DISPLAY TERMS & CONDITIONS -->
                        <div class="card p-0 border rounded-md mb-4">
                            <div class="card-header border-bottom px-3 py-3">
                                {{ $page.props.agentTermsPage.title }}
                            </div>
                            <div
                                class="card-body m-0"
                                style="max-height: 300px; overflow-y: auto;"
                                v-html="$page.props.agentTermsPage.content"
                            ></div>
                            <div class="card-footer bg-white border-top px-3 py-3">
                                <!-- CHECKBOX FOR TERMS AND CONDITTIONS ACCEPTANCE -->
                                <div class="custom-control custom-checkbox font-weight-bold">
                                    <input
                                        class="custom-control-input"
                                        name="terms"
                                        type="checkbox"
                                        id="terms"
                                        value="1"
                                        v-model="form.terms"
                                        required
                                    />

                                    <label class="custom-control-label" for="terms">
                                        I agree to the terms &amp; conditions stated above
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- [END] - DISPLAY TERMS & CONDITIONS -->

                        <div class="mb-0 text-center">
                            <jet-button
                                class="ml-4 text-uppercase"
                                :class="{ 'text-white-50': form.processing }"
                                :disabled="form.processing"
                                context-class="btn-primary"
                            >
                                Register
                            </jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbDefault from "@/Components/Utils/BreadcrumbDefault.vue";
import PageHeader from "@/Components/Utils/PageHeader.vue";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import CustomSelect from "@/Components/CustomSelect"
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';
import useInitIntlPhone from "@/composables/useInitIntlPhone"

export default {
    props: ['app_states', 'product_categories'],

    components: {
        AppLayout,
        BreadcrumbDefault,
        PageHeader,
        JetButton,
        JetInput,
        JetInputError,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        CustomSelect,
    },

    setup(props) {
        const {app_states, product_categories} = props;
        const jqueryValidator = ref(null)
        const registerForm = ref(null)

        const phoneInput = ref(null)
        const telInput = ref(null)
        const { initIntlPhone } = useInitIntlPhone()

        const form = useForm({
            first_name: "",
            last_name: "",
            email: "",
            phone: "",
            phoneInput: "",
            password: "",
            password_confirmation: "",
            terms: false,
            business_name: '',
            store_address_line_1: '',
            store_address_line_2: '',
            store_city: '',
            store_state: '',
            product_categories: [],
            spending_volume_id: '',
        })

        const submit = () => {
            form.phone = telInput.value.getNumber()
            form.post(route("registration.save_agent"), {
                preserveState: true,
                onFinish: () => {
                    form.processing = false;
                },
                onSuccess: () => {
                    form.reset();
                    jqueryValidator.value.resetForm();
                    $('#registerForm').removeClass('was-validated');
                },
                onError: () => {
                    form.reset("password", "password_confirmation");
                },
            });
        }

        const validationConfig = () => {

            const nameValidation = {
                required: true,
                minlength: 3,
                maxlength: 25,
            };

			const passwordRules = {
				required: true,
				minlength: 8,
				maxlength: 50,
			};

            return {
                submitHandler() {
                    registerForm.value.classList.add("was-validated");
                    submit();
                },
                rules: {
                    first_name: { ...nameValidation },
                    last_name: { ...nameValidation },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    phone: {
						intl_phone: true,
						maxlength: 17,
					},
                    password: {...passwordRules},
                    password_confirmation: {
                        ...passwordRules,
                        equalTo: "#password",
                    },

                    business_name: {
                        required: false,
                        minlength: 3,
                        maxlength: 255,
                    },

                    store_address_line_1: {
                        required: true,
                        minlength: 5,
                    },
                    store_address_line_2: {
                        required: false,
                        minlength: 5,
                    },
                    store_city: {
                        required: false,
                        minlength: 5,
                    },
                    store_state: 'required',
                    terms: 'required',
                    spending_volume_id: 'required',
                    'product_categories[]': {
                        required: true,
                        rangelength: [1, product_categories.length]
                    }
                },

                messages: {
                    'product_categories[]': 'Select at least one(1) product category',
                },
            };
        }
        
        onMounted(() => {
            initIntlPhone(phoneInput, telInput, 'phone')
            jqueryValidator.value = $(registerForm.value).validate(
                validationConfig()
            );
        })

        return {
            form,
            registerForm,
            jqueryValidator,
            app_states,
            product_categories,
        }
    },
};
</script>
