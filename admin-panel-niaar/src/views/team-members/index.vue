<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-start align-items-center">
          <b-avatar size="lg" rounded="sm" variant="light-primary">
            <feather-icon icon="UsersIcon" style="transform: scale(1.7)" />
          </b-avatar>
          <div
            class="d-flex flex-column justify-content-start align-items-start ml-1"
          >
            <h6>Manage Team Members</h6>
            <b-breadcrumb class="breadcrumb-slash p-0">
              <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
              <b-breadcrumb-item active> Team Members </b-breadcrumb-item>
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
        <span class="ml-1 fs-sm">Search In Team Members ...</span>
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
              <b-form-group label="Name" label-for="name">
                <b-form-input
                  id="name"
                  v-model="form.name"
                  placeholder="Enter Name"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="order" label-for="order">
                <v-select
                  label="label"
                  id="user-type"
                  :options="[
                    { key: 'asc', label: 'ascend' },
                    { key: 'desc', label: 'descend' },
                  ]"
                  placeholder="Select An Option"
                  v-model="form.order"
                  :reduce="(option) => option.key"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="User Type" label-for="user-type">
                <v-select
                  label="title"
                  id="user-type"
                  :options="userTypes"
                  placeholder="Select An Option"
                  v-model="form.role"
                  :reduce="(option) => option.key"
                />
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group label="Reg. Date From" label-for="reg-date-from">
                <b-form-datepicker
                  id="reg-date-from"
                  v-model="form.from_created_at"
                />
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group label="Reg. Date TO" label-for="reg-date-to">
                <b-form-datepicker
                  id="reg-date-to"
                  v-model="form.to_created_at"
                />
              </b-form-group>
            </b-col>
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
        <div>
          <b-card no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Team Members List</h4>
              <b-button
                v-if="
                  userData.role !== 'procurement' ||
                  userData.role !== 'accountant'
                "
                variant="gradient-success"
                size="sm"
                to="/team-members/create"
              >
                <feather-icon icon="PlusIcon" class="mr-50" />
                <span class="align-middle"> New Team Member </span>
              </b-button>
            </div>

            <div v-if="isLoaded">
              <b-table
                v-if="users.length > 0"
                responsive
                :striped="striped"
                :bordered="bordered"
                :outlined="outlined"
                :fields="fields"
                :items="users"
              >
                <template #cell(name)="data">
                  <div class="d-flex justify-content-between">
                    <b-img
                      style="object-fit: contain"
                      class="mr-1"
                      fluid
                      v-if="data.item.avatar"
                      width="40"
                      height="40"
                      rounded
                      :src="data.item.avatar"
                    />
                    <b-img
                      style="object-fit: contain"
                      class="mr-1"
                      fluid
                      v-else
                      width="35"
                      height="35"
                      rounded
                      :src="
                        require('@/assets/images/avatars/placeholder-niaar.png')
                      "
                    />
                    <b-link
                      :to="{
                        name: 'inquiries',
                        params: { assigned_to: data.value },
                      }"
                      >{{ data.value }}
                    </b-link>
                  </div>
                  <b-button size="sm" class="text-white d-block mt-1">
                    <router-link
                      class="text-white"
                      :to="`/profile/${data.item.id}`"
                    >
                      profile
                    </router-link>
                  </b-button>
                </template>

                <template #cell(allInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 'all' },
                    }"
                    >{{ data.item.stats.all_inquiries_count }}
                  </b-link>
                </template>

                <!-- <template #cell(average_response_time)="data">
                  {{ JSON.stringify(data.item.average_response_time)}}
                  {{ data.item.average_response_time.minutes }}
                 {{ data.item.average_response_time.days }} days - 
                  {{ data.item.average_response_time.hours }} hours -
                  {{ data.item.average_response_time.minutes }} minutes
                </template> -->
                <template #cell(asignedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'Assigned',
                      },
                    }"
                    >{{ data.item.stats.assigned }}
                  </b-link>
                </template>

                <template #cell(onProgressInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'On Progress',
                      },
                    }"
                    >{{ data.item.stats.on_progress }}
                  </b-link>
                </template>

                <template #cell(quotationPreparedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'Quotation Prepared',
                      },
                    }"
                    >{{ data.item.stats.quotation_prepared }}
                  </b-link>
                </template>

                <template #cell(quotedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 'Quoted' },
                    }"
                    >{{ data.item.stats.quoted }}
                  </b-link>
                </template>

                <template #cell(rejectedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'Rejected',
                      },
                    }"
                    >{{ data.item.stats.rejected }}
                  </b-link>
                </template>

                <template #cell(approvedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'Approved',
                      },
                    }"
                    >{{ data.item.stats.approved }}
                  </b-link>
                </template>
                <template #cell(paidInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 'Paid' },
                    }"
                    >{{ data.item.stats.paid }}
                  </b-link>
                </template>
                <template #cell(tax_submitted)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'tax_submitted',
                      },
                    }"
                    >{{ data.item.stats.tax_submitted }}
                  </b-link>
                </template>

                <template #cell(orderedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: {
                        assigned_to: data.item.name,
                        status: 'Ordered',
                      },
                    }"
                    >{{ data.item.stats.ordered }}
                  </b-link>
                </template>
                <template #cell(action)="data">
                  <b-dropdown
                    droptop
                    size="sm"
                    text="Actions"
                    variant="gradient-primary"
                    :disabled="loading"
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
                </template>
              </b-table>
              <b-card v-else class="text-center">
                <h4 class="text-secondary mb-0">No records to show ..</h4>
              </b-card>
              <b-pagination
                class="ml-1"
                v-model="page"
                :per-page="perPage"
                :total-rows="count"
              ></b-pagination>
            </div>
            <div v-else class="text-center my-3">
              <b-spinner label="Loading..." />
              <span class="d-block mt-1"> Loading Content ... </span>
            </div>
          </b-card>
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
  computed: {
    activeUsers() {
      return [
        { id: 1, title: "Does Not Matter" },
        { id: 2, title: "Active" },
        { id: 3, title: "Inactive" },
      ];
    },
    emailVerified() {
      return [{ title: "Does Not Matter" }, { title: "Yes" }, { title: "No" }];
    },
    userTypes() {
      return [
        { id: 1, title: "Does Not Matter", key: "" },
        { id: 2, title: "Manager", key: "manager" },
        { id: 3, title: "Team Leader", key: "team-leader" },
        { id: 4, title: "Accountant", key: "accountant" },
        { id: 5, title: "Procurements", key: "procurements" },
        { id: 6, title: "Client", key: "client" },
      ];
    },
    checkHasAnyUserSelected() {
      return this.selectedUsers.length > 0;
    },
  },
  watch: {
    page(newVal) {
      this.getData();
    },
  },
  methods: {
    getData() {
      // console.log("hi");
      axios
        .get("/admin/users/team-members", {
          params: { page: this.page, per_page: this.perPage, ...this.form },
        })
        .then((response) => {
          this.users = response.data.data;
          this.count = response.data.meta.total;

          this.isLoaded = true;
        })
        .catch((error) => (this.isLoaded = true));
      // this.$store.dispatch('users/fetchUsers');
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
      this.loading = true;
      axios.delete(`/admin/users/${id}`).then((res) => {
        this.loading = false;
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
      loading: false,
      form: {},
      // form properties
      selectedActiveUsers: undefined,
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
      userData: JSON.parse(localStorage.getItem("userData")),
      fields: [
        // { key: "check" },
        { key: "name", label: "Name", sortable: true },
        { key: "role", label: "Role", sortable: true },
        { key: "allInquiry", label: "All", sortable: true },
        { key: "asignedInquiry", label: "Asigned", sortable: true },
        { key: "onProgressInquiry", label: "On Progress", sortable: true },
        {
          key: "quotationPreparedInquiry",
          label: "Quotation Prepared",
          sortable: true,
        },
        { key: "quotedInquiry", label: "Quoted", sortable: true },
        { key: "rejectedInquiry", label: "Rejected", sortable: true },
        { key: "approvedInquiry", label: "Approved", sortable: true },
        { key: "paidInquiry", label: "Paid", sortable: true },
        { key: "tax_submitted", label: "tax_submitted", sortable: true },
        { key: "orderedInquiry", label: "Ordered", sortable: true },
        { key: "success_ratio", label: "Success Ratio", sortable: true },
        { key: "profit", label: "Profit", sortable: true },
        {
          key: "average_response_time",
          label: " Avg Reps. Time",
          sortable: true,
        },
        { key: "action", label: "Action" },
      ],
      users: [],
      isSelectedAll: false,
      selectedUsers: [1],
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
    EditUserModal,
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
import EditUserModal from "../components/users-list/edit-user/EditUserModal.vue";
import UserRewardsModal from "../components/users-list/user-rewards/UserRewardsModal.vue";
import UserTransactionsModal from "../components/users-list/user-transactions/UserTransactionsModal.vue";
import UserChangePasswordModal from "../components/users-list/user-change-password/UserChangePasswordModal.vue";
import UserSendEmailModal from "../components/users-list/user-send-email/UserSendEmailModal.vue";
import store from "@/store";
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>
