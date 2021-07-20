<template>
    <app-layout>
        <div class="m-0 py-4 bg-light">
            <profile-section>
                <template #title> Vendor Details </template>

                <template #description>
                    Update your business's profile.
                </template>

                <template #default>
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

                                

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary" :disabled="processing">
                                        SUBMIT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </template>
            </profile-section>
        </div>
    </app-layout>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import ProfileSection from "@/Components/ProfileSection";
import CustomSelect from "@/Components/CustomSelect";
import AppLayout from "@/Layouts/AppLayout"

export default {
    props: ["user_vendor", "app_states"],

    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        ProfileSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        CustomSelect,
    },

    data() {
        return {
            jqueryValidator: null,
            processing: false,
            form: this.$inertia.form({
                store_name: this.user_vendor.name,
                store_phone: this.user_vendor.phone,
                store_address_line_1: this.user_vendor.address.line_1,
                store_address_line_2: this.user_vendor.address.line_2,
                store_city: this.user_vendor.address.city,
                store_state: this.user_vendor.address.state_id,
            }),
        };
    },

    mounted() {
        window.initInputTransforms();
        window.initInputFilters();

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
                },
            };
        }, // [end] validationConfig()
    },
}
</script>