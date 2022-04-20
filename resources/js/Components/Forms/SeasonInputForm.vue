<script setup>
import { ref, toRefs } from "@vue/reactivity";
import * as Yup from "yup";

import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import useYupFormValidation from '@/composables/useYupFormValidation';
import yupNumberTest from '@/util/yupNumberTest';

const emits = defineEmits([
    "onSubmit",
]);

const props = defineProps({
    seasonInput: {
        type: Object,
        default: () => ({}),
    },
})

const { seasonInput } = toRefs(props)

const requiredMessage = "This field is required";
const rules = {
    name: Yup.string().required(requiredMessage).max(255, "This should not be more than 255 characters"),
    unit: Yup.string().required(requiredMessage).max(50, "This should not be more than 50 characters"),
    quantity: Yup.number().required(requiredMessage)
        .typeError('Enter a numeric value')
        .test(yupNumberTest())
        .min(1, "Enter a value greater 0")
        .max(999999999, "This should not be more than 999999999"),
    cost_per_unit: Yup.number().required(requiredMessage)
        .typeError('Enter a numeric value')
        .test(yupNumberTest("Cost per unit should be a numeric value."))
        .max(999999999, "This should not be more than 999999999"),
}

const data = ref({
    name: seasonInput.value?.name || '',
    unit: seasonInput.value?.unit || '',
    quantity: seasonInput.value?.quantity || '',
    cost_per_unit: seasonInput.value?.cost_per_unit || '',
});

const resetForm = () => {
    data.value = {
        name: '',
        unit: '',
        quantity: '',
        cost_per_unit: '',
    }
}

const onSubmit = (validatedData) => {
    let emitData = {...validatedData}
    if (!!props.seasonInput.id) {
        emitData['id'] = props.seasonInput.id;
    }
    emits('onSubmit', emitData)
    resetForm()
}

const {
    errors,
    hasErrors,
    validateField,
    validateForm,
} = useYupFormValidation(
    rules,
    data,
    (validatedData) => onSubmit(validatedData)
)

const submit = (event) => {
    event.preventDefault();

    validateForm()
}

</script>

<template>

    <h4 class="text-center text-uppercase text-success border-bottom pb-2 mb-2">
        Season Input
    </h4>
    <form @submit.prevent="submit">
        <div class="form-group">
            <jet-label for="name" class="mb-0" value="Name of item" />
            <div class="text-muted small">
                Example: Fertilizer, Herbicide, Seeds, etc.
            </div>
            <jet-input
                name="name"
                maxlength="255"
                id="name"
                placeholder="Name of item"
                v-model="data.name"
                @blur="() => validateField('name')"
                @keyup="() => validateField('name')"
            />
            <jet-input-error :message="errors?.name || ''" />
        </div>

        <div class="form-group">
            <jet-label for="unit" class="mb-0" value="Unit of measurement" />
            <div class="text-muted small">
                Example: Bags, Kg, Liters, Head etc.
            </div>
            <jet-input
                name="unit"
                maxlength="50"
                max="9999999"
                min="0"
                id="unit"
                placeholder="Unit of measurement"
                v-model="data.unit"
                @blur="() => validateField('unit')"
                @keyup="() => validateField('unit')"
            />
            <jet-input-error :message="errors?.unit || ''" />
        </div>

        <div class="form-group">
            <jet-label for="quantity" class="mb-0" value="Quantity used" />
            <jet-input
                name="quantity"
                maxlength="255"
                id="quantity"
                placeholder="Quantity used"
                v-model="data.quantity"
                @blur="() => validateField('quantity')"
                @keyup="() => validateField('quantity')"
            />
            <jet-input-error :message="errors?.quantity || ''" />
        </div>

        <div class="form-group">
            <jet-label for="cost_per_unit" class="mb-0" value="Cost per unit" />
            <div v-show="!!data.unit" class="text-muted small">
                Price of one(1) {{ data?.unit ?? 'Unit' }}
            </div>
            <jet-input
                name="cost_per_unit"
                max="9999999"
                min="0"
                type="number"
                id="cost_per_unit"
                placeholder="Cost per unit"
                v-model="data.cost_per_unit"
                @blur="() => validateField('cost_per_unit')"
                @keyup="() => validateField('cost_per_unit')"
            />
            <jet-input-error :message="errors?.cost_per_unit || ''" />
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success text-uppercase">
                SUBMIT
            </button>
        </div>
    </form>
</template>