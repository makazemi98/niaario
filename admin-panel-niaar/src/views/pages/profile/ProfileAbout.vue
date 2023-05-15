<template>
  <b-card>
    <!-- about -->
    <div class="d-flex justify-content-start align-items-center mb-1">
      <!-- avatar -->
      <b-avatar :src="profileData.post[0].avatar" size="50" class="mr-1" />
      <!--/ avatar -->
      <div class="profile-user-info">
        <h6 class="mb-0">
          {{ profileData.post[0].username }}
        </h6>
        <small class="text-muted">{{ profileData.post[0].postTime }}</small>
      </div>
    </div>

    <div
      v-for="(data, key, index) in aboutData"
      :key="data.title"
      :class="index ? 'mt-2' : ''"
    >
      <h5 class="text-capitalize mb-75">
        {{ key }}
      </h5>
      <b-card-text>
        {{ data }}
      </b-card-text>
    </div>
  </b-card>
</template>

<script>
import { BCard, BCardText } from "bootstrap-vue";

export default {
  components: {
    BCard,
    BCardText,
  },
  props: {
    aboutData: {
      type: Object,
      default: () => {},
    },
  },
};
</script>
