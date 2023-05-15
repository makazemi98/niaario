<template>
  <div v-if="info">
    <b-row>
      <b-col md="8" lg="9">
        <b-tabs>
          <!-- LOG -->
          <b-tab active>
            <template #title>
              <feather-icon icon="CrosshairIcon" />
              <span>LOG</span>
            </template>
            <log :statuses="statuses" />
          </b-tab>
          <!-- LOG -->

          <!-- DOCS -->
          <b-tab>
            <template #title>
              <feather-icon icon="FolderIcon" />
              <span>DOCS</span>
            </template>

            <documents :statuses="statuses" />
          </b-tab>
          <!-- DOCS -->

          <div v-if="userData.role !== 'client'">
            <!-- CONFIDENTIAL -->
            <b-tab>
              <template #title>
                <feather-icon icon="LockIcon" />
                <span>CONFIDENTIAL</span>
              </template>

              <confidential :statuses="statuses" />
            </b-tab>
            <!-- CONFIDENTIAL -->

            <!-- CALCULATION -->
            <b-tab>
              <template #title>
                <feather-icon icon="PercentIcon" />
                <span>CALCULATION</span>
              </template>

              <calculation :info="info" :statuses="statuses" />
            </b-tab>
            <!-- CALCULATION -->

            <!-- REMINDERS -->
            <b-tab v-if="userData.role !== 'client'">
              <template #title>
                <feather-icon icon="BellIcon" />
                <span>REMINDERS</span>
              </template>

              <reminders :statuses="statuses" />
            </b-tab>
            <!-- REMINDERS -->
          </div>
        </b-tabs>
      </b-col>
      <b-col md="4" lg="3">
        <status-log :statuses="statuses" />
        <div class="buttons">
          <b-button
            v-if="showCancelButton"
            block
            variant="outline-secondary"
            size="sm"
            @click="showCancelModal = true"
          >
            <feather-icon icon="XIcon" />
            <span class="align-middle"> Cancel </span>
          </b-button>

          <b-button
            v-if="showAssignButton"
            block
            variant="gradient-warning"
            size="sm"
            @click="showAssignModal = true"
          >
            <feather-icon icon="NavigationIcon" />
            <span class="align-middle"> Assign To </span>
          </b-button>
          <b-button
            v-if="showReAssignButton"
            block
            variant="gradient-warning"
            size="sm"
            @click="showReAssignModal = true"
          >
            <feather-icon icon="NavigationIcon" />
            <span class="align-middle"> Re assign </span>
          </b-button>

          <b-button
            v-if="showStartWorkButton"
            block
            variant="gradient-primary"
            size="sm"
            @click="updateStatus('on_progress')"
          >
            <feather-icon icon="TargetIcon" />
            <span class="align-middle"> Start Work </span>
          </b-button>

          <b-button
            v-if="showApproveButton"
            @click="
              showApprovedModal = true;
              approved();
            "
            block
            variant="gradient-success"
            size="sm"
          >
            <feather-icon icon="CheckIcon" />
            <span class="align-middle"> Approve </span>
          </b-button>

          <b-button
            v-if="showDeclineButton"
            @click="showDeclineModal = true"
            block
            variant="outline-danger"
            size="sm"
          >
            <feather-icon icon="XIcon" />
            <span class="align-middle"> Decline </span>
          </b-button>

          <b-button
            @click="showCommentModal = true"
            block
            variant="gradient-info"
            size="sm"
          >
            <feather-icon icon="MessageCircleIcon" />
            <span class="align-middle"> Comment </span>
          </b-button>

          <b-button
            v-if="showChangeStatusButton"
            @click="showChangeStatusModal = !showChangeStatusModal"
            block
            variant="gradient-dark"
            size="sm"
          >
            <feather-icon icon="RepeatIcon" />
            <span class="align-middle"> Change Status </span>
          </b-button>

          <!-- <b-button
            v-if="showSelectSupplierButton"
            block
            variant="gradient-secondary"
            size="sm"
            @click="showSelectSupplierModal = true"
          >
            <feather-icon icon="CodesandboxIcon" />
            <span class="align-middle"> Select Supplier </span>
          </b-button> -->

          <b-button
            @click="showReminderModal = true"
            block
            variant="gradient-danger"
            size="sm"
          >
            <feather-icon icon="BellIcon" />
            <span class="align-middle"> Set Reminder </span>
          </b-button>
        </div>
      </b-col>
    </b-row>

    <!-- modals -->
    <assign-modal
      v-model="showAssignModal"
      @update="updateAssign"
      :id="info.id"
      @refresh="getLogData()"
    />

    <re-assign-modal
      v-model="showReAssignModal"
      @update="updateReAssign"
      :id="info.id"
      @refresh="getLogData()"
    />

    <comment-modal
      :id="info.id"
      v-model="showCommentModal"
      @update="updateComment"
      @refresh="getLogData()"
    />

    <change-status-modal
      v-model="showChangeStatusModal"
      :status="info.status"
      :id="info.id"
      @update="updateChangeStatus"
      @refresh="getLogData()"
    />

    <cancel-modal
      v-model="showCancelModal"
      :status="info.status"
      :id="info.id"
      @update="updateCancel"
    />

    <reminder-modal
      v-model="showReminderModal"
      :status="info.status"
      :id="info.id"
      @update="updateReminder"
      @refresh="getReminderData()"
    />

    <decline-modal
      v-model="showDeclineModal"
      :id="info.id"
      @update="updateComment"
      @refresh="getLogData()"
    />

    <!-- <select-supplier-modal
      v-model="showSelectSupplierModal"
      :id="info.id"
      @update="updateSelectSupplier"
      @refresh="getLogData()"
    /> -->

    <!-- modals -->
  </div>
</template>

<script>
import {
  BTabs,
  BCard,
  BButton,
  BTab,
  BCardText,
  BRow,
  BCol,
} from "bootstrap-vue";
import axios from "@axios";

export default {
  props: {
    id: [Number, String],
  },
  components: {
    BCardText,
    BCard,
    BButton,
    BTabs,
    BTab,
    BRow,
    BCol,
    log: () => import("./log.vue"),
    confidential: () => import("./confidential.vue"),
    documents: () => import("./documents.vue"),
    calculation: () => import("./calculation.vue"),
    reminders: () => import("./reminders.vue"),
    StatusLog: () => import("./statusLog.vue"),
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    AssignModal: () => import("./modals/AssignModal.vue"),
    ChangeStatusModal: () => import("./modals/ChangeStatusModal.vue"),
    CommentModal: () => import("./modals/CommentModal.vue"),
    DeclineModal: () => import("./modals/DeclineModal.vue"),
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    ReminderModal: () => import("./modals/ReminderModal.vue"),
    SelectSupplierModal: () => import("./modals/SelectSupplierModal.vue"),
    CancelModal: () => import("./modals/CancelModal.vue"),
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      statuses: null,
      info: null,
      showCancelModal: false,
      showAssignModal: false,
      showReAssignModal: false,
      showApprovedModal: false,
      showDeclineModal: false,
      showCommentModal: false,
      showChangeStatusModal: false,
      showSelectSupplierModal: false,
      showReminderModal: false,
    };
  },
  mounted() {
    try {
      this.getData();
      this.getLogData();
    } catch (error) {
      // console.log(error);
    }
  },
  methods: {
    updateStatus(status) {
      this.loading = true;
      axios
        .post(`/admin/inquiries/${this.id}/statuses`, {
          status,
          reason: `the reason : ${status}`,
        })
        .then((response) => {
          this.loading = false;

          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully updated the status of ${this.id} inquiry!`,
            },
          });
          window.location.reload();
        })
        .catch((error) => {
          this.loading = false;

          console.error(error);
        });
      this.$bvModal.hide("change-status");
      this.form = {};
      this.$emit("refresh");
    },
    getData() {
      axios
        .get(`/admin/inquiries/${this.$route.params.id}/statuses`, {})
        .then((response) => {
          this.statuses = response.data.data;
        });
    },
    getLogData() {
      axios
        .get(`/admin/inquiries/${this.$route.params.id}/log`, {})
        .then((response) => {
          this.info = response.data.data;
        });
    },
    approved() {
      axios
        .post(`/admin/inquiries/${this.info.id}/statuses`, {
          status: "approved",
          reason: `approved`,
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
          window.location.reload();
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
    updateCancel(val) {
      this.showCancelModal = val;
    },
    updateReminder(val) {
      this.showReminderModal = val;
    },
    updateReAssign(val) {
      this.showReAssignModal = val;
    },
    updateAssign(val) {
      this.showAssignModal = val;
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

    showAssignButton() {
      if (
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "team-leader") &&
        this.info.status == "open"
      )
        return true;
      else return false;
    },

    showReAssignButton() {
      if (
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "procurement") &&
        this.info.status !== "open"
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
        (this.userData.role == "manager" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "procurement") &&
        (this.info.status == "assigned" || this.info.status == "re_assign")
      )
        return true;
    },

    showSelectSupplierButton() {
      if (this.info.status == "on_progress" && !this.info.supplier) return true;
      else return false;
    },
  },
};
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
