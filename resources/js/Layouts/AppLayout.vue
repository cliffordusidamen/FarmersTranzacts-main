<template>
  <div class="page-wrapper">
    <navigation-top :show-categories="showCategories" />

    <main class="main">
      <jet-banner />
      <pending-agent-banner v-if="this.$page?.props?.user" />
      <slot></slot>
    </main>

    <app-footer />
  </div>
  <nav-mobile />
  <confirmation-modal />
</template>

<script>
import JetApplicationLogo from "@/Jetstream/ApplicationLogo";
import JetBanner from "@/Jetstream/Banner";
import JetApplicationMark from "@/Jetstream/ApplicationMark";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";
import JetNavLink from "@/Jetstream/NavLink";
import NavigationTop from "@/Components/NavigationTop.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import NavMobile from "@/Components/NavMobile";
import AppFooter from "@/Components/AppFooter";
import PendingAgentBanner from "@/Components/PendingAgentBanner.vue";

export default {
  components: {
    AppFooter,
    ConfirmationModal,
    JetApplicationLogo,
    JetBanner,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    NavigationTop,
    NavMobile,
    PendingAgentBanner,
  },

  props: {
    showCategories: {
      type: Boolean,
      default: true,
    }
  },

  data() {
    return {
      showingNavigationDropdown: false,
    };
  },

  methods: {

    logout() {
      this.$inertia.post(route("logout"));
    },

  },

  computed: {
    path() {
      return window.location.pathname;
    },
  },
};
</script>
