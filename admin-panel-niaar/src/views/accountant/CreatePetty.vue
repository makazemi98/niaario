<template>
  <validation-observer ref="addpetty" #default="{ invalid }">
    <b-modal
      id="petty"
      :visible="showCreatePettyModal"
      title="Create New Petty"
      size="lg"
      static
      @change="updateModalVal"
    >
      <!-- <small class="text-grey-300"
        >If you fill in the "Debit" field, the "Credit" amount will be 0, and if you fill in the "Credit" field, the "Debit" amount will be 0.</small
      > -->
      <b-form-group label="Invoice No">
        <validation-provider
          #default="{ errors }"
          name="Invoice No"
          rules="required"
        >
          <b-form-input
            v-model="form.invoice_no"
            placeholder="invoice No"
            name="invoice_no"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>
      
      <b-form-group label="debit">
        <validation-provider #default="{ errors }" name="debit">
          <b-form-input
            v-model="form.debit"
            placeholder="debit"
            name="debit"
            type="number"
            :disabled="!!form.credit"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="credit">
        <validation-provider #default="{ errors }" name="credit">
          <b-form-input
            v-model="form.credit"
            placeholder="credit"
            name="credit"
            type="number"
            :disabled="!!form.debit"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <!-- <b-form-group label="balance">
        <validation-provider
          #default="{ errors }"
          name="balance"
          rules="required"
        >
          <b-form-input
            v-model="form.balance"
            placeholder="balance"
            name="balance"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group> -->

      <b-form-group label="invoice no">
        <validation-provider
          #default="{ errors }"
          name="invoice no"
          rules="required"
        >
          <b-form-input
            v-model="form.invoice_no"
            placeholder="invoice no"
            name="invoice no"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="Date" label-for="date">
        <validation-provider #default="{ errors }" name="Date" rules="required">
          <b-form-datepicker v-model="form.date" id="date" placeholder="Date" />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="Inquiry ID">
        <validation-provider
          #default="{ errors }"
          name="Inquiry ID"
          rules="required"
        >
          <b-form-input
            v-model="form.inquiry_id"
            placeholder="Inquiry ID"
            name="Inquiry ID"
            type="number"
          />
          <b-link target="_blank" to="/inquiries">Go to Inquiry List </b-link>
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="Description">
        <validation-provider
          #default="{ errors }"
          name="Description"
          rules="required|min:6"
        >
          <b-form-textarea
            v-model="form.description"
            placeholder="Description"
            name="Description"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <template #modal-footer>
        <b-button
          :disabled="invalid || (!form.debit && !form.credit)"
          variant="gradient-primary"
          size="sm"
          @click="submit3()"
        >
          <feather-icon icon="CheckIcon" />
          <span class="align-middle"> Save </span>
        </b-button>

        <b-button
          :disabled="loading"
          @click="$bvModal.hide('petty')"
          variant="outline-secondary"
          size="sm"
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
    prop: "showCreatePettyModal",
    event: "update:show-create-petty-modal",
  },
  props: {
    showCreatePettyModal: {
      type: Boolean,
      required: true,
    },
    id: Number,
  },
  data() {
    return {
      info: null,
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {},
      loading: false,
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit3() {
      this.loading = true;
      if (this.form.credit > 0) this.form.debit = 0;
      if (this.form.debit > 0) this.form.credit = 0;
      axios
        .post("/admin/accounting/petty", this.form)
        .then((response) => {
          this.loading = false;

          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: "You have successfully add new petty to accounting !",
            },
          });
          this.$bvModal.hide("petty");
          window.location.reload();
          this.$emit("refresh");
          window.location.reload();
          this.form = {};
          console.log(response);
        })
        .catch((error) => {
          this.loading = false;
          if(error.response.data.errors)
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
          else this.$toast({
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
    BLink,
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
    ValidationProvider,
    ValidationObserver,
  },
};
import {
  BRow,
  BCol,
  BLink,
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
