<template>
  <b-modal
    id="reAssign"
    :visible="showApprovedModal"
    title="Compose Email"
    size="lg"
    static
    @change="updateModalVal"
  >
    REASSIGNNN

    <template #modal-footer>
      <b-button
        variant="gradient-primary"
        size="sm"
        @click="submit()"
      >
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
    prop: "showApprovedModal",
    event: "update:show-approved-modal",
  },
  props: {
    showApprovedModal: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      info: null,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
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

</script>
