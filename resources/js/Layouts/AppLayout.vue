<template>
  <div class="page-wrapper">
    <navigation-top />

    <main class="main">
      <jet-banner />
      <slot></slot>
    </main>
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
  },

  data() {
    return {
      showingNavigationDropdown: false,
    };
  },

  mounted() {
    var catDropdown = $(".category-dropdown"),
      catInitVal = catDropdown.data("visible");

    if ($(".sticky-header").length && $(window).width() >= 992) {
      var sticky = new Waypoint.Sticky({
        element: $(".sticky-header")[0],
        stuckClass: "fixed",
        offset: -300,
        handler: function (direction) {
          // Show category dropdown
          if (catInitVal && direction == "up") {
            catDropdown
              .addClass("show")
              .find(".dropdown-menu")
              .addClass("show");
            catDropdown.find(".dropdown-toggle").attr("aria-expanded", "true");
            return false;
          }

          // Hide category dropdown on fixed header
          if (catDropdown.hasClass("show")) {
            catDropdown
              .removeClass("show")
              .find(".dropdown-menu")
              .removeClass("show");
            catDropdown.find(".dropdown-toggle").attr("aria-expanded", "false");
          }
        },
      });
    }
  },

  methods: {
    switchToTeam(team) {
      this.$inertia.put(
        route("current-team.update"),
        {
          team_id: team.id,
        },
        {
          preserveState: false,
        }
      );
    },

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
