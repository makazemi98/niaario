<template>
  <b-modal
    id="reAssign"
    :visible="showSelectSupplierModal"
    title="Select Supplier"
    size="lg"
    static
    @change="updateModalVal"
  >
    <b-form-group label="Select Supplier:">
      <v-select
        v-if="suppliers"
        v-model="supplier"
        label="company"
        :options="suppliers"
      />
    </b-form-group>

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
    prop: "showSelectSupplierModal",
    event: "update:show-select-supplier-modal",
  },
  props: {
    showSelectSupplierModal: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      suppliers: null,
      userData: JSON.parse(localStorage.getItem("userData")),
      supplier: null,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    getData() {
      axios.get("/admin/suppliers").then((response) => {
        // console.log(response);
        this.suppliers = response.data.data;
      });
    },
    submit() {
      this.form.assign_to = this.assignTo.id;
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
              text: `You have successfully re assign inquiry ${this.id} to ${this.assignTo.name} !`,
            },
          });
          this.$bvModal.hide("reAssign");
          this.$emit("refresh");
          this.from = {};
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
