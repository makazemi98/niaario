<template>
  <div>
    <b-card v-if="isLoaded">
      <h3>Inquiries Statistics</h3>
      <b-row class="flex align-items-center">
        <b-col cols="12" md="4">
          <b-form-group label="Date From" label-for="date-from">
            <b-form-datepicker id="date-from" v-model="form.from_created_at" />
          </b-form-group>
        </b-col>

        <b-col cols="12" md="4">
          <b-form-group label="Date To" label-for="date-to">
            <b-form-datepicker id="date-to" v-model="form.to_created_at" />
          </b-form-group>
        </b-col>

        <b-col>
          <b-button @click="getData()"> search</b-button>
        </b-col>
      </b-row>
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
      isLoaded: false,
      form: {},
      data: [],
      dateOptions: [
        { text: "Last year ", value: "last_year" },
        { text: "Last 30 Days", value: "last_30" },
        { text: "Last 7 Days", value: "last_7" },
      ],
    };
  },
  mounted() {
    this.getData();
  },

  methods: {
    getData() {
      axios
        .get("admin/dashboard/stats/inquiries", {
          params: {
            ...this.form,
          },
        })
        .then((response) => {
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
