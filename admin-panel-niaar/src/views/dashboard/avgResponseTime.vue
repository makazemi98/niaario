<template>
  <div>
    <b-card v-if="isLoaded">
      <h3>Average response time</h3>
      <hr />
      <div v-if="userData.role == 'manager' || userData.role == 'super-admin'">
        <h6>
          Accountant:
          <span class="font-weight-bold">
            {{ data.accountant.days }} days - {{ data.accountant.hours }} hours
            - {{ data.accountant.minutes }} minutes</span
          >
        </h6>

        <h6>
          Team Leader:
          <span class="font-weight-bold">
            {{ data["team-leader"].days }} days -
            {{ data["team-leader"].hours }} hours -
            {{ data["team-leader"].minutes }} minutes</span
          >
        </h6>
      </div>

      <div v-else>
        <h6>
          My Average Response Time:
          <span class="font-weight-bold">
            {{ data.days }} days - {{ data.hours }} hours -
            {{ data.minutes }} minutes</span
          >
        </h6>
      </div>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {},
      dateOptions: [
        { text: "Last year ", value: "last_year" },
        { text: "Last 30 Days", value: "last_30" },
        { text: "Last 7 Days", value: "last_7" },
      ],
      data: null,
      isLoaded: false,
    };
  },
  mounted() {
    this.getData();
  },

  methods: {
    getData() {
      if (
        this.userData.role == "manager" ||
        this.userData.role == "super-admin"
      ) {
        axios
          .get(`/admin/users/response-time-by-roles`, {})
          .then((response) => {
            // console.log(response);
            this.data = response.data;
            this.isLoaded = true;
          })
          .catch((error) => {
            this.isLoaded = true;
          });
      } else {
        axios
          .get(`/admin/users/${this.userData.id}/response-time`, {})
          .then((response) => {
            // console.log(response);
            this.data = response.data;
            this.isLoaded = true;
          })
          .catch((error) => {
            this.isLoaded = true;
          });
      }
    },
  },
  components: {
    BFormDatepicker,
    BRow,
    BCol,
    BCard,
    BFormGroup,
    BFormRadioGroup,
  },
};
import {
  BFormDatepicker,
  BRow,
  BCol,
  BCard,
  BFormGroup,
  BFormRadioGroup,
} from "bootstrap-vue";
import axios from "@axios";
</script>
