<template>
  <validation-observer ref="assignForm" #default="{ invalid }">
    <b-modal
      id="Assign"
      :visible="showAssignModal"
      title="Re assign inquiry"
      size="lg"
      static
      @change="updateModalVal"
    >
      <b-row>
        <b-col cols="12" md="6">
          <b-form-group label="Re assign To:">
            <v-select
              v-if="teamMembers"
              v-model="assignTo"
              label="name"
              :options="teamMembers"
            />
          </b-form-group>
        </b-col> 
      </b-row>

      <template #modal-footer>
        <b-button
          variant="gradient-primary"
          size="sm"
          :disabled="invalid || isLoading"
          @click="submit()"
        >
          <feather-icon icon="CheckIcon" />
          <span class="align-middle"> Save </span>
        </b-button>

        <b-button
          @click="$bvModal.hide('Assign')"
          variant="outline-secondary"
          size="sm"
          :disabled="isLoading"
        >
          <span class="align-middle"> Cancel </span>
        </b-button>
      </template>
    </b-modal>
  </validation-observer>
</template>

<script>
export default {
  model: {
    prop: "showAssignModal",
    event: "update:show-re-assign-modal",
  },
  props: {
    showAssignModal: {
      type: Boolean,
      required: true,
    },
    id: Number,
  },
  mounted() {
    this.getData();
  },
  data() {
    return {
      assignTo: {},
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {
        action: "re_assign",
      },
      teamMembers: null,
      isLoading: false,
    };
  },
  methods: {
    getData() {
      axios
        .get("admin/users/team-members?per_page=100000&page=1")
        .then((response) => {
          this.teamMembers = response.data.data;
        });
    },
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      this.isLoading = true;
      this.form.assign_to = this.assignTo.id;
      this.$refs.assignForm.validate().then((success) => {
        if (success) {
          axios
            .post(`/admin/inquiries/${this.id}/assign`, this.form)
            .then((response) => {
              this.isLoading = false;

              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: "Success",
                  icon: "CoffeeIcon",
                  variant: "success",
                  text: `You have successfully re assign inquiry ${this.id} to ${this.assignTo.name} !`,
                },
              });
              this.$bvModal.hide("Assign");
              this.$emit("refresh");
              window.location.reload();
              this.from = {};
            })
            .catch((err) => {
              console.error(err);
              this.isLoading = true;
              this.$bvModal.hide("Assign");
            });
        }
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
    ValidationProvider,
    ValidationObserver,
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
