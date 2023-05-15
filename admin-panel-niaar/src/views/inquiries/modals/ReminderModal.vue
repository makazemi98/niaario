<template>
  <b-modal
    id="reminder"
    :visible="showReminderModal"
    title="Reminder"
    size="lg"
    static
    @change="updateModalVal"
  >
    <b-row>
      <b-col cols="12" md="6">
        <b-form-group label="Date" label-for="reminder_date">
          <b-form-datepicker
            v-model="form.reminder_date"
            id="reminder_date"
            placeholder="Reminder date"
          />
        </b-form-group>
      </b-col>

      <b-col cols="12">
        <b-form-group label="Title" label-for="title">
          <b-form-input id="title" v-model="form.title" />
        </b-form-group>
      </b-col>

      <b-col cols="12">
        <b-form-group label="Description" label-for="reminder-desc">
          <b-form-textarea id="reminder-desc" v-model="form.body" />
        </b-form-group>
      </b-col>
    </b-row>

    <template #modal-footer>
      <b-button
        :disabled="!form.title || !form.body || !form.reminder_date || loading"
        variant="gradient-primary"
        size="sm"
        @click="submit()"
      >
        <feather-icon icon="CheckIcon" />
        <span class="align-middle"> Save </span>
      </b-button>

      <b-button
        @click="$bvModal.hide('reminder')"
        variant="outline-secondary"
        size="sm"
        :disabled="loading"
      >
        <span class="align-middle"> Cancel </span>
      </b-button>
    </template>
  </b-modal>
</template>

<script>
export default {
  model: {
    prop: "showReminderModal",
    event: "update:show-reminder-modal",
  },
  props: {
    showReminderModal: {
      type: Boolean,
      required: true,
    },
    id: Number,
  },
  data() {
    return {
      form: {
        reminder_date: "",
        title: "",
        body: "",
      },
      loading: false,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      this.loading = true;
      this.form.user_id = this.userData.id;
      this.form.inquiry_id = this.id;
      axios
        .post(`/admin/reminders`, this.form)
        .then((response) => {
          this.loading = false;

          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully set reminder for inquiry ${this.id}!`,
            },
          });
          this.$bvModal.hide("reminder");
          this.$emit("refresh");
          this.from = {
            reminder_date: "",
            title: "",
            body: "",
          };
          window.location.reload();
        })
        .catch((err) => {
          console.error(err);
          this.loading = false;

          window.location.reload();
        });
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
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
