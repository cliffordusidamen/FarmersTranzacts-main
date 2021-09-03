<template>
    <jet-authentication-card>
        <jet-validation-errors class="mb-3" />

        <div
            v-if="status"
            class="alert alert-success mb-3 rounded-0"
            role="alert"
        >
            {{ status }}
        </div>

        <div class="form-tab">
            <ul class="nav nav-pills nav-fill" role="tablist">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        id="signin-tab-2"
                        :href="route('login')"
                        >Sign In</a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link active"
                        id="register-tab-2"
                        data-toggle="tab"
                        href="#register-2"
                        role="tab"
                        aria-controls="register-tab-2"
                        aria-selected="false"
                        >Register</a
                    >
                </li>
            </ul>
            <div class="tab-content px-4 mx-4 mb-4">
                <div class="tab-pane fade show active" id="register-2" role="tabpanel" aria-labelledby="register-tab-2">
                    <registration-form />
                </div>
            </div>
        </div>
    </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard"
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo"
import JetValidationErrors from "@/Jetstream/ValidationErrors"
import RegistrationForm from '@/Components/Forms/RegistrationForm.vue'

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetValidationErrors,
        RegistrationForm,
    },

};
</script>
