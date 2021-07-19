<template>
  <div class="row justify-content-center py-4">
    <div class="col-md-4">
      <jet-section-title>
        <template #title><slot name="title"></slot></template>
        <template #description>
          <span class="small">
            <slot name="description"></slot>
          </span>
        </template>
      </jet-section-title>
    </div>

    <div class="col-md-7">
      <form @submit.prevent="$emit('submitted')">
        <div class="card shadow-sm">
          <div class="card-body">
            <slot name="form"></slot>
            <div class="border-top py-4 mt-3 d-flex justify-content-end" v-if="hasActions">
              <slot name="actions"></slot>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</template>

<script>
  import JetSectionTitle from './SectionTitle'

  export default {
    components: {
      JetSectionTitle,
    },

    emits: ['submitted'],

    computed: {
      hasActions() {
        return !! this.$slots.actions
      }
    }
  }
</script>
