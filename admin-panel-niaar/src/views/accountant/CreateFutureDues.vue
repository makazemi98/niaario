<template>
  <validation-observer ref="addfutureDues" #default="{ invalid }">
    <b-modal
      id="futureDues"
      :visible="showCreateFutureDuesModal"
      title="Create New Future Dues"
      size="lg"
      static
      @change="updateModalVal"
    >
      <b-form-group label="debit">
        <validation-provider
          #default="{ errors }"
          name="debit"
          rules="required"
        >
          <b-form-input
            v-model="form.debit"
            placeholder="debit"
            name="debit"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="credit">
        <validation-provider
          #default="{ errors }"
          name="credit"
          rules="required"
        >
          <b-form-input
            v-model="form.credit"
            placeholder="credit"
            name="credit"
            type="number"
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

      <b-form-group label="Is paid">
        <validation-provider
          #default="{ errors }"
          name="Is paid"
          rules="required"
        >
          <b-form-checkbox
            v-model="form.is_paid"
            name="is_paid"
            class="mr-0"
            switch
            inline
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
          rules="required"
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
          :disabled="invalid || loading"
          variant="gradient-primary"
          size="sm"
          @click="submit1()"
        >
          <feather-icon icon="CheckIcon" />
          <span class="align-middle"> Save </span>
        </b-button>

        <b-button
          :disabled="loading"
          @click="$bvModal.hide('futureDues')"
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
    prop: "showCreateFutureDuesModal",
    event: "update:show-create-future-dues-modal",
  },
  props: {
    showCreateFutureDuesModal: {
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
  mounted() {
    this.form.is_paid = false;
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit1() {
      this.loading = true;

      if (this.form.is_paid) this.form.is_paid = 1;
      else this.form.is_paid = 0;
      axios
        .post("/admin/accounting/future_dues", this.form)
        .then((response) => {
          this.loading = false;

          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: "You have successfully add new future dues to accounting !",
            },
          });
          this.$bvModal.hide("futureDues");
          window.location.reload();
          this.$emit("refresh");
          window.location.reload();
          this.form = {};
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
