<template>
  <button :type="type" :class="`btn ${contextClass} text-uppercase`">
    <slot></slot>
  </button>
</template>

<script>
  export default {
    props: {
      type: {
        type: String,
        default: 'submit',
      },

      contextClass: {
        type: String,
        default: 'btn-dark',
      },
    }
  }
</script>
