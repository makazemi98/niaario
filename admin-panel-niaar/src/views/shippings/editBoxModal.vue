<template>
  <validation-observer ref="editBoxForm" #default="{ invalid }">
    <b-modal
      id="editBox"
      :visible="showEditBoxModal"
      title="Edit Box"
      size="lg"
      static
      @change="updateModalVal"
    >
      <b-row>
        <b-col cols="12" md="6">
          <b-form-group label="To Client :">
            <v-select
              v-if="clients"
              v-model="form.client_id"
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
          </b-form-group>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="InquiryID"
            rules="required"
          >
            <b-form-group label="Inquiry ID">
              <b-form-input
                type="number"
                id="InquiryID"
                v-model="form.inquiry_id"
              />
            </b-form-group>
            <b-link target="_blank" to="/inquiries">Go to Inquiry List </b-link>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="sign"
            rules="required"
          >
            <b-form-group label="sign">
              <b-form-input id="sign" v-model="form.sign" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="height"
            rules="required"
          >
            <b-form-group label="height">
              <b-form-input type="number" id="height" v-model="form.height" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="length"
            rules="required"
          >
            <b-form-group label="length">
              <b-form-input type="number" id="length" v-model="form.length" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="width"
            rules="required"
          >
            <b-form-group label="width">
              <b-form-input type="number" id="width" v-model="form.width" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="volume"
            rules="required"
          >
            <b-form-group label="volume">
              <b-form-input type="number" id="volume" v-model="form.volume" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12">
          <validation-provider
            #default="{ errors }"
            name="Content"
            rules="required"
          >
            <b-form-group label="content">
              <b-form-input id="content" v-model="form.content" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>
      </b-row>

      <template #modal-footer>
        <b-button
          :disabled="invalid || loading"
          variant="gradient-primary"
          size="sm"
          @click="submit()"
        >
          <feather-icon icon="CheckIcon" />
          <span class="align-middle"> Save </span>
        </b-button>

        <b-button
          :disabled="loading"
          @click="
            $bvModal.hide('editBox');
            form = {};
            assignToTeam = {};
            assignToClient = {};
          "
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
import axios from "@axios";
export default {
  model: {
    prop: "showEditBoxModal",
    event: "update:show-edit-box-modal",
  },
  props: {
    showEditBoxModal: {
      type: Boolean,
      required: true,
    },
    id: [Number, String],
    item: Object,
  },
  computed: {
    selectedId() {
      if (this.assignToTeam.id) return this.assignToTeam.id;
      if (this.assignToClient.id) return this.assignToClient.id;
    },
  },
  data() {
    return {
      loading: false,
      teamMembers: null,
      clients: null,
      form: {},
      assignToTeam: null,
      assignToClient: null,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    submit() {
      this.loading = true;
      delete this.form.client;
      delete this.form.creator;
      delete this.form.deleted_at;
      delete this.form.created_at;
      delete this.form.created_by;
      delete this.form.updated_at;
      delete this.form.shipping_id;
      delete this.form.id;
      axios
        .patch(
          `/admin/shipping/${this.$route.params.id}/boxes/${this.id}`,
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
              text: `You have successfully edited box ${this.item.id} of shipping ${this.id}!`,
            },
          });
          this.$bvModal.hide("editBox");
          this.form = {};
          this.loading = false;
          this.$emit("refresh");
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
    getClientsData() {
      axios.get("/admin/users?role=client").then((response) => {
        this.clients = response.data.data;
      });
    },
  },
  watch: {
    item(newVal) {
      this.form = newVal;
    },
  },
  mounted() {
    this.getClientsData();
    this.form = this.item;
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
    ValidationProvider,
    ValidationObserver,
    BLink,
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
  BLink,
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
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
