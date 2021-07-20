<template>
    <app-layout>
        <div class="page-content py-4 bg-light">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h4 class="text-center">
                            Re-submission of vendor registration
                        </h4>

                        <p class="text-center" v-if="user_vendor.reject_reason">
                            <strong>Reason for disapproval:</strong>
                            {{ user_vendor.reject_reason }}
                        </p>
                        <jet-validation-errors class="mb-3" />

                        <form ref="registerForm" id="registerForm">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <p class="pb-1 mb-2 border-bottom text-muted text-uppercase">
                                        Organization/Store Information
                                    </p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="store_name" value="Name" />
                                                <jet-input
                                                    name="store_name"
                                                    max-length="100"
                                                    id="store_name"
                                                    type="text"
                                                    v-model="form.store_name"
                                                    required
                                                />
                                                <jet-input-error :message="form.errors.store_name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="store_phone" value="Phone" />
                                                <jet-input
                                                    name="store_phone"
                                                    max-length="16"
                                                    id="store_phone"
                                                    type="text"
                                                    v-model="form.store_phone"
                                                    required
                                                    autocomplete="store_phone"
                                                />
                                                <jet-input-error :message="form.errors.store_phone" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
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
                                    </div>

                                    <div class="row">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <jet-label class="required" for="store_state" value="State" />
                                                <custom-select
                                                    id="type"
                                                    name="type"
                                                    v-model="form.store_state"
                                                    :options="app_states"
                                                    required
                                                />
                                                <jet-input-error :message="form.errors.store_state" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PRODUCTS CATERGORIES -->
                                    
                                    <p class="pb-1 mb-2 border-bottom text-muted text-uppercase">
                                        Product categories
                                    </p>
                                    <div class="form-group row justify-content-center border-bottom pb-2 mb-3">
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

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">
                                            SUBMIT
                                        </button>
                                    </div>

                                    <!-- [END] - PRODUCTS CATERGORIES -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import CustomSelect from "@/Components/CustomSelect"

export default {
    props: [
        'user_vendor',
        'app_states',
        'product_categories',
        'product_category_ids',
    ],

    components: {
        AppLayout,
        JetButton,
        JetInput,
        JetInputError,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        CustomSelect,
    },

    
    data() {
        return {
            jqueryValidator: null,
            processing: true,
            form: this.$inertia.form({
                store_name: this.user_vendor.name,
                store_phone: this.user_vendor.phone,
                store_address_line_1: this.user_vendor.address.line_1,
                store_address_line_2: this.user_vendor.address.line_2,
                store_city: this.user_vendor.address.city,
                store_state: this.user_vendor.address.state_id,
                product_categories: [],
            }),
        };
    },

    mounted() {
        window.initInputTransforms();
        window.initInputFilters();

        this.form.product_categories = [];

        for( const idKey in this.product_category_ids) {
            this.form.product_categories.push(this.product_category_ids[idKey]);
        }

        this.jqueryValidator = $(this.$refs.registerForm).validate(
            this.validationConfig()
        );
    },
    
    methods: {
        submit() {
            this.processing = true;

            this.form.post(this.route("profile.update_vendor_profile"), {
                preserveState: true,
                onFinish: () => {
                    this.processing = false;
                },
            });
        }, // [end] submit()

        validationConfig() {
            const vueObj = this;

            const nameValidation = {
                required: true,
                minlength: 3,
                maxlength: 25,
            };

            return {
                submitHandler() {
                    vueObj.$refs.registerForm.classList.add("was-validated");
                    vueObj.submit();
                },
                rules: {
                    store_name: { ...nameValidation },
                    store_phone: {
                        required: true,
                        digits: true,
                        minlength: 11,
                        maxlength: 16,
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
                    'product_categories[]': {
                        required: true,
                        rangelength: [1, vueObj.product_categories.length]
                    }
                },

                messages: {
                    'product_categories[]': 'Select at least one(1) product category',
                },
            };
        }, // [end] validationConfig()
    },
}
</script>