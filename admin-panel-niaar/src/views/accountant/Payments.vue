<template>
  <section>
    <!-- list table -->

    <b-row
      v-if="userData.role !== 'client'"
      class="align-items-center d-flex my-2"
    >
      <b-col cols="12" md="6">
        <b-form-group label="Client :">
          <v-select
            v-if="clients"
            v-model="clientId"
            :options="clients"
            :reduce="(option) => option.id"
            label="abbreviation"
          >
            <template v-slot:option="option">
              {{ option.abbreviation }} -
              {{ option.name }}
            </template>
          </v-select>
          <div v-else>loading...</div>
        </b-form-group>
      </b-col>

      <b-col cols="12" md="6">
        <b-form-group label="Team :">
          <v-select
            v-if="teams"
            v-model="form.creator_id"
            :options="teams"
            :reduce="(option) => option.id"
            label="abbreviation"
          >
            <template v-slot:option="option">
              {{ option.abbreviation }} -
              {{ option.name }}
            </template>
          </v-select>
          <div v-else>loading...</div>
        </b-form-group>
      </b-col>

      <b-col cols="12" md="6">
        <b-form-group label="Date From" label-for="reg-date-from">
          <b-form-datepicker
            id="reg-date-from"
            v-model="form.from_created_at"
          />
        </b-form-group>
      </b-col>

      <b-col cols="12" md="6">
        <b-form-group label="Date To" label-for="reg-date-to">
          <b-form-datepicker id="reg-date-to" v-model="form.to_created_at" />
        </b-form-group>
      </b-col>

      <b-col cols="12" md="6">
        <b-form-group label="Inquiry Number" label-for="inquiryNumber">
          <b-form-input
            id="inquiryNumber"
            v-model="form.inquiry_id"
            placeholder="Enter Inquiry Number"
            type="number"
          />
        </b-form-group>
      </b-col>

      <b-col cols="12" md="6">
        <b-form-group label="Description Or Remark" label-for="descOrRemark">
          <b-form-input
            id="descOrRemark"
            v-model="form.text"
            placeholder="Enter Description Or Remark"
          />
        </b-form-group>
      </b-col>

      <b-col>
        <b-button
          class="mr-2"
          size="sm"
          variant="flat-danger"
          @click="(form = {}), (clientId = null)"
        >
          Reset
        </b-button>

        <b-button size="sm" variant="primary" class="mr-1" @click="getData()">
          Filter
        </b-button>
      </b-col>
    </b-row>
    <div v-if="isLoaded">
      <div v-if="userData.role !== 'client'">
        <h6>Total Balance: {{ totals.balance }}</h6>
        <h6>Total Creidt: {{ totals.credit }}</h6>
        <h6>Total Debit: {{ totals.debit }}</h6>
      </div>
      <b-card v-if="list" no-body>
        <div
          class="p-2 d-flex flex-row justify-content-between align-items-center"
        >
          <h4 class="mb-0">Payments</h4>
          <!-- <b-button
                variant="gradient-success"
                size="sm"
                to="/team-members/create"
              >
                <feather-icon icon="PlusIcon" class="mr-50" />
                <span class="align-middle"> New Team Member </span>
              </b-button> -->
        </div>
        <b-table
          responsive
          :striped="striped"
          :bordered="bordered"
          :outlined="outlined"
          :fields="fields"
          :items="list"
        >
          <template #cell(date)="data">
            {{ data.item.date | formatDate }}
          </template>
          <!-- <template #cell(inquiry)="data">
            {{ data.item.inquiry.id }} - {{ data.item.inquiry.title }}
          </template> -->

          <template #cell(is_paid)="data">
            <feather-icon
              v-if="data.item.is_paid == 1"
              icon="CheckIcon"
              class="mr-50"
            />

            <feather-icon v-else icon="XIcon" class="mr-50" />
          </template>

          <template #cell(past_due)="data">
            <feather-icon
              v-if="data.item.past_due == 1"
              icon="CheckIcon"
              class="mr-50"
            />

            <feather-icon v-else icon="XIcon" class="mr-50" />
          </template>
          <!-- <template #cell(action)="data">
                <b-dropdown
                  droptop
                  size="sm"
                  text="Actions"
                  variant="gradient-primary"
                >
                  <b-dropdown-item :to="`/team-members/${data.item.id}`">
                    <small>Edit</small>
                  </b-dropdown-item>

                  <b-dropdown-item
                    @click="deleteUser(data.item.id, data.item.name)"
                  >
                    <small>Delete User</small>
                  </b-dropdown-item>
                </b-dropdown>
              </template> -->
        </b-table>

        <b-pagination
          class="ml-1"
          v-model="page"
          :per-page="perPage"
          :total-rows="count"
        ></b-pagination>
      </b-card>

      <b-card v-else class="text-center">
        <h4 class="text-secondary mb-0">No records to show ..</h4>
      </b-card>
    </div>
    <div v-else class="text-center my-3">
      <b-spinner label="Loading..." />
      <span class="d-block mt-1"> Loading Content ... </span>
    </div>
  </section>
</template>

<script>
export default {
  directives: {
    "b-toggle": VBToggle,
  },
  watch: {
    page(newVal) {
      this.getData();
    },
  },
  methods: {
    getClientsData() {
      axios.get("/admin/users?role=client").then((response) => {
        this.clients = response.data.data;
      });
    },
    getTeamsData() {
      axios.get("/admin/users/team-members").then((response) => {
        this.teams = response.data.data;
      });
    },
    getData() {
      // if (this.userData.role == "client") {
      //   axios
      //     .get("/admin/accounting/future_dues", {
      //       params: {
      //         page: this.page,
      //         per_page: this.perPage,
      //         ...this.form,
      //         client_id: this.userData.id,
      //       },
      //     })
      //     .then((response) => {
      //       console.log(response);
      //       this.list = response.data;
      //       this.totals = response.totals;
      //       this.count = response?.meta?.total;
      //       this.isLoaded = true;
      //     })
      //     .catch((error) => (this.isLoaded = true));
      // } else
        axios
          .get("/admin/accounting/payments", {
            params: {
              page: this.page,
              per_page: this.perPage,
              ...this.form,
              client_id: this.clientId,
            },
          })
          .then((response) => {
            console.log(response);
            this.list = response.data?.data;
            this.totals = response.data?.totals;
            this.count = response?.data?.meta?.total;

            this.isLoaded = true;
          })
          .catch((error) => (this.isLoaded = true));
    },
    openUsersFilterForm() {
      this.usersFilterFormCollapsed = this.usersFilterFormCollapsed
        ? false
        : true;
    },
    resetForm() {
      this.name = this.role = this.dateFrom = this.dateTo = null;
    },
    deleteUser(id, name) {
      axios
        .delete(`/admin/users/${id}`)
        .then((res) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully deleted ${name}!`,
            },
          });
          this.form = {};
          this.getData();
        })
        .catch((error) => {
          if (error.response.data.errors)
            Object.keys(error.response.data.errors).map((item) => {
              error.response.data.errors[item].map((err) => {
                this.$toast({
                  component: ToastificationContent,
                  position: "top-right",
                  props: {
                    title: `error ${item}`,
                    icon: "XIcon",
                    variant: "danger",
                    text: err,
                  },
                });
              });
            });
          else
            this.$toast({
              component: ToastificationContent,
              position: "top-right",
              props: {
                title: "error",
                icon: "XIcon",
                variant: "danger",
                text: error.message,
              },
            });
        });
    },
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      clientId: null,
      clients: [],
      teams: [],
      totals: {},
      form: {},
      // form properties
      usersFilterFormCollapsed: false,
      name: undefined,
      selectedEmailVerified: undefined,
      role: undefined,
      dateFrom: undefined,
      dateTo: undefined,
      // loading process
      isLoaded: false,
      // table properties
      striped: true,
      bordered: true,
      outlined: true,
      fields: [
        // { key: "check" },
        { key: "id", label: "ID", sortable: true },
        { key: "date", label: "Date", sortable: true },
        { key: "inquiry.id", label: "Inquiry No.", sortable: true },
        { key: "customer.name", label: "Customer", sortable: true },
        { key: "creator.name", label: "Team", sortable: true },
        { key: "credit", label: "Recieved", sortable: true },
        { key: "debit", label: "Paid", sortable: true },
        { key: "balance", label: "Balance", sortable: true },
        {
          key: "description",
          label: "Description",
          sortable: true,
        },
        // { key: "is_paid", label: "Is Paid", sortable: true },
        // { key: "past_due", label: "Past Due", sortable: true },
        // { key: "action", label: "Action" },
      ],
      list: [],
      // table pagination
      page: 1,
      perPage: 10,
      count: 0,
      // edit user
      editUserModal: false,
      // rewards
      userRewardsModal: false,
      // transactions
      userTransactionsModal: false,
      // change password
      userChangePasswordModal: false,
      // send email
      userSendEmailModal: false,
    };
  },
  mounted() {
    this.getTeamsData();
    this.getClientsData();
    this.getData();
  },
  components: {
    BLink,
    BImg,
    BRow,
    BCol,
    BAvatar,
    BBreadcrumb,
    BBreadcrumbItem,
    BButton,
    BCollapse,
    BCard,
    BCardBody,
    BFormGroup,
    BFormInput,
    VSelect,
    BFormDatepicker,
    BTable,
    BSpinner,
    BFormCheckbox,
    BDropdown,
    BDropdownItem,
    BPagination,
    EditUserModal,
    UserRewardsModal,
    UserTransactionsModal,
    UserChangePasswordModal,
    UserSendEmailModal,
    BTabs,
    BTab,
  },
};

// * dependencies
import axios from "@axios";
import _ from "lodash";
// * components
import {
  BLink,
  BRow,
  BCol,
  BAvatar,
  BBreadcrumb,
  BBreadcrumbItem,
  BButton,
  BCollapse,
  VBToggle,
  BCard,
  BCardBody,
  BFormGroup,
  BFormInput,
  BFormDatepicker,
  BTable,
  BSpinner,
  BFormCheckbox,
  BDropdown,
  BDropdownItem,
  BPagination,
  BImg,
  BTabs,
  BTab,
} from "bootstrap-vue";
import VSelect from "vue-select";
import EditUserModal from "../components/users-list/edit-user/EditUserModal.vue";
import UserRewardsModal from "../components/users-list/user-rewards/UserRewardsModal.vue";
import UserTransactionsModal from "../components/users-list/user-transactions/UserTransactionsModal.vue";
import UserChangePasswordModal from "../components/users-list/user-change-password/UserChangePasswordModal.vue";
import UserSendEmailModal from "../components/users-list/user-send-email/UserSendEmailModal.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import store from "@/store";
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>
