<template>
  <div>
    <validation-observer ref="addFutureForm" #default="{ invalid }">
      <b-modal
        id="editFutureModal"
        :visible="showEditFutureDues"
        :title='`Edit feature due ${data.id}`'
        size="lg"
        static
        @change="updateModalVal"
      >
      {{data}}
        <b-form-group label="Bill to:">
          <validation-provider
            #default="{ errors }"
            name="Bill to"
            rules="required"
          >
            <v-select
              v-model="form.bill_to"
              label="company"
              :options="[ 'Niaar', 'Supplier', 'Client']"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Payee name:">
          <validation-provider
            #default="{ errors }"
            name="Payee name"
            rules="required"
          >
            <v-select
              v-if="suppliers"
              v-model="form.payee_name"
              label="company"
              :options="suppliers"
              :reduce="(option) => option.id"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Payable amount">
          <validation-provider
            #default="{ errors }"
            name="Payable amount"
            rules="required"
          >
            <b-form-input
              v-model="form.payable_amount"
              placeholder="Payable amount"
              name="Payable amount"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Receivable amount">
          <validation-provider
            #default="{ errors }"
            name="Receivable amount"
            rules="required"
          >
            <b-form-input
              v-model="form.receivable_amount"
              placeholder="Receivable amount"
              name="Receivable amount"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Currency">
          <validation-provider
            #default="{ errors }"
            name="Currency"
            rules="required"
          >
            <b-form-input
              v-model="form.currency"
              placeholder="Currency"
              name="Currency"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Due Date">
          <validation-provider
            #default="{ errors }"
            name="Due Date"
            rules="required"
          >
            <b-form-datepicker name="Due date" v-model="form.due_date" />
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


        <!-- <b-form-group label="Description">
          <validation-provider
            #default="{ errors }"
            name="Description"
            rules="required"
          >
            <b-form-textarea
              v-model="form.desc"
              placeholder="Description"
              name="Description"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group> -->
 

        <template #modal-footer>
          <b-button
            :disabled="invalid"
            variant="gradient-primary"
            size="sm"
            @click="submit()"
          >
            <feather-icon icon="CheckIcon" />
            <span class="align-middle"> Save </span>
          </b-button>

          <b-button
            @click="$bvModal.hide('editFutureModal')"
            variant="outline-secondary"
            size="sm"
          >
            <span class="align-middle"> Cancel </span>
          </b-button>
        </template>
      </b-modal>
    </validation-observer>
  </div>
</template>

<script>
export default {
  model: {
    prop: "showEditFutureDues",
    event: "update:show-edit-feature-dues",
  },
  props: {
    showEditFutureDues: {
      type: Boolean,
      required: true,
    },
    data: {
      suppliers: null,
      type: Object,
      default: () => {},
    },
  }, 
  mounted() {
    this.getSuppliers();
    this.form.bill_to = this.data.bill_to
    this.form.payee_name = this.data.payee_name
    this.form.payable_amount = this.data.payable_amount
    this.form.receivable_amount = this.data.receivable_amount
    this.form.currency = this.data.currency
    this.form.due_date = this.data.due_date
    this.form.is_paid = this.data.is_paid
  },
  data() {
    return {
      form: {
        actual_ordered_price_aed: null,
        remark: null,
      },
      suppliers: null,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  methods: {
    getSuppliers() {
      axios.get("/admin/suppliers").then((response) => {
        this.suppliers = response.data.data;
      });
    },
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      if(this.form.is_paid) this.form.is_paid = 1;
      else this.form.is_paid = 0;
      axios
        .patch(
          `/admin/inquiries/${this.$route.params.id}/future-dues/${this.data.id}`,
          this.form
        )
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully edit future due ${this.data.id} of inquiry ${this.$route.params.id}  !`,
            },
          });
          this.$bvModal.hide("editFutureModal");
          this.$emit("refresh");
          this.form = {};
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
    ValidationProvider,
    ValidationObserver,
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
