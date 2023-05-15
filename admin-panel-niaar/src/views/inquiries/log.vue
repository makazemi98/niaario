<template>
  <div>
    <div v-if="info">
      <div class="d-flex justify-content-between mb-1">
        <span>
          BY: <b>{{ info.creator.name }}</b>
          <small v-if="info.creator.abbreviation">
            ({{ info.creator.abbreviation }})</small
          >
        </span>

        <span> {{ info.created_at | formatDate }}</span>
      </div>
      <b-card>
        <h3>title : {{ info.title }}</h3>
        <h4>INQ NUM : {{ info.id }}</h4>
      </b-card>
      <b-card>
        qty : {{ info.qty }}
        <p>{{ info.description }}</p>
      </b-card>
      <hr />
      <div v-for="item in info.comments" :key="item.id">
        <div class="d-flex justify-content-between mb-1">
          <span>
            BY: <b>{{ item.creator.name }}</b>
            <small v-if="item.creator.abbreviation">
              ({{ item.creator.abbreviation }})</small
            >
          </span>

          <span> {{ item.created_at | formatDate }}</span>
        </div>
        <b-card> {{ item.body }} </b-card>
      </div>
    </div>
    <div v-else class="text-center flex">
      <b-spinner />
    </div>
  </div>
</template>

<script>
import axios from "@axios";

export default {
  props: {
    statuses: { type: Object, default: () => [] },
  },
  data() {
    return {
      info: null,
      userData: JSON.parse(localStorage.getItem("userData")),
      showReAssignModal: false,
      showApprovedModal: false,
      showDeclineModal: false,
      showCommentModal: false,
      showChangeStatusModal: false,
      showSelectSupplierModal: false,
      showReminderModal: false,
    };
  },
  computed: {
    showDeclineButton() {
      if (
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant" ||
          this.userData.role == "team-leader") &&
        this.info.status == "quotation_prepared"
      )
        return true;
      else return false;
    },
    showApproveButton() {
      if (
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant" ||
          this.userData.role == "team-leader") &&
        this.info.status == "quotation_prepared"
      )
        return true;
      else return false;
    },

    showCancelButton() {
      if (
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "client" ||
          this.userData.role == "procurement" ||
          this.userData.role == "team-leader") &&
        (this.info.status == "open" ||
          this.info.status == "assigned" ||
          this.info.status == "on_progress")
      )
        return true;
      else return false;
    },

    showReAssignButton() {
      if (
        this.userData.role == "super-admin" ||
        this.userData.role == "manager" ||
        this.userData.role == "client" ||
        this.userData.role == "procurement"
      )
        return true;
      else return false;
    },

    showChangeStatusButton() {
      if (
        this.info.status == "on_progress" ||
        this.info.status == "approved" ||
        this.info.status == "paid" ||
        this.info.status == "ordered"
      )
        return true;
      else return false;
    },

    showStartWorkButton() {
      if (
        (this.userData.role == "team-leader" ||
          this.userData.role == "procurement") &&
        this.info.status == "assigned"
      )
        return true;
    },

    showSelectSupplierButton() {
      if (this.info.status == "on_progress" && !this.info.supplier) return true;
      else return false;
    },
  },
  mounted() {
    this.getLogData();
  },
  methods: {
    getLogData() {
      axios
        .get(`/admin/inquiries/${this.$route.params.id}/log`, {})
        .then((response) => {
          this.info = response.data.data;
        });
    },
    approved() {
      axios
        .post(`/admin/inquiries/${this.info.id}/change-status`, {
          status: "approved",
        })
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully updated the status of ${this.info.id} inquiry!`,
            },
          });
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
    getReminderData() {
      this.loadRemider = true;
      axios
        .get("/admin/reminders", {
          params: { inquiry_id: this.$route.params.id },
        })
        .then((response) => {
          this.allRemiders = response.data.data;
          this.loadRemider = false;
        });
    },
    updateChangeStatus(val) {
      this.showChangeStatusModal = val;
    },
    updateReminder(val) {
      this.showReminderModal = val;
    },
    updateReAssaign(val) {
      this.showReAssignModal = val;
    },
    updateApproved(val) {
      this.showApprovedModal = val;
    },
    updateDecline(val) {
      this.showDeclineModal = val;
    },
    updateComment(val) {
      this.showCommentModal = val;
    },
    updateSelectSupplier(val) {
      this.showSelectSupplierModal = val;
    },
  },
  components: {
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
    VBTooltip,
    BModal,
    BTabs,
    BTab,
    BCardText,
    BForm,
    BInputGroup,
    BInputGroupPrepend,
    BFormTextarea,
    BFormRadioGroup,
    BFormFile,
    VBPopover,
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    ChangeStatusModal: () => import("./modals/ChangeStatusModal.vue"),
    CommentModal: () => import("./modals/CommentModal.vue"),
    DeclineModal: () => import("./modals/DeclineModal.vue"),
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    ReminderModal: () => import("./modals/ReminderModal.vue"),
    SelectSupplierModal: () => import("./modals/SelectSupplierModal.vue"),
  },
};
import {
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
  VBTooltip,
  BModal,
  BTabs,
  BTab,
  BCardText,
  BForm,
  BInputGroup,
  BInputGroupPrepend,
  BFormTextarea,
  BFormRadioGroup,
  BFormFile,
  VBPopover,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
