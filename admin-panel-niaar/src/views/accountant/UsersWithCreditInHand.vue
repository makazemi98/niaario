<template>
  <section>
    <!-- list table -->
    <b-row class="mt-2">
      <b-col cols="12">
        <b-row class="align-items-center d-flex">
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

          <b-col>
            <b-button
              size="sm"
              variant="primary"
              class="mr-1"
              @click="getData()"
            >
              Filter
            </b-button>
          </b-col>
        </b-row>
        <div v-if="isLoaded">
          <b-card v-if="list" no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Users With Credit In hand</h4>
            </div>
            <b-table
              responsive
              :striped="striped"
              :bordered="bordered"
              :outlined="outlined"
              :fields="fields"
              :items="list"
            >
              <template #cell(assigned_to)="data">
                {{ data.item.assigned_to.name }}
              </template>
              <template #cell(client)="data">
                {{ data.item.client.name }}
              </template>
              <template #cell(date)="data">
                {{ data.item.date | formatDate }}
              </template>
              <template #cell(inquiry)="data">
                {{ data.item.inquiry.id }} - {{ data.item.inquiry.title }}
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
      </b-col>
    </b-row>
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
    getData() {
      axios
        .get("/admin/accounting/users/credit-in-hand", {
          params: {
            page: this.page,
            per_page: this.perPage,
            ...this.form,
            client_id: this.clientId,
          },
        })
        .then((response) => {
          // console.log(response);
          this.list = response.data.data.data;
          this.count = response.data.total;
          this.isLoaded = true;
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
    openUsersFilterForm() {
      this.usersFilterFormCollapsed = this.usersFilterFormCollapsed
        ? false
        : true;
    },
    resetForm() {
      this.name = this.role = this.dateFrom = this.dateTo = null;
    },
    deleteUser(id, name) {
      axios.delete(`/admin/users/${id}`).then((res) => {
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
      });
    },
  },
  data() {
    return {
      clientId: null,
      clients: [],
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
        { key: "client_id", label: "Client ID", sortable: true },
        { key: "client_full_name", label: "Client Full Name", sortable: true },
        { key: "first_name", label: "First name", sortable: true },
        { key: "last_payment", label: "Last Payment", sortable: true },
        {
          key: "pay_id",
          label: "Payment Id",
          sortable: true,
        },
        { key: "total", label: "Total", sortable: true },
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
