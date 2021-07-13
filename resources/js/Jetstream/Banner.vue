<template>
    <div
      v-if="show && message"
      :class="'alert alert-dismissible ' + typeClass"
      role="alert"
    >
        <div class="d-flex">
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                <icon-check v-if="style == 'success'" classes="alert-icon" />
                <icon-info-circle v-if="style == 'info'" classes="alert-icon" />
                <icon-alert-circle v-if="style == 'warning'" classes="alert-icon" />
                <icon-alert-triangle v-if="style == 'danger'" classes="alert-icon" />
            </div>
            <div class="text-white">
                <h4 v-if="title" class="alert-title text-white">{{ title }}</h4>
                <div class="text-white" v-html="message"></div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" id="btnClose" aria-label="close"></a>
    </div>
</template>

<script>
import IconCheck from "@/Components/Icons/Check"
import IconInfoCircle from "@/Components/Icons/InfoCircle"
import IconAlertCircle from "@/Components/Icons/AlertCircle"
import IconAlertTriangle from "@/Components/Icons/AlertTriangle"

  export default {
    components: {
      IconCheck,
      IconInfoCircle,
      IconAlertCircle,
      IconAlertTriangle,
    },

    data() {
      return {
        show: true,
      }
    },

    mounted() {
      if (this.show && this.message) {
        setTimeout(() => this.show = false, 4000);
      }
    },

    computed: {
      style() {
        return this.$page.props.jetstream.flash?.bannerStyle || 'info';
      },

      title() {
        return this.$page.props.jetstream.flash?.bannerTitle || '';
      },

      message() {
        return this.$page.props.jetstream.flash?.banner || '';
      },

      typeClass() {
        const typeClasses = {
          'success': 'alert-success',
          'danger': 'alert-danger',
          'warning': 'alert-warning',
          'info': 'alert-info',
        };

        return typeClasses[this.style] || typeClasses['info'];
      }
    }
  }
</script>
