<template>

    <form ref="referralForm" id="referralForm" class="px-4">

        <h4 class="text-center text-success border-bottom mb-3 pb-2 text-uppercase">
            Farm season information
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

export default {
    props: {

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
        const { referralRequest, seasons, years } = props;

        const jqueryValidator = ref(null)
        const referralForm = ref(null)

        const form = useForm({
            season: '',
            year: '',
            crops_harvest: '',
            animals_harvest: '',
            farm_size: '',
            seed_input: '',
            animal_feed_input: '',
            fertilizer_bags: '',
            agro_chemical_input: '',
            number_of_workers: '',
            crops_sale: '',
            animals_sale: '',
            workers_pay: '',
            input_supply_prices: '',
            season_notes: '',
        })

        const submit = () => {
            const tartgetRoute = route("referrals.store_season", {id: referralRequest.id})
            const tartgetMethod = 'post'

            form[tartgetMethod](tartgetRoute, {
                preserveState: true,
                onFinish: () => {
                    form.processing = false;
                },
            });
        }


        const validationConfig = () => {

            return {
                submitHandler() {
                    referralForm.value.classList.add("was-validated");
                    submit();
                },

                rules: {
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
            jqueryValidator.value = $(referralForm.value).validate(
                validationConfig()
            );
        })

        return {
            form,
            referralForm,
            jqueryValidator,
        }
    },
};
</script>
