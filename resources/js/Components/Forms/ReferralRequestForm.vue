<template>

    <form ref="referralForm" id="referralForm">
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
                    />
                    <jet-input-error :message="form.errors.last_name" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="age" value="Age" />
                    <jet-input
                        name="age"
                        max-length="25"
                        id="age"
                        type="number"
                        v-model="form.age"
                        min="18"
                        max="120"
                        data-cfilter-allow="alpha"
                    />
                    <jet-input-error :message="form.errors.age" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required" for="phone" value="Phone" />
                    <div>
                        <jet-input
                            name="phone"
                            id="phone"
                            max-length="15"
                            type="text"
                            v-model="form.phoneInput"
                        />

                        <div v-if="!!form?.errors?.phone" class="span d-block mt-2">
                            <jet-input-error :message="form.errors.phone" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="email" value="Email" />
                    <jet-input
                        name="email"
                        max-length="255"
                        id="email"
                        type="email"
                        v-model="form.email"
                        data-cfilter="email"
                    />
                    <jet-input-error :message="form.errors.email" />
                </div>
            </div>
        </div>

        
        <h4 class="text-center text-success border-bottom mb-3 pb-2 text-uppercase">
            Business information
        </h4>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="business_name" value="Business name(optional)" />
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
                    <jet-label class="required" for="address_line_1" value="Address Line 1" />
                    <jet-input
                        name="address_line_1"
                        max-length="50"
                        id="address_line_1"
                        type="text"
                        v-model="form.address_line_1"
                        required
                    />
                    <jet-input-error :message="form.errors.address_line_1" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="address_line_2" value="Address Line 2" />
                    <jet-input
                        name="address_line_2"
                        max-length="50"
                        id="address_line_2"
                        type="text"
                        v-model="form.address_line_2"
                    />
                    <jet-input-error :message="form.errors.address_line_2" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="city" value="City" />
                    <jet-input
                        name="city"
                        max-length="50"
                        id="city"
                        type="text"
                        v-model="form.city"
                    />
                    <jet-input-error :message="form.errors.city" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required" for="state_id" value="State" />
                    <custom-select
                        id="type"
                        name="state_id"
                        v-model="form.state_id"
                        :options="appStates"
                        required
                    />
                    <jet-input-error :message="form.errors.state_id" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required mb-0" for="farm_size" value="Farm size" />
                    <span class="d-block small">
                        Example: 3 Acres, 10 Plots, 300ft x 400ft
                    </span>
                    <jet-input
                        name="farm_size"
                        max-length="50"
                        id="farm_size"
                        type="text"
                        v-model="form.farm_size"
                    />
                    <jet-input-error :message="form.errors.farm_size" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="crops_grown" class="mb-0" value="Crops grown" />
                    <div class="text-muted small">
                        Example: casava, maize, potato, cocoa...
                    </div>
                    <jet-input
                        name="crops_grown"
                        max-length="50"
                        id="crops_grown"
                        type="text"
                        v-model="form.crops_grown"
                    />
                    <jet-input-error :message="form.errors.crops_grown" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="animals_reared" class="mb-0" value="Animals reared" />
                    <div class="text-muted small">
                        Example: goat, sheep, poultry...
                    </div>
                    <jet-input
                        name="animals_reared"
                        max-length="50"
                        id="animals_reared"
                        type="text"
                        v-model="form.animals_reared"
                    />
                    <jet-input-error :message="form.errors.animals_reared" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="seed_demand" class="mb-0" value="Seed demand" />
                    <span class="d-block small">
                        Quantity of seed required during planting season
                        e.g. Maize-3kg, Beans-4kg...
                    </span>
                    <jet-input
                        name="seed_demand"
                        max-length="50"
                        id="seed_demand"
                        type="text"
                        v-model="form.seed_demand"
                    />
                    <jet-input-error :message="form.errors.seed_demand" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="animal_feed_demand" class="mb-0" value="Animal-feed demand" />
                    <span class="d-block small">
                        Quantity of animal feed frequently purchased
                        e.g. Fish feed-30kg per month, Poultry feed-20kg per week...
                    </span>
                    <jet-input
                        name="animal_feed_demand"
                        max-length="50"
                        id="animal_feed_demand"
                        type="text"
                        v-model="form.animal_feed_demand"
                    />
                    <jet-input-error :message="form.errors.animal_feed_demand" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="fertilizer_demand" class="mb-0" value="Fertilizer demand" />
                    <span class="d-block small">
                        E.g. 4kg
                    </span>
                    <jet-input
                        name="fertilizer_demand"
                        max-length="50"
                        id="fertilizer_demand"
                        type="text"
                        v-model="form.fertilizer_demand"
                    />
                    <jet-input-error :message="form.errors.fertilizer_demand" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="number_of_workers" class="mb-0 required" value="Number of workers" />
                    <span class="d-block small">
                        That work directly on the farm
                    </span>
                    <jet-input
                        name="number_of_workers"
                        max-length="50"
                        id="number_of_workers"
                        type="number"
                        min="1"
                        v-model="form.number_of_workers"
                    />
                    <jet-input-error :message="form.errors.number_of_workers" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="agro_chemical_demand" class="mb-0" value="Agro-chemical demand" />
                    <span class="d-block small">
                        E.g. Pesticide- 4 Litres weekly, Herbicide - 50ml per season...
                    </span>
                    <jet-input
                        name="agro_chemical_demand"
                        max-length="50"
                        id="agro_chemical_demand"
                        type="text"
                        v-model="form.agro_chemical_demand"
                    />
                    <jet-input-error :message="form.errors.agro_chemical_demand" />
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="border rounded bg-light p-3 my-3">
                    <strong>NOTE:</strong> 
                    <ol>
                        <li>Ensure that you have searched in the list of already existing farmers before submitting this form.</li>

                        <li>You will receive commissions for only farmer details that have been verified and approved</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="mb-0 text-center">
            <jet-button
                class="ml-4 text-uppercase"
                :class="{ 'text-white-50': form.processing }"
                :disabled="form.processing"
            >
                submit
            </jet-button>
        </div>
    </form>

</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from '@vue/reactivity';
import { onMounted } from '@vue/runtime-core';

import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import CustomSelect from "@/Components/CustomSelect"
import useInitIntlPhone from "@/composables/useInitIntlPhone"

export default {
    props: {
        appStates: {
            type: Object,
            required: true,
        },

        referralRequest: {
            type: Object,
            default: (() => ({})),
        },
    },

    components: {
        CustomSelect,
        JetButton,
        JetCheckbox,
        JetInput,
        JetInputError,
        JetLabel,
        JetValidationErrors,
    },

    setup(props) {
        const { appStates, referralRequest } = props;

        const jqueryValidator = ref(null)
        const referralForm = ref(null)

        const phoneInput = ref(null)
        const telInput = ref(null)
        const { initIntlPhone } = useInitIntlPhone()

        const form = useForm({
            first_name: referralRequest?.first_name || "",
            last_name: referralRequest?.last_name || "",
            email: referralRequest?.email || "",
            phone: referralRequest?.phone || "",
            age: referralRequest?.age || "",
            phoneInput: referralRequest?.phone || "",
            business_name: referralRequest?.business_name || '',
            address_line_1: referralRequest?.address_line_1 || '',
            address_line_2: referralRequest?.address_line_2 || '',
            city: referralRequest?.city || '',
            state_id: referralRequest?.state_id || '',
            crops_grown: referralRequest?.crops_grown || '',
            animals_reared: referralRequest?.animals_reared || '',
            farm_size: referralRequest?.farm_size || '',
            seed_demand: referralRequest?.seed_demand || '',
            animal_feed_demand: referralRequest?.animal_feed_demand || '',
            fertilizer_demand: referralRequest?.fertilizer_demand || '',
            agro_chemical_demand: referralRequest?.agro_chemical_demand || '',
            number_of_workers: referralRequest?.number_of_workers || '',
        })

        const submit = () => {
            form.phone = telInput.value.getNumber()
            const tartgetRoute = !referralRequest?.id
                ? route("referrals.store")
                : route("referrals.update", {id: referralRequest.id})

            form.put(tartgetRoute, {
                preserveState: true,
                onFinish: () => {
                    form.processing = false;
                },
                onSuccess: () => {
                    form.reset();
                    jqueryValidator.value.resetForm();
                    $('#referralForm').removeClass('was-validated');
                },
            });
        }

        $.validator.addMethod( "person_name", function( value, element ) {
            return !/[^a-z]/gi.test( value );
        }, "Enter letters only" );

        const validationConfig = () => {

            const nameValidation = {
                person_name: true,
                required: true,
                minlength: 3,
                maxlength: 25,
            };

            return {
                submitHandler() {
                    referralForm.value.classList.add("was-validated");
                    submit();
                },
                rules: {
                    first_name: { ...nameValidation },
                    last_name: { ...nameValidation },
                    age: {
                        digits: true,
                        max: 99,
                    },
                    email: {
                        email: true,
                        maxlength: 255,
                    },
                    phone: {
                        required: true,
						intl_phone: true,
						maxlength: 17,
					},

                    business_name: {
                        required: false,
                        minlength: 3,
                        maxlength: 255,
                    },

                    address_line_1: {
                        required: true,
                        minlength: 5,
                    },
                    address_line_2: {
                        required: false,
                        minlength: 5,
                    },
                    city: {
                        required: false,
                        minlength: 3,
                    },
                    state_id: 'required',

                    crops_grown: {
                        required: () => {
                            return $('#animals_reared').val().trim().length == 0
                        },
                        minlength: 3,
                    },
                    animals_reared: {
                        required:  () => {
                            return $('#crops_grown').val().trim().length == 0
                        },
                        minlength: 3,
                    },
                    farm_size: {
                        required: true,
                        minlength: 3,
                    },

                    seed_demand: {
                        minlength: 3,
                    },
                    animal_feed_demand: {
                        minlength: 3,
                    },
                    fertilizer_demand: {
                        minlength: 3,
                    },
                    agro_chemical_demand: {
                        minlength: 3,
                    },
                    number_of_workers: {
                        required: true,
                        digits: true,
                        min: 1,
                    },
                },
            };
        }
        
        onMounted(() => {
            initIntlPhone(phoneInput, telInput, 'phone')
            jqueryValidator.value = $(referralForm.value).validate(
                validationConfig()
            );
        })

        return {
            form,
            referralForm,
            jqueryValidator,
            appStates,
        }
    },
};
</script>
