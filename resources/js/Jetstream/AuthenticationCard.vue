<template>
  <app-layout :show-menu="false">
    <auth-form-box>
      <slot></slot>
    </auth-form-box>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout"
import AuthFormBox from '@/Components/AuthFormBox'

export default {
  components: {
    AppLayout,
    AuthFormBox,
  }
}
</script>
