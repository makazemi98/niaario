<template>
  <div>
    <validation-observer ref="addCalcForm" #default="{ invalid }">
      <b-modal
        id="addCalcModal"
        :visible="showAddCalculation"
        title="Add new Calculation"
        size="lg"
        static
        @change="updateModalVal"
      >
        <b-form-group label="Supplier:">
          <validation-provider
            #default="{ errors }"
            name="supplier"
            rules="required"
          >
            <v-select
              v-if="suppliers"
              v-model="form.supplier_id"
              label="company"
              :options="suppliers"
              :reduce="(option) => option.id"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="quantity">
          <validation-provider
            #default="{ errors }"
            name="quantity"
            rules="required"
          >
            <b-form-input
              v-model="form.qty"
              placeholder="quantity"
              name="quantity"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Product name">
          <validation-provider
            #default="{ errors }"
            name="Product name"
            rules="required"
          >
            <b-form-input
              v-model="form.description"
              placeholder="Product name"
              name="Product name"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="buying price">
          <validation-provider
            #default="{ errors }"
            name="buying price"
            rules="required"
          >
            <b-form-input
              v-model="form.buying_price"
              placeholder="buying price"
              name="buying price"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="buying currency">
          <validation-provider
            #default="{ errors }"
            name="buying currency"
            rules="required"
          >
            <b-form-input
              v-model="form.buying_currency"
              placeholder="buying currency"
              name="buying currency"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="buying total price (AED)">
          <validation-provider
            #default="{ errors }"
            name="buying total price (AED)"
            rules="required"
          >
            <b-form-input
              v-model="form.buying_total_price_aed"
              placeholder="buying total price (AED)"
              name="buying total price (AED)"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Qouted price">
          <validation-provider
            #default="{ errors }"
            name="Qouted price"
            rules="required"
          >
            <b-form-input
              v-model="form.quoted_price"
              placeholder="Qouted price"
              name="Qouted price"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Qouted currency">
          <validation-provider
            #default="{ errors }"
            name="Qouted currency"
            rules="required"
          >
            <b-form-input
              v-model="form.quoted_currency"
              placeholder="Qouted currency"
              name="Qouted currency"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Qouted price (AED)">
          <validation-provider
            #default="{ errors }"
            name="Qouted price (AED)"
            rules="required"
          >
            <b-form-input
              v-model="form.quoted_price_aed"
              placeholder="Qouted price (AED)"
              name="Qouted price (AED)"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Actual ordered price (AED)">
          <validation-provider
            #default="{ errors }"
            name="Actual ordered price (AED)"
            rules="required" 
          >
            <b-form-input
              v-model="form.actual_ordered_price_aed"
              placeholder="Actual ordered price (AED)"
              name="Actual ordered price (AED)"
              type="number"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>

        <b-form-group label="Remark">
          <validation-provider
            #default="{ errors }"
            name="Remark"
          >
            <b-form-textarea
              v-model="form.remark"
              placeholder="Remark"
              name="Remark"
            />
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-form-group>
        
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
            @click="$bvModal.hide('addCalcModal')"
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
    prop: "showAddCalculation",
    event: "update:show-add-calc-modal",
  },
  props: {
    showAddCalculation: {
      type: Boolean,
      required: true,
    },
  },
  mounted() {
    this.getSuppliers();
  },
  data() {
    return {
      form: {},
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
      axios
        .post(`/admin/inquiries/${this.$route.params.id}/calculations`, this.form)
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully add calculation to inquiry ${this.$route.params.id}  !`,
            },
          });
          this.$bvModal.hide("addCalcModal");
          this.$emit("refresh");
          // window.location.reload();
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
