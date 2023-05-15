<template>
  <div>
    <b-card v-if="isLoaded">
      <h3>STATISTICS</h3>
      <hr />
      <div
        v-for="item in Object.keys(data)"
        :key="item"
        class="d-inline-block p-2 mr-2 mb-2 border text center rounded"
      >
        <h4 class="mb-0">{{ item.replace('_'," ") }}: {{ data[item] }}</h4>
      </div>
    </b-card>

    <div class="text-center" v-else>
      <b-card>
        <b-spinner />
      </b-card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      isLoaded: false,
      form: {},
      data: [],
    };
  },
  mounted() {
    this.getData();
  },

  methods: {
    getData() {
      axios
        .get(`admin/users/${this.userData.id}/statistics`, {})
        .then((response) => {
          // console.log("ssttaattiiss", response);
          this.data = response.data;
          this.isLoaded = true;
        })
        .catch((error) => {
          this.isLoaded = true;
        });
    },
  },
  components: {
    BFormDatepicker,
    BRow,
    BCol,
    BCard,
    BFormGroup,
    BFormRadioGroup,
    BSpinner,
    BButton,
  },
};
import {
  BFormDatepicker,
  BButton,
  BRow,
  BCol,
  BCard,
  BFormGroup,
  BFormRadioGroup,
  BSpinner,
} from "bootstrap-vue";
import axios from "@axios";
</script>
