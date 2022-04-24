<script setup>
import { useForm, usePage } from '@inertiajs/inertia-vue3';
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
import useScrollToError from '@/composables/useScrollToError';
import ManageSeasonInputs from "@/Components/ManageSeasonInputs";
import ManageSeasonProduce from "@/Components/ManageSeasonProduce";

const props = defineProps({
    referralRequest: {
        type: Object,
        required: true,
    },
    farmSeason: {
        type: Object,
        default: () => ({}),
    },
    seasons: {
        type: Object,
        default: () => ({}),
    },

    years: {
        type: Object,
        default: () => ({}),
    },
});

const { scrollToFieldError } = useScrollToError('#seasonForm')


const showSeasonInputError = ref(false)
const seasonInputs = ref([]);
const handleSeasonInputsChanged = (newSeasonInputs) => {
    showSeasonInputError.value = !newSeasonInputs.length;
    seasonInputs.value = [...newSeasonInputs];
    form.errors.farm_season_inputs = !!newSeasonInputs.length ? [] : null;
}


const showSeasonProduceError = ref(false)
const seasonProduces = ref([]);
const handleSeasonProducesChanged = (newSeasonProduces) => {
    showSeasonProduceError.value = !newSeasonProduces.length;
    seasonProduces.value = [...newSeasonProduces];
    form.errors.farm_season_inputs = !!newSeasonProduces.length ? [] : null;
}

const jqueryValidator = ref(null)
const seasonForm = ref(null)

const form = useForm({
    farm_size: props.farmSeason?.farm_size || '',
    number_of_workers: props.farmSeason?.number_of_workers || '',
    season: props.farmSeason?.season || '',
    year: props.farmSeason?.year || '',
    workers_pay: props.farmSeason?.workers_pay || '',
    farm_season_inputs: [],
    farm_season_produces: [],
})

const submit = () => {
    form.farm_season_inputs = [...seasonInputs.value]
    form.farm_season_produces = [...seasonProduces.value]

    const tartgetRoute = route("referrals.store_season", {id: props.referralRequest.id})

    form.post(tartgetRoute, {
        preserveState: true,
        onFinish: () => {
            form.processing = false;
        },
        onSuccess: () => {
            form.reset();
            jqueryValidator.value.resetForm();
            $('#seasonForm').removeClass('was-validated');
        },
    });
}

const validationConfig = () => {

    return {
        submitHandler() {
            showSeasonInputError.value = false
            seasonForm.value.classList.add("was-validated");
            const noInputOrProducSet = !seasonInputs.value.length
                || !seasonProduces.value.length
            if (!seasonInputs.value.length) {
                showSeasonInputError.value = true
            }
            if (!seasonProduces.value.length) {
                showSeasonProduceError.value = true
            }

            if (noInputOrProducSet) {
                return scrollToFieldError();
            }
            submit();
        },
        rules: {
            season: 'required',
            year: 'required',

            farm_size: {
                required: true,
                minlength: 3,
            },

            workers_pay: {
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
    jqueryValidator.value = $(seasonForm.value).validate(
        validationConfig()
    );
});


</script>

<template>

    <form ref="seasonForm" id="seasonForm" class="px-4">
        
        <h4 class="text-center text-success border-bottom text-uppercase">
            Farm season information
        </h4>
        <div class="mx-4 text-center text-muted mt-n4 mb-3">
            Information of the most recent season
        </div>

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

        </div>

        <div class="alert alert-warning mb-2" v-show="showSeasonInputError">
            Enter at least one(1) season input!
        </div>
        <manage-season-inputs
            @season-inputs-changed="handleSeasonInputsChanged"
            :form="form"
        />


        <div class="alert alert-warning mb-2" v-show="showSeasonProduceError">
            Enter at least one(1) season produce!
        </div>
        <manage-season-produce
            @season-produces-changed="handleSeasonProducesChanged"
            :form="form"
        />


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
