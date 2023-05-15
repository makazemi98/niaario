<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-between align-items-center">
          <div
            class="d-inline-flex flex-row align-items-center justify-content-start"
          >
            <b-avatar size="lg" rounded="sm" variant="light-primary">
              <feather-icon
                icon="BarChart2Icon"
                style="transform: scale(1.7)"
              />
            </b-avatar>
            <div
              class="d-flex flex-column justify-content-start align-items-start ml-1"
            >
              <h6>Manage Accountant</h6>
              <b-breadcrumb class="breadcrumb-slash p-0">
                <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
                <b-breadcrumb-item active> Accountant </b-breadcrumb-item>
              </b-breadcrumb>
            </div>
          </div>
          <div
            v-if="userData.role !== 'client' && userData.role !== 'procurement'"
          >
          <b-button
              class="mr-1"
              variant="gradient-danger"
              size="sm"
              @click="showCreateBalanceSheetModal = true"
            >
              <feather-icon icon="PlusIcon" class="mr-50" />
              <span class="align-middle"> Add new Transaction <small>(balance sheet)</small></span>
            </b-button>

            <b-button
              class="mr-1"
              variant="gradient-success"
              size="sm"
              @click="showCreateFutureDuesModal = true"
            >
              <feather-icon icon="PlusIcon" class="mr-50" />
              <span class="align-middle"> Add new Future Dues</span>
            </b-button>
            <b-button
              class="mr-1"
              variant="gradient-primary"
              size="sm"
              @click="showCreatePaymentModal = true"
            >
              <feather-icon icon="PlusIcon" class="mr-50" />
              <span class="align-middle"> Add new Payment</span>
            </b-button>
            <b-button
              variant="gradient-info"
              size="sm"
              @click="showCreatePettyModal = true"
            >
              <feather-icon icon="PlusIcon" class="mr-50" />
              <span class="align-middle"> Add new Petty</span>
            </b-button>
          </div>
        </div>
      </b-col>
    </b-row>

    <b-tabs>
      <!-- Balance Sheet -->
      <b-tab
        active
        v-if="userData.role !== 'client' && userData.role !== 'procurement'"
      >
        <template #title>
          <span>Balance Sheet</span>
        </template>
        <balance-sheet />
      </b-tab>
      <!-- Balance Sheet -->

      <!-- Payments -->
      <b-tab>
        <template #title>
          <span>Payments</span>
        </template>

        <payments />
      </b-tab>
      <!-- Payments -->

      <!-- Future Dues -->
      <b-tab>
        <template #title>
          <span>Future Dues</span>
        </template>

        <future-dues />
      </b-tab>
      <!-- Future Dues -->

      <!-- Petty -->
      <b-tab
        v-if="userData.role !== 'client' && userData.role !== 'procurement'"
      >
        <template #title>
          <span>Petty</span>
        </template>

        <petty />
      </b-tab>
      <!-- Petty -->

      <!-- Profit -->
      <b-tab
        v-if="userData.role !== 'client' && userData.role !== 'procurement'"
      >
        <template #title>
          <span>Profit</span>
        </template>

        <profit />
      </b-tab>
      <!-- Profit -->

      <!-- Users with credit in hand -->
      <b-tab
        v-if="userData.role !== 'client' && userData.role !== 'procurement'"
      >
        <template #title>
          <span>Users with credit in hand</span>
        </template>

        <UsersWithCreditInHand />
      </b-tab>
      <!-- Users with credit in hand -->
    </b-tabs>

    <!-- modals -->
    
    <create-balance-sheet @update="updateCreateBalanceSheet" v-model="showCreateBalanceSheetModal" />

    <create-future-dues @update="updateCreateFutureDues" v-model="showCreateFutureDuesModal" />

    <create-petty @update="updateCreatePetty" v-model="showCreatePettyModal" />

    <create-payment  @update="updateCreatePayment" v-model="showCreatePaymentModal" />
  </section>
</template>

<script>
export default {
  directives: {
    "b-toggle": VBToggle,
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
  },
  methods: {
    updateCreateBalanceSheet(val) {
      this.showCreateBalanceSheetModal = val;
    },
    updateCreateFutureDues(val) {
      this.showCreateFutureDuesModal = val;
    },
    updateCreatePayment(val) {
      this.showCreatePaymentModal = val;
    },
    updateCreatePetty(val) {
      this.showCreatePettyModal = val;
    },
  },
  data() {
    return {
      showCreateBalanceSheetModal: false,
      showCreateFutureDuesModal: false,
      showCreatePaymentModal: false,
      showCreatePettyModal: false,
      getData: false,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
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
    BalanceSheet,
    FutureDues,
    Payments,
    Petty,
    Profit,
    UsersWithCreditInHand,
    CreateFutureDues,
    CreatePayment,
    CreatePetty,
    CreateBalanceSheet,
  },
};
import BalanceSheet from "./BalanceSheet.vue";
import FutureDues from "./FutureDues.vue";
import Payments from "./Payments.vue";
import Petty from "./Petty.vue";
import Profit from "./Profit.vue";
import UsersWithCreditInHand from "./UsersWithCreditInHand.vue";
import CreateBalanceSheet from "./CreateBalanceSheet.vue";
import CreateFutureDues from "./CreateFutureDues.vue";
import CreatePayment from "./CreatePayment.vue";
import CreatePetty from "./CreatePetty.vue";
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
