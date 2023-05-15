<template>
  <div>
    <validation-observer ref="editCalcForm" #default="{ invalid }">
      <b-modal
        v-if="Object.keys(data).length > 0"
        id="editCalcModal"
        :visible="showEditCalculation"
        title="Edit calculation"
        size="lg"
        static
        @change="updateModalVal"
      >
        <b-form-group label="Actual ordered price">
          <validation-provider
            #default="{ errors }"
            name="Actual ordered price"
            rules="required"
          >
            <b-form-input
              v-model="form.actual_ordered_price"
              placeholder="Actual ordered price"
              name="Actual ordered price"
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
            @click="$bvModal.hide('editCalcModal')"
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
    prop: "showEditCalculation",
    event: "update:show-edit-calc-modal",
  },
  props: {
    showEditCalculation: {
      type: Boolean,
      required: true,
    },
    data: {
      type: Object,
      default: () => {},
    },
  }, 
  watch: {
    data(newVal, oldVal) {
      this.form.actual_ordered_price = newVal.actual_ordered_price;
      this.form.actual_ordered_price_aed = newVal.actual_ordered_price_aed;
      this.form.remark = newVal.remark.body;
    },
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
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      axios
        .patch(
          `/admin/inquiries/${this.$route.params.id}/calculations/${this.data.id}`,
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
              text: `You have successfully edit calculation ${this.data.id} of inquiry ${this.$route.params.id}  !`,
            },
          });
          this.$bvModal.hide("editCalcModal");
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
