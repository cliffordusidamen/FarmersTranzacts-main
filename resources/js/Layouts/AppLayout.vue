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
  <!-- End .page-wrapper -->
  <nav-mobile />
</template>

<script>
import JetApplicationLogo from "@/Jetstream/ApplicationLogo";
import JetBanner from "@/Jetstream/Banner";
import JetApplicationMark from "@/Jetstream/ApplicationMark";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";
import JetNavLink from "@/Jetstream/NavLink";
import NavigationTop from "@/Components/NavigationTop.vue";
import NavMobile from "@/Components/NavMobile";
import AppFooter from "@/Components/AppFooter";
import PendingAgentBanner from "@/Components/PendingAgentBanner.vue";

export default {
  components: {
    NavigationTop,
    JetApplicationLogo,
    JetBanner,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    NavigationTop,
    NavMobile,
    AppFooter,
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
