<template>
  <div>
    <b-card v-if="isLoaded">
      <h3>Pending Tasks</h3>
      <hr />
      <b-table
        responsive
        striped
        bordered
        outlined
        :fields="fields"
        :items="data"
      >
      <template #cell(stats)="data">
        <b-link class="d-inline-block p-1 mr-1 mb-1 border text center rounded" v-for="i in Object.keys(data.item.stats)" :key="i"
          :to="{
            name: 'inquiries',
            params: {
              assigned_to: data.item.name,
              status: i,
            },
          }"
          > {{i}} - {{data.item.stats[i]}}
        </b-link>
      </template>
      </b-table>
      <!-- <div
        v-for="item in Object.keys(data)"
        :key="item"
        class="d-inline-block p-2 mr-2 mb-2 border text center rounded"
      >
        <h4 class="mb-0">{{ item.replace("_", " ") }}: {{ data[item] }}</h4>
      </div> -->
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
      fields: [
        { key: "name", label: "Name", sortable: true },
        { key: "role", label: "Role", sortable: true },
        { key: "stats", label: "All", sortable: true },
      ],
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
      // if (
      //   this.userData.role == "manager" ||
      //   this.userData.role == "super-admin"
      // ) {
      //   axios
      //     .get(`admin/users/team-members?per_page=100000&page=1`, {
      //     })
      //     .then((response) => {
      //       console.log("ajab",response);
      //       this.data = response.data;
      //       this.isLoaded = true;
      //     })
      //     .catch((error) => {
      //       this.isLoaded = true;
      //     });
      // }
      // else {
      axios
        .get(`admin/users/pending-tasks`, {})
        .then((response) => {
          // console.log("bdinshekdl", response);
          this.data = response.data.data;
          this.isLoaded = true;
        })
        .catch((error) => {
          this.isLoaded = true;
        });
      // }
    },
  },
  components: {
    BFormDatepicker,
    BRow,
    BCol,
    BCard,
    BTable,
    BFormGroup,
    BFormRadioGroup,
    BSpinner,
    BButton,
    BLink,
  },
};
import {
  BFormDatepicker,
  BButton,
  BRow,
  BTable,
  BCol,
  BCard,
  BFormGroup,
  BFormRadioGroup,
  BSpinner,
  BLink,
} from "bootstrap-vue";
import axios from "@axios";
</script>
