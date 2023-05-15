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
    <!-- collapse content (users filter form) -->
    <b-collapse id="users-filter" class="mt-1">
      <b-card no-body>
        <b-card-body>
          <b-row class="mb-1">
            <b-col cols="12" md="6">
              <b-form-group label="Name" label-for="name">
                <b-form-input
                  id="name"
                  v-model="name"
                  placeholder="Enter Name"
                />
              </b-form-group>
            </b-col>
            <!-- <b-col cols="12" md="6">
              <b-form-group label="Active Users" label-for="active-users">
                <v-select
                  label="title"
                  id="active-users"
                  :options="activeUsers"
                  v-model="selectedActiveUsers"
                  placeholder="Select An Option"
                />
              </b-form-group>
            </b-col> -->
            <!-- <b-col cols="12" md="6">
              <b-form-group label="Email Verified" label-for="email-verified">
                <v-select
                  label="title"
                  id="email-verified"
                  :options="emailVerified"
                  placeholder="Select An Option"
                  v-model="selectedEmailVerified"
                />
              </b-form-group>
            </b-col> -->
            <b-col cols="12" md="6">
              <b-form-group label="User Type" label-for="user-type">
                <v-select
                  label="title"
                  id="user-type"
                  :options="userTypes"
                  placeholder="Select An Option"
                  v-model="selectedUserType"
                />
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group label="Reg. Date From" label-for="reg-date-from">
                <b-form-datepicker id="reg-date-from" v-model="dateFrom" />
              </b-form-group>
            </b-col>
            <b-col cols="12" md="6">
              <b-form-group label="Reg. Date To" label-for="reg-date-to">
                <b-form-datepicker id="reg-date-to" v-model="dateTo" />
              </b-form-group>
            </b-col>
          </b-row>
          <b-button size="sm" variant="primary" class="mr-1"> Filter </b-button>
          <b-button size="sm" variant="flat-danger" @click="resetForm()">
            Reset
          </b-button>
        </b-card-body>
      </b-card>
    </b-collapse>
    <!-- users table -->
    <b-row class="mt-2">
      <b-col cols="12">
        <div v-if="isLoaded">
          <b-card v-if="users.length > 0" no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Team Members List</h4>
              <!-- <b-button
                variant="gradient-danger"
                size="sm"
                @click="deleteUsersHandler"
              >
                <feather-icon icon="XIcon" class="mr-50" />
                <span class="align-middle" v-if="checkHasAnyUserSelected">
                  Delete Selected Users
                </span>
                <span class="align-middle" v-else> Deleted Users </span>
              </b-button> -->
            </div>
            <!-- <div>
              <b-alert
                v-height-fade.appear
                :show="this.alert.visible"
                dismissible
                class="mb-0"
                :variant="this.alert.type"
              >
                <div class="alert-body">
                  {{ this.alert.text }}
                </div>
              </b-alert>
            </div> -->
            <b-table
              responsive
              :striped="striped"
              :bordered="bordered"
              :outlined="outlined"
              :fields="fields"
              :items="users"
            >
              <!-- <template #head(check)>
                <b-form-checkbox
                  :value="true"
                  v-model="isSelectedAll"
                  @change="selectAllHandler"
                />
              </template>
              <template #cell(check)="data">
                <b-form-checkbox
                  v-model="selectedUsers"
                  :value="data.item.id"
                />
              </template> -->
              <!-- <template #cell(id)="data">
                {{ data.value }}
              </template> -->
              <template #cell(name)="data">
                <b-link to="/shops"
                  >{{ data.item.name }}
                </b-link>
              </template>

              <template #cell(role)="data">
                <strong>{{ data.value }}</strong>
              </template>
              <template #cell(type)="data">
                {{ data.value }}
              </template>
              <template #cell(reg_date)="data">
                {{ data.value }}
              </template>
              <template #cell(status)="data">
                <b-form-checkbox switch inline :checked="data.value">
                </b-form-checkbox>
              </template>
              <template #cell(verified)="data">
                {{ data.value }}
              </template>
              <template #cell(action)="data">
                <b-dropdown
                  droptop
                  size="sm"
                  text="Actions"
                  variant="gradient-primary"
                >
                  <b-dropdown-item @click="editUserModal = true">
                    <small>Edit</small>
                  </b-dropdown-item>
                  <b-dropdown-item @click="userRewardsModal = true">
                    <small>Rewards</small>
                  </b-dropdown-item>
                  <b-dropdown-item @click="userTransactionsModal = true">
                    <small>Transactions</small>
                  </b-dropdown-item>
                  <b-dropdown-item @click="userChangePasswordModal = true">
                    <small>Change Password</small>
                  </b-dropdown-item>
                  <b-dropdown-item href="#">
                    <small>Login To User Profile</small>
                  </b-dropdown-item>
                  <b-dropdown-item @click="userSendEmailModal = true">
                    <small>Email User</small>
                  </b-dropdown-item>
                  <b-dropdown-item>
                    <small>Delete User</small>
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

    <!-- edit user -->
    <edit-user-modal :show="editUserModal" @toggle="editUserModal = false" />
    <user-rewards-modal
      :show="userRewardsModal"
      @toggle="userRewardsModal = false"
    />
    <user-transactions-modal
      :show="userTransactionsModal"
      @toggle="userTransactionsModal = false"
    />
    <user-change-password-modal
      :show="userChangePasswordModal"
      @toggle="userChangePasswordModal = false"
    />
    <user-send-email-modal
      :show="userSendEmailModal"
      @toggle="userSendEmailModal = false"
    />
  </section>
</template>

<script>
// * dependencies
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
} from "bootstrap-vue";
import VSelect from "vue-select";
import EditUserModal from "../components/users-list/edit-user/EditUserModal.vue";
import UserRewardsModal from "../components/users-list/user-rewards/UserRewardsModal.vue";
import UserTransactionsModal from "../components/users-list/user-transactions/UserTransactionsModal.vue";
import UserChangePasswordModal from "../components/users-list/user-change-password/UserChangePasswordModal.vue";
import UserSendEmailModal from "../components/users-list/user-send-email/UserSendEmailModal.vue";

export default {
  components: {
    BLink,
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
        { id: 1, title: "Does Not Matter" },
        { id: 2, title: "Manager" },
        { id: 3, title: "Team Leader" },
        { id: 4, title: "Accountant" },
        { id: 5, title: "Procurements" },
        { id: 6, title: "Client" },
      ];
    },
    checkHasAnyUserSelected() {
      return this.selectedUsers.length > 0;
    },
  },
  watch: {
    page(newVal) {
      console.log(newVal);
      // this.getData();
    },
  },
  methods: {
    openUsersFilterForm() {
      this.usersFilterFormCollapsed = this.usersFilterFormCollapsed
        ? false
        : true;
    }, 
    selectAllHandler() {
      if (this.isSelectedAll) {
        this.selectedUsers = _.map(this.users, "id");
      } else {
        this.selectedUsers = [];
      }
    },
    deleteUsersHandler() {
      if (this.checkHasAnyUserSelected) {
        console.log("delete users actions");
      } else {
        console.log("got to deleted users page");
      }
    },
    resetForm() {
      this.name = this.selectedUserType = this.dateFrom = this.dateTo = null;
    },
  },
  data() {
    return {
      // form properties
      selectedActiveUsers: undefined,
      usersFilterFormCollapsed: false,
      name: undefined,
      selectedEmailVerified: undefined,
      selectedUserType: undefined,
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
        { key: "name", label: "Name", sortable: true },
        { key: "role", label: "Role", sortable: true },
        { key: "allInquiry", label: "All", sortable: true },
        { key: "asignesInquiry", label: "Asigned", sortable: true },
        { key: "canceledInquiry", label: "Canceled", sortable: true },
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
        { key: "orderedInquiry", label: "Ordered", sortable: true },
        { key: "successRatio", label: "Success Ratio", sortable: true },
        { key: "profit", label: "Profit", sortable: true },
        { key: "avgRepsTime", label: " Avg Reps. Time", sortable: true },
      ],
      users: [
        {
          id: 1,
          name: "Aysha",
          role: "Procurment",
          allInquiry: 12,
          asignesInquiry: 12,
          canceledInquiry: 2,
          onProgressInquiry: 3,
          quotationPreparedInquiry: 4,
          quotedInquiry: 3,
          rejectedInquiry: 6,
          approvedInquiry: 7,
          paidInquiry: 9,
          orderedInquiry: 12,
          successRatio: "45%",
          profit: "1200000",
          avgRepsTime: 30,
        },
        {
          id: 2,
          name: "Lili",
          role: "Procurment",
          allInquiry: 13,
          asignesInquiry: 5,
          canceledInquiry: 4,
          onProgressInquiry: 8,
          quotationPreparedInquiry: 0,
          quotedInquiry: 6,
          rejectedInquiry: 9,
          approvedInquiry: 5,
          paidInquiry: 4,
          orderedInquiry: 12,
          successRatio: "45%",
          profit: "2300000",
          avgRepsTime: 24,
        },
      ],
      isSelectedAll: false,
      selectedUsers: [1],
      // table pagination
      page: 1,
      perPage: 10,
      count: 34,
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
    // this.$store.dispatch('users/fetchUsers');
    this.isLoaded = true;
  },
};
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>
