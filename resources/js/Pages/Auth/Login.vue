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
                        class="nav-link active"
                        id="signin-tab-2"
                        data-toggle="tab"
                        href="#signin-2"
                        role="tab"
                        aria-controls="signin-2"
                        aria-selected="false"
                        >Sign In</a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        id="register-tab-2"
                        :href="route('register')"
                        >Register</a
                    >
                </li>
            </ul>
            <div class="tab-content px-4 mx-4 mb-4">
                <div
                    class="tab-pane fade show active"
                    id="signin-2"
                    role="tabpanel"
                    aria-labelledby="signin-tab-2"
                >
                    <login-form />
                </div>
                <div class="tab-pane fade" id="register-2" role="tabpanel" aria-labelledby="register-tab-2">
                </div>
            </div>
        </div>
    </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import LoginForm from '@/Components/Forms/LoginForm.vue'

export default {
    components: {
        JetAuthenticationCard,
        JetValidationErrors,
        LoginForm,
    },
};
</script>
