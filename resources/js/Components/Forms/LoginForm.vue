<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <jet-label for="email" value="Email" />
            <jet-input
                id="email"
                type="email"
                v-model="form.email"
                required
                autofocus
            />
        </div>

        <div class="form-group">
            <jet-label for="password" value="Password" />
            <jet-input
                id="password"
                type="password"
                v-model="form.password"
                required
                autocomplete="current-password"
            />
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <jet-checkbox
                    id="remember_me"
                    name="remember"
                    v-model:checked="form.remember"
                />

                <label class="custom-control-label" for="remember_me">
                    Remember Me
                </label>
            </div>
        </div>

        <div class="mb-0 border-top pt-1">
            <div class="d-flex justify-content-center align-items-baseline">
                <inertia-link
                    :href="route('password.request')"
                    class="flex-fill text-primary text-muted mr-3"
                >
                    Forgot your password?
                </inertia-link>

                <jet-button
                    class="ml-4"
                    :class="{ 'text-white-50': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </jet-button>

            </div>
            <div class="d-block mt-2 text-center">
                Don't have an account?
                <a
                    class="ml-3"
                    id="register-tab-2"
                    :href="route('register')"
                    >Register Now</a
                >
            </div>
        </div>
    </form>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import { useForm, usePage } from "@inertiajs/inertia-vue3";

export default {
    components: {
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
    },

    setup() {
        const {status} = usePage().props;
        const form = useForm({
            email: "",
            password: "",
            remember: false,
            as_designation: 'shopper',
        });

        const submit = () => {
            form.transform((data) => ({
                ...data,
                remember: form.remember ? "on" : "",
            })).post(route("login"), {
                onFinish: () => form.reset("password"),
            });
        };

        return {
            form,
            submit,
            status,
        };
    },
};
</script>
