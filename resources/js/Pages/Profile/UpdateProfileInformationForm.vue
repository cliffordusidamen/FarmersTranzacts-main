<template>
    <profile-section>
        <template #title> Profile Information </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <form ref="profileUpdateForm">
            <div class="card shadow-sm">
                <div class="card-body">
                    <jet-action-message :on="form.recentlySuccessful">
                        Saved.
                    </jet-action-message>

                    <!-- Profile Photo -->
                    <div
                        class="form-group mt-2 mb-2"
                        v-if="$page.props.jetstream.managesProfilePhotos"
                    >
                        <!-- Profile Photo File Input -->
                        <input
                            type="file"
                            hidden
                            name="photo"
                            ref="photo"
                            @change="updatePhotoPreview"
                        />

                        <jet-label for="photo" value="Photo" class="sronly" />

                        <!-- Current Profile Photo -->
                        <div class="mt-2" v-show="!photoPreview">
                            <img
                                :src="user.profile_photo_url"
                                alt="Current Profile Photo"
                                class="rounded-circle"
                                style="width: 80px; height: 80px;"
                            />
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div class="mt-2" v-show="photoPreview">
                            <img
                                :src="photoPreview"
                                class="rounded-circle"
                                alt="Photo"
                                style="width: 80px; height: 80px;"
                            />
                        </div>

                        <jet-secondary-button
                            class="mt-2 me-2"
                            type="button"
                            @click.prevent="selectNewPhoto"
                        >
                            Select A New Photo
                        </jet-secondary-button>

                        <jet-secondary-button
                            type="button"
                            class="mt-2"
                            @click.prevent="deletePhoto"
                            v-if="user.profile_photo_path"
                        >
                            Remove Photo
                        </jet-secondary-button>

                        <jet-input-error :message="form.errors.photo" class="mt-2" />
                    </div>

                    <div class="">

                        <!-- First Name -->
                        <div class="form-group row justify-content-center mb-3 align-items-start">
                            <jet-label
                                for="first_name"
                                value="First Name"
                                class="col-sm-5 text-sm-right required col-form-label"
                            />
                            <div class="col-sm-6">
                                <jet-input
                                    id="first_name"
                                    name="first_name"
                                    type="text"
                                    v-model="form.first_name"
                                    :class="{ 'is-invalid': form.errors.first_name }"
                                    autocomplete="first_name"
                                    data-cfilter="name"
                                    data-ctransform="capitalize"
                                />
                                <jet-input-error :message="form.errors.first_name" />
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group row justify-content-center mb-3 align-items-start">
                            <jet-label
                                for="last_name"
                                value="Last Name"
                                class="col-sm-5 text-sm-right required col-form-label"
                            />
                            <div class="col-sm-6">
                                <jet-input
                                    id="last_name"
                                    name="last_name"
                                    type="text"
                                    v-model="form.last_name"
                                    :class="{ 'is-invalid': form.errors.last_name }"
                                    autocomplete="last_name"
                                    data-cfilter="name"
                                    data-ctransform="capitalize"
                                />
                                <jet-input-error :message="form.errors.last_name" />
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row justify-content-center mb-3 align-items-start">
                            <jet-label
                                for="email"
                                value="Email"
                                class="col-sm-5 text-sm-right required col-form-label"
                            />
                            <div class="col-sm-6">
                                <jet-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    data-cfilter="email"
                                    v-model="form.email"
                                    data-ctransform="lowercase"
                                    :class="{ 'is-invalid': form.errors.email }"
                                />
                                <jet-input-error :message="form.errors.email" />
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="form-group row justify-content-center mb-3 align-items-start">
                            <jet-label
                                for="phone"
                                value="Phone"
                                class="col-sm-5 text-sm-right col-form-label"
                            />
                            <div class="col-sm-6">
                                <jet-input
                                    id="phone"
                                    name="phone"
                                    type="phone"
                                    data-cfilter="digits"
                                    v-model="form.phoneIntl"
                                    :class="{ 'is-invalid': form.errors.phone }"
                                />
                                <jet-input-error :message="form.errors.phone" />
                            </div>
                        </div>
                    </div>

                    <div class="border-top py-4 mt-3 d-flex justify-content-end">
                        <jet-button
                            :class="{ 'text-white-50': form.processing }"
                            :disabled="form.processing"
                            context-class="btn-primary"
                        >
                            Save
                        </jet-button>
                    </div>
                </div>
            </div>
         </form>
    </profile-section>
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
import useInitIntlPhone from "@/composables/useInitIntlPhone"
import { ref } from '@vue/reactivity';
import { Inertia } from '@inertiajs/inertia';
import { onMounted } from '@vue/runtime-core';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    components: {
        JetActionMessage,
        JetButton,
        ProfileSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        CustomSelect,
    },
    props: ["user"],

    setup(props) {
        const form = useForm({
            _method: "PUT",
            first_name: props.user.first_name || '',
            last_name: props.user.last_name || '',
            email: props.user.email || '',
            phone: props.user.phone || '',
            phoneIntl: props.user.phone || '',
            photo: null,
        })
        const photo = ref(null)
        const photoPreview = ref(null)
        const profileUpdateForm = ref(null)
        const jqueryValidator = ref(null)
        const phoneInput = ref(null)
        const telInput = ref(null)
        const { initIntlPhone } = useInitIntlPhone()

        const updateProfileInformation = () => {
            if (photo.value) {
                form.photo = photo.value.files[0];
            }

            form.phone = telInput.value.getNumber()

            form.post(route("user-profile-information.update"), {
                errorBag: "updateProfileInformation",
                preserveScroll: true,
            });
        }

        const selectNewPhoto = () => {
            photo.value.click();
        }

        const updatePhotoPreview = () => {
            const reader = new FileReader();
            reader.onload = (e) => {
                photoPreview.value = e.target.result;
            };
            reader.readAsDataURL(photo.value.files[0]);
        }

        const deletePhoto = () => {
            Inertia.delete(route("current-user-photo.destroy"), {
                preserveScroll: true,
                onSuccess: () => (photoPreview.value = null),
            });
        }

        const validationConfig = () => {
            const nameValidation = {
                required: true,
                minlength: 3,
                maxlength: 25,
            };

            return {
                submitHandler() {
                    updateProfileInformation();
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
                },
            };
        }

        onMounted(() => {
            initIntlPhone(phoneInput, telInput, 'phone')
            jqueryValidator.value = $(profileUpdateForm.value).validate(
                validationConfig()
            );
        })

        return {
            form,
            profileUpdateForm,
            photo,
            photoPreview,
            jqueryValidator,
            selectNewPhoto,
            updatePhotoPreview,
            deletePhoto,
        }

    },
};
</script>