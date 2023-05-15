<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-start align-items-center">
          <b-avatar size="lg" rounded="sm" variant="light-primary">
            <feather-icon icon="BellIcon" style="transform: scale(1.7)" />
          </b-avatar>
          <div
            class="d-flex flex-column justify-content-start align-items-start ml-1"
          >
            <h6>Notifications</h6>
            <b-breadcrumb class="breadcrumb-slash p-0">
              <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
              <b-breadcrumb-item active> Notifications </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </div>
      </b-col>
    </b-row>
    <!-- modal -->
    <b-modal
      v-if="showDetailModal"
      id="detailModal"
      :visible="showDetailModal"
      title="Detail Notification"
      size="lg"
      static
    >
      Detail Notification

      <template #modal-footer>
        <b-button variant="gradient-primary" size="sm" @click="submit()">
          <feather-icon icon="CheckIcon" />
          <span class="align-middle"> Save </span>
        </b-button>

        <b-button
          @click="$bvModal.hide('detailModal')"
          variant="outline-secondary"
          size="sm"
        >
          <span class="align-middle"> Cancel </span>
        </b-button>
      </template>
    </b-modal>
    <!-- Modal -->

    <!-- users table -->
    <b-row class="mt-2">
      <b-col cols="12">
        <div v-if="isLoaded">
          <b-card v-if="notifications.length > 0" no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Notification List</h4>
            </div>
            <b-table
              responsive
              :striped="striped"
              :bordered="bordered"
              :outlined="outlined"
              :fields="fields"
              :items="notifications"
            >
              <template #cell(created_at)="data">
                {{ data.item.created_at | formatDate }}
              </template>
              <template #cell(read_at)="data">
                {{ data.item.read_at | formatDate }}
              </template>
              <template #cell(reply_link)="data">
                <router-link :to="data.item.reply_link">Reply Link</router-link>
              </template>

              <!-- <template #cell(action)="data">
                <b-button
                  block
                  variant="outline-warning"
                  size="sm"
                  @click="showDetailModalFunc(data.item)"
                >
                <span class="align-middle"> Edit </span>
              </b-button>
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
            <h4 class="text-secondary mb-0">No records found</h4>
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
  watch: {
    page(newVal) {
      this.getData();
    },
  },
  methods: {
    showDetailModalFunc(data) {
      this.selectedRow = data;
      this.showDetailModal = true;
    },

    getData() {
      axios
        .get("/admin/notifications", {
          params: { page: this.page, per_page: this.perPage },
        })
        .then((response) => {
          // console.log(response);
          this.notifications = response.data.data;
          this.count = response.data.meta.total;

          this.isLoaded = true;
        })
        .catch((error) => (this.isLoaded = true));
    },
  },
  data() {
    return {
      showDetailModal: false,
      isLoaded: false,
      striped: true,
      bordered: true,
      outlined: true,
      fields: [
        { key: "created_at", label: "Created at", sortable: true },
        { key: "notification", label: "Notification", sortable: true },
        { key: "read_at", label: "Read at", sortable: true },
        { key: "reply_link", label: "Link", sortable: true },
        // { key: "action", label: "Action" },
      ],
      notifications: [],
      page: 1,
      perPage: 10,
      count: 0,
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
    BModal,
  },
};
import axios from "@axios";
import {
  BModal,
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
import UserRewardsModal from "../components/users-list/user-rewards/UserRewardsModal.vue";
import UserTransactionsModal from "../components/users-list/user-transactions/UserTransactionsModal.vue";
import UserChangePasswordModal from "../components/users-list/user-change-password/UserChangePasswordModal.vue";
import UserSendEmailModal from "../components/users-list/user-send-email/UserSendEmailModal.vue";
import store from "@/store";
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>
