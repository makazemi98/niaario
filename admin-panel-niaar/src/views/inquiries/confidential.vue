<template>
  <div>
    <b-card v-if="!loading">
      <b-form-group description="Allowed PDF">
        <!-- JPG, JPEG, PNG, PDF, XLSM, XLSB, XLS, TXT, CSV. -->
        <template>
          <div class="mb-1">
            Confidential Documents
            <small> (You can choose multiple files)</small>
          </div>
        </template>
        <!-- .jpg, .png, .gif, .pdf, .xlsx, .xlsm, .xlsb , .xls ,.txt , .csv" -->
        <b-form-file
          ref="refInputEl"
          accept=".pdf"
          :hidden="true"
          @change="uploadHandler"
          multiple
        />
        <small class="text-danger">{{ error }}</small>
      </b-form-group>

      <b-button
        :disabled="whileUploadingConf"
        @click="submitConf()"
        variant="gradient-success"
        size="sm"
      >
        <feather-icon v-if="!whileUploadingConf" icon="UploadCloudIcon" />
        <span v-if="!whileUploadingConf" class="align-middle"> Upload </span>
        <span v-else class="align-middle"> Loading... </span>
      </b-button>
      <div class="mt-3" v-if="allDocs.media.length > 0">
        <b-table
          responsive
          striped
          bordered
          outlined
          :fields="fields"
          :items="allDocs.media"
        >
          <template #cell(url)="data">
            <b-link :href="data.item.url" target="_blank">{{
              data.item.file_name
            }}</b-link>
          </template>
          <template #cell(action)="data">
            <b-button
              block
              variant="outline-secondary"
              size="sm"
              :disabled="whileDeletingConf"
              @click="deleteConfDoc(data.item.id, data.item.file_name)"
            >
              <span class="align-middle"> Delete </span>
            </b-button>
          </template>
        </b-table>
      </div>
    </b-card>
    <div v-else class="text-center flex">
      <b-spinner />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    statuses: { type: Object, default: () => [] },
  },
  data() {
    return {
      docs: [],
      error: null,
      allDocs: {},
      loading: true,
      whileDeletingConf: false,
      whileUploadingConf: false,
      fields: [
        // { key: "id", label: "#" },
        { key: "url", label: "Link" },
        { key: "file_name", label: "File name" },
        { key: "action", label: "Action" },
      ],
    };
  },
  mounted() {
    this.getConfData();
  },
  methods: {
    uploadHandler(e) {
      this.docs = e.target.files;
    },
    getConfData() {
      this.loading = true;
      axios
        .get(`/admin/inquiries/${this.$route.params.id}/docs/confidential`)
        .then((response) => {
          this.allDocs = response.data.data;
          this.loading = false;
        });
    },
    deleteConfDoc(id, name) {
      this.whileDeletingConf = true;
      axios
        .delete(`/admin/inquiries/${this.$route.params.id}/docs/${id}`)
        .then((res) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully deleted document ${name}!`,
            },
          });
          this.getConfData();
          this.whileDeletingConf = false;
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
    submitConf() {
      let formData = new FormData();
      for (var i = 0; i < this.docs.length; i++) {
        let file = this.docs[i];
        formData.append("docs[" + i + "]", file);
      }
      formData.append("doc_type", "confidential");
      if (this.docs.length > 0) {
        this.whileUploadingConf = true;
        this.error = null;
        axios({
          method: "POST",
          url: `/admin/inquiries/${this.$route.params.id}/docs`,
          data: formData,
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }).then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully created ${this.firstName} ${this.lastName} as a ${this.role}!`,
            },
          });
          this.whileUploadingConf = false;
          this.getConfData();
        });
      } else
        this.error = "Documents should have at least one file before submit";
    },
  },
  components: {
    BRow,
    BLink,
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
    StatusLog: () => import("./statusLog.vue"),
  },
};
import axios from "@axios";

import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
// * components
import {
  BRow,
  BLink,
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
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { map } from "leaflet";
</script>
