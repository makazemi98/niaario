<template>
  <div>
    <div v-if="statuses">
      <b-card style="background-color: #c7e4d8">
        <h4 class="mb-0" style="color:black;">
          Inquiry number <b>#{{ $route.params.id }} </b>
        </h4>
      </b-card>
      <hr />

      <b-card
        v-for="item in statuses.statuses"
        :key="item.id"
        :header="item.name"
        footer-bg-variant="secondary"
        footer-text-variant="white"
      >
        <template #header>
          <b> {{ item.name }}</b>
        </template>
        <b-card-text>{{ item.created_at | formatDate }}</b-card-text>
        <template #footer>
          By : {{ item.creator.name }}
          <small v-if="item.creator.abbreviation"
            >({{ item.creator.abbreviation }})</small
          >
        </template>
      </b-card>
    </div>
    <div v-else>
      <div class="my-2">
        fetching data of statuses ...
        <b-spinner small />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    statuses: { type: Object, default: () => [] },
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
    StatusLog: () => import("./statusLog.vue"),
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    ChangeStatusModal: () => import("./modals/ChangeStatusModal.vue"),
    CommentModal: () => import("./modals/CommentModal.vue"),
    DeclineModal: () => import("./modals/DeclineModal.vue"),
    ReAssignModal: () => import("./modals/ReAssignModal.vue"),
    ReminderModal: () => import("./modals/ReminderModal.vue"),
    SelectSupplierModal: () => import("./modals/SelectSupplierModal.vue"),
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
</script>

<style></style>
