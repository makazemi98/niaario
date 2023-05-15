<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-start align-items-center">
          <b-avatar size="lg" rounded="sm" variant="light-primary">
            <feather-icon
              icon="CodesandboxIcon"
              style="transform: scale(1.7)"
            />
          </b-avatar>
          <div
            class="d-flex flex-column justify-content-start align-items-start ml-1"
          >
            <h6>Manage Suppliers</h6>
            <b-breadcrumb class="breadcrumb-slash p-0">
              <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
              <b-breadcrumb-item active> Suppliers </b-breadcrumb-item>
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
        <span class="ml-1 fs-sm">Search In Suppliers ...</span>
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
              <b-form-group
                label="product categories"
                label-for="product_categories"
              >
                <b-form-input
                  id="product_categories"
                  v-model="form.product_categories"
                  placeholder="Enter product categories"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group
                label="supplying brands"
                label-for="supplying_brands"
              >
                <b-form-input
                  id="supplying_brands"
                  v-model="form.supplying_brands"
                  placeholder="Enter supplying brands"
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
              <b-form-group label="Date From" label-for="date-from">
                <b-form-datepicker
                  id="date-from"
                  v-model="form.from_created_at"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="Date To" label-for="date-to">
                <b-form-datepicker id="date-to" v-model="form.to_created_at" />
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
        <div v-if="isLoaded">
          <b-card no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Suppliers List</h4>
              <b-button
                variant="gradient-success"
                size="sm"
                to="/suppliers/create"
              >
                <feather-icon icon="FilePlusIcon" class="mr-50" />
                <span class="align-middle"> New Supplier </span>
              </b-button>
            </div>
            <div v-if="suppliers.length > 0">
              <b-table
                responsive
                :striped="striped"
                :bordered="bordered"
                :outlined="outlined"
                :fields="fields"
                :items="suppliers"
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
                </template>

                <template #cell(allInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 1 },
                    }"
                    >{{ data.item.stats.all_inquiries_count }}
                  </b-link>
                </template>
                <template #cell(asignedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 3 },
                    }"
                    >{{ data.item.stats.assigned }}
                  </b-link>
                </template>
                <template #cell(onProgressInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 4 },
                    }"
                    >{{ data.item.stats.on_progress }}
                  </b-link>
                </template>

                <template #cell(quotationPreparedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 5 },
                    }"
                    >{{ data.item.stats.quotation_prepared }}
                  </b-link>
                </template>
                <template #cell(quotedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 6 },
                    }"
                    >{{ data.item.stats.quoted }}
                  </b-link>
                </template>
                <template #cell(rejectedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 7 },
                    }"
                    >{{ data.item.stats.rejected }}
                  </b-link>
                </template>

                <template #cell(approvedInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 8 },
                    }"
                    >{{ data.item.stats.approved }}
                  </b-link>
                </template>
                <template #cell(paidInquiry)="data">
                  <b-link
                    :to="{
                      name: 'inquiries',
                      params: { assigned_to: data.item.name, status: 9 },
                    }"
                    >{{ data.item.stats.paid }}
                  </b-link>
                </template>
                <template #cell(supplying_brands)="data">
                  <span
                    v-for="item in data.item.supplying_brands"
                    :key="item.id"
                    >{{ item.name }} ,</span
                  >
                </template>
                <template #cell(product_categories)="data">
                  <span
                    v-for="item in data.item.product_categories"
                    :key="item.id"
                    >{{ item.title }} ,</span
                  >
                </template>
                <template #cell(action)="data">
                <b-dropdown
                  droptop
                  size="sm"
                  text="Actions"
                  variant="gradient-primary"
                >
                  <b-dropdown-item :to="`/suppliers/${data.item.id}`">
                    <small>Edit</small>
                  </b-dropdown-item>

                  <!-- <b-dropdown-item
                    @click="deleteUser(data.itam.id, data.item.name)"
                  >
                    <small>Delete User</small>
                  </b-dropdown-item> -->
                </b-dropdown>
              </template>
              </b-table>

              <b-pagination
                class="ml-1"
                v-model="page"
                :per-page="perPage"
                :total-rows="count"
              ></b-pagination>
            </div>

            <b-card v-else class="text-center">
              <h4 class="text-secondary mb-0">No records to show ..</h4>
            </b-card>
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
      this.getData();
    },
  },
  methods: {
    getData() {
      axios
        .get("/admin/suppliers", {
          params: { page: this.page, per_page: this.perPage, ...this.form },
        })
        .then((response) => {
          this.suppliers = response.data.data;
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
      this.name = this.selectedUserType = this.dateFrom = this.dateTo = null;
    },
    deleteUser(id, name) {
      axios.delete("/admin/users/team-members", id).then((res) => {
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
      });
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
        { key: "company", label: "Company", sortable: true },
        { key: "email", label: "email", sortable: true },
        { key: "phone", label: "Phone", sortable: true },
        { key: "mobile", label: "Mobile", sortable: true },
        { key: "website", label: "Website", sortable: true },
        { key: "in_charge", label: "In charge", sortable: true },
        { key: "country", label: "Country", sortable: true },
        { key: "customer.name", label: "Customer", sortable: true },
        { key: "supplying_brands", label: "Supplying brands", sortable: true },
        {
          key: "product_categories",
          label: "Product categories",
          sortable: true,
        },
        { key: "action", label: "Action" },
      ],
      suppliers: [],
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
      form: {},
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
