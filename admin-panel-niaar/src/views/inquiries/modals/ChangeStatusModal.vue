<template>
  <b-modal
    id="change-status"
    :visible="showChangeStatusModal"
    title="Change Status"
    size="lg"
    static
    @change="updateModalVal"
  >
    <b-row>
      <b-col cols="12" md="6">
        <b-form-group label="To Status:">
          <v-select
            v-if="options"
            v-model="form.status"
            label="name"
            :options="options"
            :reduce="(option) => option.key"
          />
        </b-form-group>
      </b-col>
      <b-col cols="12">
        <b-form-group label="Reason: " label-for="reason">
          <b-form-textarea id="reason" v-model="form.reason" />
        </b-form-group>
      </b-col>
    </b-row>

    <template #modal-footer>
      <b-button
        :disabled="!!form.reason == false || !!form.status == false || loading"
        variant="gradient-primary"
        size="sm"
        @click="submit()"
      >
        <feather-icon icon="CheckIcon" />
        <span class="align-middle"> Save </span>
      </b-button>

      <b-button
        :disabled="loading"
        @click="$bvModal.hide('change-status')"
        variant="outline-secondary"
        size="sm"
      >
        <span class="align-middle"> Cancel </span>
      </b-button>
    </template>
  </b-modal>
</template>

<script>
export default {
  model: {
    prop: "showChangeStatusModal",
    event: "update:show-change-status-modal",
  },
  props: {
    showChangeStatusModal: {
      type: Boolean,
      required: true,
    },
    status: String,
    id: Number,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {},
      loading: false,
    };
  },
  computed: {
    options() {
      // on progress
      if (this.status == "on_progress") {
        return [{ name: "Quotation prepared", key: "quotation_prepared" }];
      }

      // approved
      else if (
        this.status == "approved" &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant")
      ) {
        return [{ name: "Paid", key: "paid" }];
      } else if (
        this.status == "approved" &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "procurement")
      ) {
        return [
          { name: "Paid", key: "paid" },
          { name: "Rejected", key: "rejected" },
        ];
      }
      // approved

      // paid
      else if (
        this.status == "paid" &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "accountant")
      ) {
        return [
          { name: "On progress", key: "on_progress" },
          { name: "Ordered", key: "ordered" },
        ];
      }
      // paid
      else if (
        this.status == "ordered" &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant")
      ) {
        return [{ name: "Supplier paid", key: "supplier_paid" }];
      } else if (
        this.status == "ordered" &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "team-leader" ||
          this.userData.role == "procurement" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant")
      ) {
        return [{ name: "Paid", key: "paid" }];
      }
    },
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      this.loading = true;
      axios
        .post(`/admin/inquiries/${this.id}/statuses`, this.form)
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
    vSelect,
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
import axios from "@axios";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
