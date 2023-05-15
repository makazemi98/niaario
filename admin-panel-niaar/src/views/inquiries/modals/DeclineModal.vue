<template>
  <b-modal
    id="decline"
    :visible="showDeclineModal"
    title="Compose Email"
    size="lg"
    static
    @change="updateModalVal"
  >
    <b-row>
      <b-col cols="12">
        <b-form-group label="Reason" label-for="inq-decline-reason">
          <b-form-textarea id="inq-decline-reason" v-model="form.reason" />
        </b-form-group>
      </b-col>
    </b-row>

    <template #modal-footer>
      <b-button variant="gradient-primary" size="sm" @click="submit()">
        <feather-icon icon="CheckIcon" />
        <span class="align-middle"> Save </span>
      </b-button>

      <b-button
        @click="$bvModal.hide('reAssign')"
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
    prop: "showDeclineModal",
    event: "update:show-decline-modal",
  },
  props: {
    showDeclineModal: {
      type: Boolean,
      required: true,
    },
    id: Number,
  },
  data() {
    return {
      info: null,
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {
        action: "cancel",
      },
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      axios
        .post(`/admin/inquiries/${this.id}/actions`, this.form)
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully declined inquiry ${this.id} !`,
            },
          });
          this.$bvModal.hide("decline");
          this.from = {};
          this.$emit("refresh");
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
