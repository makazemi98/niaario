<template>
  <ul>
    <component
      v-for="item in finalItems"
      :key="item.header || item.title"
      :is="resolveNavItemComponent(item)"
      :item="item"
    />
  </ul>
</template>

<script>
import { resolveVerticalNavMenuItemComponent as resolveNavItemComponent } from "@core/layouts/utils";
import { provide, ref } from "@vue/composition-api";
import VerticalNavMenuHeader from "../vertical-nav-menu-header";
import VerticalNavMenuLink from "../vertical-nav-menu-link/VerticalNavMenuLink.vue";
import VerticalNavMenuGroup from "../vertical-nav-menu-group/VerticalNavMenuGroup.vue";

export default {
  components: {
    VerticalNavMenuHeader,
    VerticalNavMenuLink,
    VerticalNavMenuGroup,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  computed: {
    finalItems() {
      return this.items.filter((item) => !item.notAllowed.includes(this.userData.role));
    },
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  setup() {
    provide("openGroups", ref([]));

    return {
      resolveNavItemComponent,
    };
  },
};
</script>
