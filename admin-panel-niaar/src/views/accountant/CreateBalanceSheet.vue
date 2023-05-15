<template>
  <validation-observer ref="addfutureDues" #default="{ invalid }">
    <b-modal
      id="futureDues"
      :visible="showCreateFutureDuesModal"
      title="Create New Balance Sheet"
      size="lg"
      static
      @change="updateModalVal"
    >
      <b-form-group label="Category :">
        <validation-provider
          #default="{ errors }"
          name="category"
          rules="required"
        >
          <v-select
            v-model="form.category"
            :options="orders"
            :reduce="(option) => option.key"
            label="text"
          > 
          </v-select>
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="Client :">
        <validation-provider
          #default="{ errors }"
          name="Client"
        >
          <v-select
            v-if="clients"
            v-model="form.customer_id"
            :options="clients"
            :reduce="(option) => option.id"
            label="abbreviation"
          >
            <template v-slot:option="option">
              {{ option.abbreviation }} -
              {{ option.name }}
            </template>
          </v-select>
          <div v-else>loading...</div>
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="Supplier :">
        <validation-provider
          #default="{ errors }"
          name="debit"
          rules="required"
        >
          <v-select
            v-if="suppliers"
            v-model="form.supplier_id"
            :options="suppliers"
              label="company"
            :reduce="(option) => option.id"
          >
            
          </v-select>
          <div v-else>loading...</div>
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="paid">
        <validation-provider #default="{ errors }" name="paid" rules="required">
          <b-form-input
            v-model="form.debit"
            placeholder="paid"
            name="paid"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

      <b-form-group label="recieved">
        <validation-provider
          #default="{ errors }"
          name="recieved"
          rules="required"
        >
          <b-form-input
            v-model="form.credit"
            placeholder="recieved"
            name="recieved"
            type="number"
          />
          <small class="text-danger">{{ errors[0] }}</small>
        </validation-provider>
      </b-form-group>

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
          :rules="form.category === 'order_related' ? required : ''"
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

      <b-form-group label="Remark">
        <validation-provider
          #default="{ errors }"
          name="Remark"
          rules="required"
        >
          <b-form-textarea
            v-model="form.remark"
            placeholder="Remark"
            name="Remark"
          />
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
            rows="4"
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
      clients: [],
      suppliers: [],
      orders: [
        { key: "internal_charges", text: "Internal charges" },
        { key: "order_related", text: "order related" },
        { key: "salary", text: "Salary" },
        { key: "vat", text: "VAT" },
        { key: "tax", text: "TAX" },
        { key: "petty", text: "Petty" },
        { key: "custom_duty-refund", text: "Custom Duty refund" },
      ],
    };
  },
  mounted() {
    this.form.is_paid = false;
    this.getClientsData();
    this.getSuppliers();
  },
  methods: {
    getSuppliers() {
      axios.get("/admin/suppliers").then((response) => {
        this.suppliers = response.data.data;
      });
    },
    getClientsData() {
      axios.get("/admin/users?role=client").then((response) => {
        this.clients = response.data.data;
      });
    },
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit1() {
      this.loading = true;

      if (this.form.is_paid) this.form.is_paid = 1;
      else this.form.is_paid = 0;
      axios
        .post("/admin/accounting/balance_sheet", this.form)
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
    VSelect,
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
import VSelect from "vue-select";
import axios from "@axios";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
