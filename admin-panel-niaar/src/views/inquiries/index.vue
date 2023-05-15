<template>
  <section>
    <!-- page header (breadcrumb) -->
    <b-row class="page-header mb-2">
      <b-col cols="12">
        <div class="d-flex flex-row justify-content-start align-items-center">
          <b-avatar size="lg" rounded="sm" variant="light-primary">
            <feather-icon icon="FileTextIcon" style="transform: scale(1.7)" />
          </b-avatar>
          <div
            class="d-flex flex-column justify-content-start align-items-start ml-1"
          >
            <h6>Manage Inquiries</h6>
            <b-breadcrumb class="breadcrumb-slash p-0">
              <b-breadcrumb-item href="/"> Home </b-breadcrumb-item>
              <b-breadcrumb-item active> Inquiries </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </div>
      </b-col>
    </b-row>

    <!-- collapse button -->
    <b-card
      no-body
      class="collapsible-form px-2 py-1 d-flex flex-row justify-content-between align-items-center"
      @click="openShopsFilterForm"
      v-b-toggle.shops-filter
      ref="test"
    >
      <div>
        <feather-icon icon="SearchIcon" style="transform: scale(1.2)" />
        <span class="ml-1 fs-sm">Search In Inquiries ...</span>
      </div>
      <b-button variant="flat-primary" class="btn-icon" size="sm">
        <feather-icon
          :icon="shopsFilterFormCollapsed ? 'ChevronUpIcon' : 'ChevronDownIcon'"
          style="transform: scale(1.4)"
        />
      </b-button>
    </b-card>

    <!-- search form -->
    <b-collapse id="shops-filter" class="mt-1">
      <b-card no-body>
        <b-card-body>
          <b-row class="mb-1">
            <b-col v-if="userData.role !== 'client'" cols="12" md="6">
              <b-form-group label="Assigned To" label-for="assigned_to">
                <b-form-input
                  id="assigned_to"
                  v-model="form.assigned_to"
                  placeholder="Enter Assigned To name"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="order" label-for="order">
                <v-select
                  label="label"
                  id="user-type"
                  :options="[
                    { key: 'asc', label: 'ascend' },
                    { key: 'desc', label: 'descend' },
                  ]"
                  placeholder="Select An Option"
                  v-model="form.order"
                  :reduce="(option) => option.key"
                />
              </b-form-group>
            </b-col>

            <b-col v-if="userData.role !== 'client'" cols="12" md="6">
              <b-form-group label="Client" label-for="client">
                <b-form-input
                  id="client"
                  v-model="form.client"
                  placeholder="Enter client name"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="Inquiry Number" label-for="inquiryNumber">
                <b-form-input
                  id="inquiryNumber"
                  v-model="form.number"
                  placeholder="Enter Your Inquiry Number"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="Status" label-for="status">
                <v-select
                  label="title"
                  id="status"
                  :options="inquiryStatuses"
                  v-model="form.status"
                  placeholder="Select An Option"
                  :reduce="(option) => option.key"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="Date From" label-for="date-from">
                <b-form-datepicker
                  id="date-from"
                  v-model="form.from_created_at"
                />
              </b-form-group>
            </b-col>

            <b-col cols="12" md="6">
              <b-form-group label="Date To" label-for="date-to">
                <b-form-datepicker id="date-to" v-model="form.to_created_at" />
              </b-form-group>
            </b-col>
          </b-row>
          <b-button size="sm" variant="primary" class="mr-1" @click="getData()">
            Filter
          </b-button>
          <b-button
            size="sm"
            variant="flat-danger"
            @click="
              form = {
                assigned_to: null,
                status: null,
              }
            "
          >
            Reset
          </b-button>
        </b-card-body>
      </b-card>
    </b-collapse>

    <!-- inquiries table -->
    <b-row class="mt-2">
      <b-col cols="12">
        <div v-if="isLoaded">
          <b-card no-body>
            <div
              class="p-2 d-flex flex-row justify-content-between align-items-center"
            >
              <h4 class="mb-0">Inquiry List</h4>
              <b-button
                variant="gradient-success"
                size="sm"
                to="/inquiries/create"
              >
                <feather-icon icon="FilePlusIcon" class="mr-50" />
                <span class="align-middle"> NEW INQUIRY </span>
              </b-button>
            </div>

            <div v-if="inquiries.length > 0">
              <b-table
                responsive
                :striped="striped"
                :bordered="bordered"
                :outlined="outlined"
                :fields="fields"
                :items="inquiries"
              >
                <template #cell(inquiryTitle)="data">
                  <div
                    class="d-flex justify-content-between align-items-center"
                  >
                    <span class="text-truncate" style="width: 120px">
                      {{ data.value }}
                    </span>

                    <b-button
                      variant="outline-secondary"
                      class="btn-icon rounded-circle"
                      v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                      v-b-popover.hover.focus="data.value"
                      size="sm"
                    >
                      <feather-icon icon="AlertCircleIcon" />
                    </b-button>
                  </div>
                </template>
                <template #cell(index)="data">
                  {{ data.index + 1 }}
                </template>

                <template #cell(client)="data">
                  {{ data.item.client.name }}
                </template>

                <template #cell(created_at)="data">
                  {{ data.item.created_at | formatDate }}
                </template>

                <template #cell(assigned_to)="data">
                  {{ data.item.assigned_to.name }}
                </template>

                <template #cell(action)="data">
                  <b-button
                    variant="gradient-info"
                    size="sm"
                    :to="`/inquiries/${data.item.id}`"
                  >
                    <!-- <feather-icon icon="EyeIcon" /> -->
                    <span class="align-middle"> Details </span>
                  </b-button>
                </template>
              </b-table>

              <b-pagination
                class="ml-1"
                v-model="page"
                :per-page="perPage"
                :total-rows="count"
              ></b-pagination>
            </div>
            
            <b-card v-else class="text-center">
              <h4 class="text-secondary mb-0">Inquiries Not Found</h4>
            </b-card>
          </b-card>
        </div>

        <div v-else class="text-center my-3">
          <b-spinner label="Loading..." />
          <span class="d-block mt-1"> Loading Content ... </span>
        </div>
      </b-col>
    </b-row>
  </section>
</template>

<script>
export default {
  computed: {
    featured() {
      return [{ title: "Does Not Matter" }, { title: "Yes" }, { title: "No" }];
    },
    statuses() {
      return [
        { title: "Does Not Matter" },
        { title: "Active" },
        { title: "Inactive" },
      ];
    },
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    this.params = this.$route.params;
    this.form.assigned_to = this.params?.assigned_to;
    this.form.status = this.inquiryStatuses.find(
      (el) => el.title == this.params?.status || el.key == this.params?.status
    );
    if (this.form.status != null) this.form.status = this.form.status.key;
    this.getData();
  },
  methods: {
    getData() {
      if (this.userData.role == "client") {
        this.form.client = this.userData.name;
        axios
          .get("/admin/inquiries", {
            params: {
              page: this.page,
              per_page: this.perPage,
              ...this.params,
              ...this.form,
            },
          })
          .then((response) => {
            this.inquiries = response.data.data;
            this.count = response.data.meta.total;
            this.isLoaded = true;
          })
          .catch((error) => {
            this.isLoaded = true;
          });
      } else if (this.userData.role == "procurement") {
        this.form.assigned_to = this.userData.name;
        axios
          .get("/admin/inquiries", {
            params: {
              page: this.page,
              per_page: this.perPage,
              ...this.params,
              ...this.form,
            },
          })
          .then((response) => {
            this.inquiries = response.data.data;
            this.count = response.data.meta.total;
            this.isLoaded = true;
          })
          .catch((error) => {
            this.isLoaded = true;
          });
      }  else
        axios
          .get("/admin/inquiries", {
            params: {
              page: this.page,
              per_page: this.perPage,
              ...this.params,
              ...this.form,
            },
          })
          .then((response) => {
            this.inquiries = response.data.data;
            this.count = response.data.meta.total;
            this.isLoaded = true;
          })
          .catch((error) => {
            this.isLoaded = true;
          });
    }, 
    newInquiry() {},
    openShopsFilterForm() {
      this.shopsFilterFormCollapsed = this.shopsFilterFormCollapsed
        ? false
        : true;
    },
    resetForm() {
      this.client =
        this.teamMember =
        this.inquiryStatus =
        this.inquiryNumber =
        this.dateFrom =
        this.dateTo =
          null;
    },
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      inquiryStatuses,
      params: [],
      shopsFilterFormCollapsed: false,
      client: "",
      teamMember: "",
      inquiryStatus: null,
      inquiryNumber: undefined,
      dateFrom: undefined,
      dateTo: undefined,
      dateFrom: undefined,
      dateTo: undefined,
      isLoaded: false,
      striped: true,
      bordered: true,
      outlined: true,
      fields: [
        { key: "id", label: "ID" },
        { key: "client.abbreviation", label: "Client abbreviation" },
        { key: "client", label: "Client" },
        { key: "creator.name", label: "Creator" },
        { key: "assigned_to.abbreviation", label: "Team Member abbreviation" },
        { key: "assigned_to", label: "Team Member" },
        { key: "title", label: "Inquiry title" },
        { key: "id", label: "Inquiry number" },
        { key: "created_at", label: "Start Date" },
        { key: "status", label: "Status" },
        { key: "action" },
      ],
      inquiries: [],
      selectedShops: [1],
      page: 1,
      perPage: 10,
      count: 34,
      form: {
        assigned_to: null,
        status: null,
      },
    };
  },
  watch: {
    page(newVal) {
      this.getData();
    },
  },
  directives: {
    "b-toggle": VBToggle,
    "b-popover": VBPopover,
    Ripple,
  },
  components: {
    BRow,
    BCol,
    BAvatar,
    BBreadcrumb,
    BBreadcrumbItem,
    BButton,
    BCollapse,
    BCard,
    BCardBody,
    BFormGroup,
    BFormInput,
    VSelect,
    BFormDatepicker,
    BTable,
    BSpinner,
    BFormCheckbox,
    BDropdown,
    BDropdownItem,
    BPagination,
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
  },
};
// * dependencies
import { inquiryStatuses } from "@/constant-vars";
import Ripple from "vue-ripple-directive";
import _ from "lodash";
// * components
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
import VSelect from "vue-select";
import axios from "@axios";
</script>

<style lang="scss">
@import "@/assets/scss/pages/shops.scss";
</style>
