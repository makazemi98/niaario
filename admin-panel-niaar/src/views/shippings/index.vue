<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-start align-items-center">
          <b-avatar size="lg" rounded="sm" variant="light-primary">
            <feather-icon icon="TruckIcon" style="transform: scale(1.7)" />
          </b-avatar>
          <div
            class="d-flex flex-column justify-content-start align-items-start ml-1"
          >
            <h6>Manage Shippings</h6>
            <b-breadcrumb class="breadcrumb-slash p-0">
              <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
              <b-breadcrumb-item active> Shippings </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </div>
      </b-col>
    </b-row>
    <!-- collapse button -->

    <b-card
      no-body
      class="collapsible-form px-2 py-1 d-flex flex-row justify-content-between align-items-center"
      @click="openUsersFilterForm"
      v-b-toggle.users-filter
    >
      <div>
        <feather-icon icon="SearchIcon" style="transform: scale(1.2)" />
        <span class="ml-1 fs-sm">Search In shipping ...</span>
      </div>
      <b-button variant="flat-primary" class="btn-icon" size="sm">
        <feather-icon
          :icon="usersFilterFormCollapsed ? 'ChevronUpIcon' : 'ChevronDownIcon'"
          style="transform: scale(1.4)"
        />
      </b-button>
    </b-card>

    <!-- search form -->
    <b-collapse id="users-filter" class="mt-1">
      <b-card no-body>
        <b-card-body>
          <b-row class="mb-1">
            <b-col cols="12" md="6">
              <b-form-group label="Agent Name" label-for="name">
                <b-form-input
                  id="name"
                  v-model="form.agent_name"
                  placeholder="Enter Name"
                />
              </b-form-group>
            </b-col>
            <b-col md="6">
              <b-form-group label="status">
                <v-select
                  id="statusList"
                  v-model="form.status"
                  label="title"
                  :options="statuses"
                  :reduce="(title) => title.key"
                />
              </b-form-group>
            </b-col>

            <!-- <b-col md="6">
              <b-form-group label="Handed Over Date">
                <b-form-datepicker
                  v-model="form.handed_over_date"
                  id="date"
                  placeholder="Date"
                />
              </b-form-group>
            </b-col> -->
          </b-row>
          <b-button size="sm" variant="primary" class="mr-1" @click="getData()">
            Filter
          </b-button>
          <b-button size="sm" variant="flat-danger" @click="form = {}">
            Reset
          </b-button>
        </b-card-body>
      </b-card>
    </b-collapse>
    <!-- search form -->

    <!-- users table -->
    <b-row class="mt-2">
      <b-col cols="12">
        <div v-if="isLoaded">
          <b-card v-if="list.length > 0" no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Shipping List</h4>
              <b-button
                variant="gradient-success"
                size="sm"
                to="/shippings/create"
              >
                <feather-icon icon="PlusIcon" class="mr-50" />
                <span class="align-middle"> New Shipping </span>
              </b-button>
            </div>
            <b-table
              responsive
              :striped="striped"
              :bordered="bordered"
              :outlined="outlined"
              :fields="fields"
              :items="list"
            >
              <template #cell(handed_over_date)="data">
                {{ data.item.handed_over_date | formatDate }}
              </template>
              <template #cell(status)="data">
                {{ data.item.status.replaceAll("_", " ") }}
              </template>
              <template #cell(action)="data">
                <b-dropdown
                  droptop
                  size="sm"
                  text="Actions"
                  variant="gradient-primary"
                >
                  <b-dropdown-item :to="`/shippings/${data.item.id}`">
                    <small>Edit</small>
                  </b-dropdown-item>

                  <b-dropdown-item
                    @click="deleteUser(data.item.id, data.item.name)"
                  >
                    <small>Delete</small>
                  </b-dropdown-item>
                </b-dropdown>
              </template>
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
    getData() {
      axios
        .get("/admin/shipping", {
          params: { page: this.page, per_page: this.perPage, ...this.form },
        })
        .then((response) => {
          // console.log(response.data.data);
          this.list = response.data.data;
          this.count = response.data.meta.total;
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
        window.location.reload();
      });
    },
  },
  data() {
    return {
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
        { key: "id", label: "Id", sortable: true },
        { key: "agent_name", label: "Agent Name", sortable: true },
        { key: "agent_no", label: "Agent No", sortable: true },
        { key: "boxes_count", label: "Boxes count", sortable: true },
        { key: "captain_info", label: "Capt. Info", sortable: true },
        { key: "handed_over_date", label: "Date", sortable: true },
        { key: "status", label: "Status", sortable: true },
        { key: "action", label: "Action" },
      ],
      list: [],
      // table pagination
      page: 1,
      perPage: 10,
      count: 0,
      // edit user
      // rewards
      userRewardsModal: false,
      // transactions
      userTransactionsModal: false,
      // change password
      userChangePasswordModal: false,
      // send email
      userSendEmailModal: false,
      statuses: [
        { key: "preparing", title: "Preparing" },
        { key: "handed_to_capt", title: "Handed to captain" },
        { key: "left_dubai", title: "Left dubai" },
        { key: "document_collected", title: "Document collected" },
        { key: "reached_to_destination", title: "Reached to destination" },
        { key: "paid", title: "Paid" },
        { key: "delivered", title: "delivered" },
      ],
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
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
    UserRewardsModal,
    UserTransactionsModal,
    UserChangePasswordModal,
    UserSendEmailModal,
  },
};
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
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
} from "bootstrap-vue";
import VSelect from "vue-select";
import UserRewardsModal from "../components/users-list/user-rewards/UserRewardsModal.vue";
import UserTransactionsModal from "../components/users-list/user-transactions/UserTransactionsModal.vue";
import UserChangePasswordModal from "../components/users-list/user-change-password/UserChangePasswordModal.vue";
import UserSendEmailModal from "../components/users-list/user-send-email/UserSendEmailModal.vue";
import store from "@/store";
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>
