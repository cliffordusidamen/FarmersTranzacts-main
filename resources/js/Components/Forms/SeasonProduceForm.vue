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
    seasonProduce: {
        type: Object,
        default: () => ({}),
    },
})

const { seasonProduce } = toRefs(props)

const requiredMessage = "This field is required";
const qtyValidationRules = Yup.number().required(requiredMessage)
        .typeError('Enter a numeric value')
        .test(yupNumberTest())
        .min(1, "Enter a value greater 0")
        .max(999999999, "This should not be more than 999999999");

const rules = {
    name: Yup.string().required(requiredMessage).max(255, "This should not be more than 255 characters"),
    unit: Yup.string().required(requiredMessage).max(50, "This should not be more than 50 characters"),
    quantity_produced: qtyValidationRules.clone(),
    quantity_sold: qtyValidationRules.clone(),
    price_per_unit: Yup.number().required(requiredMessage)
        .typeError('Enter a numeric value')
        .test(yupNumberTest("Selling price(per unit) should be a numeric value."))
        .max(999999999, "This should not be more than 999999999"),
}

const data = ref({
    name: seasonProduce.value?.name || '',
    unit: seasonProduce.value?.unit || '',
    quantity: seasonProduce.value?.quantity || '',
    price_per_unit: seasonProduce.value?.price_per_unit || '',
});

const resetForm = () => {
    data.value = {
        name: '',
        unit: '',
        quantity: '',
        price_per_unit: '',
    }
}

const onSubmit = (validatedData) => {
    emits('onSubmit', {...validatedData})
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
        Season Produce
    </h4>
    <form @submit.prevent="submit">
        <div class="form-group">
            <jet-label for="name" class="mb-0" value="Name of item" />
            <div class="text-muted small">
                Example: Yam, Rice, Goat, Fish, etc.
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
                Example: Bags, Kg, Tons, Head etc.
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
            <jet-label for="quantity_produced" class="mb-0" value="Quantity produced/harvested" />
            <jet-input
                name="quantity_produced"
                type="number"
                min="0"
                max="9999999"
                id="quantity_produced"
                placeholder="Quantity produced/harvested"
                v-model="data.quantity_produced"
                @blur="() => validateField('quantity_produced')"
                @keyup="() => validateField('quantity_produced')"
            />
            <jet-input-error :message="errors?.quantity_produced || ''" />
        </div>

        <div class="form-group">
            <jet-label for="quantity_sold" class="mb-0" value="Quantity sold" />
            <jet-input
                name="quantity_sold"
                type="number"
                min="0"
                max="9999999"
                id="quantity_sold"
                placeholder="Quantity sold"
                v-model="data.quantity_sold"
                @blur="() => validateField('quantity_sold')"
                @keyup="() => validateField('quantity_sold')"
            />
            <jet-input-error :message="errors?.quantity_sold || ''" />
        </div>

        <div class="form-group">
            <jet-label for="price_per_unit" class="mb-0" value="Selling price(per unit)" />
            <div v-show="!!data.unit" class="text-muted small">
                Price of one(1) {{ data?.unit ?? 'Unit' }}
            </div>
            <jet-input
                name="price_per_unit"
                max="9999999"
                min="0"
                type="number"
                id="price_per_unit"
                placeholder="Selling price(per unit)"
                v-model="data.price_per_unit"
                @blur="() => validateField('price_per_unit')"
                @keyup="() => validateField('price_per_unit')"
            />
            <jet-input-error :message="errors?.price_per_unit || ''" />
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success text-uppercase">
                SUBMIT
            </button>
        </div>
    </form>
</template>