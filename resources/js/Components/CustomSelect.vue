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
        <option v-if="showPlaceholder" :selected="modelValueEmpty" value="">{{ placeholder }}</option>
        <option
            v-for="key in Object.keys(options)"
            :value="key"
            :key="key"
            :selected="isSelected(key)"
        >{{ options[key] }}</option>
    </select>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
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
        showPlaceholder: {
            type: Boolean,
            default: true,
        },
    },

    emits: ['update:modelValue'],

    computed: {
        modelValueEmpty() {
            return this.modelValue === null
        },
        firstOptionKey() {
            return Object.keys(this.options)[0]
        },
    },

    methods: {
        isSelected(key) {
            if (!this.showPlaceholder && this.modelValue == '') {
                return this.firstOptionKey === key
            }

            return !this.modelValueEmpty && this.modelValue == this.options[key]
        },
    },

}
</script>
