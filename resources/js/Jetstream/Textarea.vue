<template>
  <textarea class="form-control" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="textarea"></textarea>
</template>

<script>
  export default {
    props: {
        modelValue: {
            required: true,
        },
    },

    emits: ['update:modelValue'],

    methods: {
      focus() {
        this.$refs.textarea.focus()
      }
    }
  }
</script>

