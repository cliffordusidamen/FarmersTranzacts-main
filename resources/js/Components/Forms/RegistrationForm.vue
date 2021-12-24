<template>
    <form ref="registerForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label
                        class="required"
                        for="first_name"
                        value="First Name"
                    />
                    <jet-input
                        name="first_name"
                        max-length="25"
                        id="first_name"
                        type="text"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="first_name"
                    />
                    <jet-input-error :message="form.errors.first_name" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label
                        class="required"
                        for="last_name"
                        value="Last Name"
                    />
                    <jet-input
                        name="last_name"
                        max-length="25"
                        id="last_name"
                        type="text"
                        v-model="form.last_name"
                        required
                        autofocus
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
                        required
                    />
                    <jet-input-error :message="form.errors.email" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="phone" value="Phone" />
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label
                        class="required"
                        for="password"
                        value="Password"
                    />
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
                    <jet-input-error
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </div>
        </div>

        <div
            class="form-group"
            v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
        >
            <div class="custom-control custom-checkbox">
                <jet-checkbox
                    name="terms"
                    id="terms"
                    v-model:checked="form.terms"
                />

                <label class="custom-control-label" for="terms">
                    I agree to the
                    <a target="_blank" :href="route('terms')"
                        >Terms of Service</a
                    >
                    and
                    <a target="_blank" :href="route('privacy_policy')"
                        >Privacy Policy</a
                    >
                </label>
                <jet-input-error :message="form.errors.terms" />
            </div>
        </div>

        <div class="mb-0">
            <div class="d-flex justify-content-center align-items-baseline">
                <inertia-link
                    :href="route('login')"
                    class="flex-fill text-muted mr-3 text-decoration-none"
                >
                    Already registered?
                </inertia-link>

                <jet-button
                    class="ml-4"
                    :class="{ 'text-white-50': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </jet-button>
            </div>
        </div>
    </form>
</template>


<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import { ref } from '@vue/reactivity';
import { useForm } from '@inertiajs/inertia-vue3';
import { onMounted } from '@vue/runtime-core';
import useInitIntlPhone from "@/composables/useInitIntlPhone"

export default {
    components: {
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetInputError,
    },

    setup() {
        const jqueryValidator = ref(null)
        const registerForm = ref(null)
        const phoneInput = ref(null)
        const telInput = ref(null)
        const { initIntlPhone } = useInitIntlPhone()

        const form = useForm({
            first_name: "",
            last_name: "",
            email: "",
            phoneInput: "",
            phone: "",
            password: "",
            password_confirmation: "",
            terms: false,
        })

        const submit = () => {
            form.phone = telInput.value.getNumber()
            form.post(route("register"), {
                onFinish: () => {
                    form.processing = false;
                    form.reset("password", "password_confirmation");
                },
                onSuccess: () => {
                    form.processing = false;
                    form.reset();
                },
                onError: () => {
                    form.processing = false;
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
						maxlength: 17,
                        intl_phone: true,
					},
                    password: {...passwordRules},
                    password_confirmation: {
                        ...passwordRules,
                        equalTo: "#password",
                    },
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
            jqueryValidator,
            form,
            submit,
            registerForm,
            phoneInput,
        }
    },
};
</script>

