import { computed, ref } from 'vue';
import * as Yup from 'yup';

import scrollToFieldError from './useScrollToError';

export default function useYupFormValidation(
    rules,
    dataRef,
    successCallback
) {

    const schema = Yup.object().shape(rules)

    const errors = ref({})
    const hasErrors = computed(() => Object.keys(errors.value).length > 0)

    const validateField = (field) => {
        if (undefined == rules[field]) {
            return;
        }

        schema
            .validateAt(field, dataRef.value, { stripUnknown: true })
            .then(() => {
                errors.value[field] = "";
            })
            .catch(err => {
                errors.value[field] = err.message;
            });
    }

    const validateForm = () => {
        schema
            .validate(dataRef.value, { abortEarly: false })
            .then(() => {
                errors.value = {};
                successCallback(dataRef.value)
            })
            .catch(err => {
                err.inner.forEach(error => {
                    errors.value[error.path] = error.message;
                });
            })
            .then(() => scrollToFieldError());
    }

    return {
        errors,
        hasErrors,
        schema,
        validateField,
        validateForm,
    }
}