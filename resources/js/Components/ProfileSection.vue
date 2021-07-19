<template>
  <div class="row justify-content-center pt-3">
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
      <slot></slot>
    </div>
  </div>
</template>

<script>
  import JetSectionTitle from '@/Jetstream/SectionTitle'
  export default {
    components: {
      JetSectionTitle,
    },
  }
</script>
