<template>

    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="border rounded bg-light p-3 my-3">
                <strong>NOTE:</strong> 
                <ol>
                    <li>
                        Ensure that you have
                        <inertia-link class="text-primary" :href="route('referrals.search')">searched in the list</inertia-link>
                        already registered farmers of already existing farmers
                        before submitting this form.
                    </li>

                    <li>You will receive commissions for only farmer details that have been verified and approved</li>
                </ol>
            </div>
        </div>
    </div>
    <form ref="referralForm" id="referralForm" class="px-4">
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
                        @change="handleStateChange"
                        required
                    />
                    <jet-input-error :message="form.errors.state_id" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required" for="local_government_area_id" value="Local Government Area" />
                    <custom-select
                        id="type"
                        name="local_government_area_id"
                        v-model="form.local_government_area_id"
                        :options="selectedStateLgas"
                        required
                    />
                    <jet-input-error :message="form.errors.local_government_area_id" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="ward" value="Ward" />
                    <jet-input
                        name="ward"
                        max-length="50"
                        id="ward"
                        type="text"
                        v-model="form.ward"
                        autocomplete="ward"
                    />
                    <jet-input-error :message="form.errors.ward" />
                </div>
            </div>
        </div>

        <h4 class="text-center text-success border-bottom mb-3 pb-2 text-uppercase">
            Last season record
        </h4>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required" for="season" value="Year" />
                    <custom-select
                        id="type"
                        name="year"
                        v-model="form.year"
                        :options="years"
                        required
                    />
                    <jet-input-error :message="form.errors.year" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label class="required" for="season" value="Season" />
                    <custom-select
                        id="type"
                        name="season"
                        v-model="form.season"
                        :options="seasons"
                        required
                    />
                    <jet-input-error :message="form.errors.season" />
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
                    <jet-label for="seed_input" class="mb-0" value="Seeds used" />
                    <span class="d-block small">
                        Quantity of seeds used for the season
                        <br />
                        e.g. Maize-3kg, Beans-4kg...
                    </span>
                    <jet-input
                        name="seed_input"
                        max-length="50"
                        id="seed_input"
                        type="text"
                        v-model="form.seed_input"
                    />
                    <jet-input-error :message="form.errors.seed_input" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="animal_feed_input" class="mb-0" value="Animal-feed used" />
                    <span class="d-block small">
                        Quantity of animal feed used for the season
                        <br />
                        e.g. Fish feed-30kg, Poultry feed-10 bags...
                    </span>
                    <jet-input
                        name="animal_feed_input"
                        max-length="50"
                        id="animal_feed_input"
                        type="text"
                        v-model="form.animal_feed_input"
                    />
                    <jet-input-error :message="form.errors.animal_feed_input" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="fertilizer_bags" class="mb-0" value="Fertilizer used(Bags)" />

                    <jet-input
                        name="fertilizer_bags"
                        max-length="50"
                        id="fertilizer_bags"
                        type="number"
                        v-model="form.fertilizer_bags"
                    />
                    <jet-input-error :message="form.errors.fertilizer_bags" />
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
                    <jet-label for="agro_chemical_input" class="mb-0" value="Agro-chemical used" />
                    <span class="d-block small">
                        E.g. Pesticide- 4 Litres, Herbicide - 50litres
                    </span>
                    <jet-input
                        name="agro_chemical_input"
                        max-length="50"
                        id="agro_chemical_input"
                        type="text"
                        v-model="form.agro_chemical_input"
                    />
                    <jet-input-error :message="form.errors.agro_chemical_input" />
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="crops_harvest" class="mb-0" value="Crops harvest" />
                    <div class="text-muted small">
                        Example: casava - 4bags, maize - 400tons, potato - 303kg...
                    </div>
                    <jet-textarea
                        name="crops_harvest"
                        max-length="500"
                        id="crops_harvest"
                        v-model="form.crops_harvest"
                    />
                    <jet-input-error :message="form.errors.crops_harvest" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="animals_harvest" class="mb-0" value="Animals harvest" />
                    <div class="text-muted small">
                        Example: goat - 190, sheep - 67, poultry-1100...
                    </div>
                    <jet-textarea
                        name="animals_harvest"
                        max-length="500"
                        id="animals_harvest"
                        v-model="form.animals_harvest"
                    />
                    <jet-input-error :message="form.errors.animals_harvest" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="crops_sale" class="mb-0" value="Crops sale" />
                    <div class="text-muted small">
                        Example: casava - N4,000 per bag, maize - N2,000 per ton, potato - N100 per kg...
                    </div>
                    <jet-textarea
                        name="crops_sale"
                        max-length="500"
                        id="crops_sale"
                        v-model="form.crops_sale"
                    />
                    <jet-input-error :message="form.errors.crops_sale" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="animals_sale" class="mb-0" value="Animals sale" />
                    <div class="text-muted small">
                        Example: N5000 per goat, N100 per sheep, N200 per poultry...
                    </div>
                    <jet-textarea
                        name="animals_sale"
                        max-length="500"
                        id="animals_sale"
                        v-model="form.animals_sale"
                    />
                    <jet-input-error :message="form.errors.animals_sale" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="workers_pay" class="mb-0" value="Workers pay" />
                    <div class="text-muted small">
                        Example: N500 per day, 3 people N100 per week
                    </div>
                    <jet-textarea
                        name="workers_pay"
                        max-length="500"
                        id="workers_pay"
                        v-model="form.workers_pay"
                    />
                    <jet-input-error :message="form.errors.workers_pay" />
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="input_supply_prices" class="mb-0" value="Input supplies prices" />
                    <span class="d-block small">
                        E.g. Pesticide- N4000 per Litres, Fertilizer - N500 per bag, Maize - N500 per bag
                    </span>
                    <jet-textarea
                        name="input_supply_prices"
                        max-length="500"
                        id="input_supply_prices"
                        v-model="form.input_supply_prices"
                    />
                    <jet-input-error :message="form.errors.input_supply_prices" />
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="form-group">
                    <jet-label for="season_notes" class="mb-0" value="Season notes" />
                    <div class="text-muted small">
                        What are the challenges, opportunities, successes, and notable experiences of the season?
                    </div>
                    <jet-textarea
                        name="season_notes"
                        max-length="5000"
                        id="season_notes"
                        v-model="form.season_notes"
                    />
                    <jet-input-error :message="form.errors.season_notes" />
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
import JetTextarea from "@/Jetstream/Textarea";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import CustomSelect from "@/Components/CustomSelect"
import useInitIntlPhone from "@/composables/useInitIntlPhone"
import useStateLgaSelection from "@/composables/useStateLgaSelection"

export default {
    props: {
        appStates: {
            type: Object,
            required: true,
        },

        stateLgas: {
            type: Object,
            required: true,
        },

        referralRequest: {
            type: Object,
            default: (() => ({})),
        },

        seasons: {
            type: Object,
            default: (() => ({})),
        },

        years: {
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
        JetTextarea,
        JetValidationErrors,
    },

    setup(props) {
        const { appStates, referralRequest, seasons, years, stateLgas } = props;

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
            local_government_area_id: referralRequest?.local_government_area_id || '',
            ward: referralRequest?.ward || '',
            crops_harvest: referralRequest?.crops_harvest || '',
            animals_harvest: referralRequest?.animals_harvest || '',
            farm_size: referralRequest?.farm_size || '',
            seed_input: referralRequest?.seed_input || '',
            animal_feed_input: referralRequest?.animal_feed_input || '',
            fertilizer_bags: referralRequest?.fertilizer_bags || '',
            agro_chemical_input: referralRequest?.agro_chemical_input || '',
            number_of_workers: referralRequest?.number_of_workers || '',
            crops_sale: referralRequest?.crops_sale || '',
            animals_sale: referralRequest?.animals_sale || '',
            season: referralRequest?.season || '',
            year: referralRequest?.year || '',
            workers_pay: referralRequest?.workers_pay || '',
            input_supply_prices: referralRequest?.input_supply_prices || '',
            season_notes: referralRequest?.season_notes || '',
        })

        const setLga = (lga_id) => {
            form.local_government_area_id = lga_id ?? '';
        }

        const  {
            selectedStateLgas,
            updateSelectedStateLgas,
            handleStateChange,
        } = useStateLgaSelection(stateLgas, setLga)

        const submit = () => {
            form.phone = telInput.value.getNumber()
            const tartgetRoute = !referralRequest?.id
                ? route("referrals.store")
                : route("referrals.update", {id: referralRequest.id})

            const tartgetMethod = !referralRequest?.id ? 'post' : 'put'

            form[tartgetMethod](tartgetRoute, {
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
                    local_government_area_id: 'required',
                    ward: {
                        maxlength: 50,
                    },
                    season: 'required',
                    year: 'required',

                    crops_harvest: {
                        required: () => {
                            return $('#animals_harvest').val().trim().length == 0
                        },
                        minlength: 3,
                    },
                    animals_harvest: {
                        required:  () => {
                            return $('#crops_harvest').val().trim().length == 0
                        },
                        minlength: 3,
                    },
                    farm_size: {
                        required: true,
                        minlength: 3,
                    },

                    seed_input: {
                        minlength: 3,
                    },
                    animal_feed_input: {
                        minlength: 3,
                    },
                    workers_pay: {
                        minlength: 3,
                    },
                    input_supply_prices: {
                        minlength: 3,
                    },
                    fertilizer_bags: {
                        max: 9999999,
                    },
                    agro_chemical_input: {
                        minlength: 3,
                    },
                    number_of_workers: {
                        required: true,
                        digits: true,
                        min: 1,
                    },

                    crops_sale: {
                        required: () => {
                            return $('#animals_sale').val().trim().length == 0
                        },
                        minlength: 3,
                    },
                    animals_sale: {
                        required:  () => {
                            return $('#crops_sale').val().trim().length == 0
                        },
                        minlength: 3,
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
            selectedStateLgas,
            updateSelectedStateLgas,
            handleStateChange,
        }
    },
};
</script>
