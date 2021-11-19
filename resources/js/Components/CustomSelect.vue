<template>
    <select
        v-model="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="select"
        :class="{
            'form-control': true,
            ...classes,
        }"
    >
        <option :selected="modelValueEmpty" value="">{{ placeholder }}</option>
        <option v-for="key in Object.keys(options)" :value="key" :key="key" :selected="!modelValueEmpty && modelValue == options[key]">{{ options[key] }}</option>
    </select>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: String,
            default: '',
        },
        options: {
            type: Object,
            required: true,
        },
        placeholder: {
            type: String,
            default: 'Select one',
        },
        classes: {
            type: Object,
            default: () => ({}),
        },
    },

    emits: ['update:modelValue'],

    computed: {
        modelValueEmpty() {
            return this.modelValue === null
        }
    },

}
</script>
