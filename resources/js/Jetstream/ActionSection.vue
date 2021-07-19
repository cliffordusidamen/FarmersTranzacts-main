<template>
  <div class="row justify-content-center py-4">
    <div class="col-md-4">
      <jet-section-title>
        <template #title><slot name="title"></slot></template>
        <template #description><slot name="description"></slot></template>
      </jet-section-title>
    </div>

    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-body">
          <slot name="content"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import JetSectionTitle from './SectionTitle'

  export default {
    components: {
      JetSectionTitle,
    }
  }
</script>
