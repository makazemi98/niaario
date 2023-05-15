<template>
  <validation-observer ref="cmfrm" #default="{ invalid }">
    <b-modal
      id="reAssign"
      :visible="showCommentModal"
      title="Add Comment"
      size="lg"
      static
      @change="updateModalVal"
    >
      <b-row>
        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="type"
            :rules="
              commentForm.type == 'question' || commentForm.type == 'reply'
                ? 'required'
                : ''
            "
          >
            <b-form-group label="To Team Member:">
              <v-select
                v-if="teamMembers"
                v-model="assignToTeam"
                :options="teamMembers"
                :reduce="(val) => val.id"
                label="abbreviation"
              >
                <template v-slot:option="option">
                  {{ option.abbreviation }} -
                  {{ option.name }}
                </template>
              </v-select>
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12" md="6">
          <validation-provider
            #default="{ errors }"
            name="type"
            rules="required"
          >
            <b-form-group label="Type of comment">
              <v-select
                v-model="commentForm.type"
                label="name"
                :options="['comment', 'question', 'reply']"
              />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12">
          <validation-provider
            #default="{ errors }"
            name="title"
            rules="required"
          >
            <b-form-group label="Title" label-for="title">
              <b-form-input id="title" v-model="commentForm.title" />
            </b-form-group>
            <small class="text-danger">{{ errors[0] }}</small>
          </validation-provider>
        </b-col>

        <b-col cols="12">
          <validation-provider
            #default="{ errors }"
            name="Commment"
            rules="required"
          >
            <b-form-group label="Commment" label-for="inq-comment">
              <b-form-textarea id="inq-comment" v-model="commentForm.body" />
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
            $bvModal.hide('reAssign');
            commentForm = {};
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
    prop: "showCommentModal",
    event: "update:show-comment-modal",
  },
  props: {
    showCommentModal: {
      type: Boolean,
      required: true,
    },
    id: Number,
  },
  mounted() {
    this.getData();
  },
  computed: {
    showClientsSelectBox() {
      if (
        this.clients &&
        (this.userData.role == "super-admin" ||
          this.userData.role == "manager" ||
          this.userData.role == "accountant" ||
          this.userData.role == "procurement" ||
          this.userData.role == "team-leader")
      )
        return true;
      else return false;
    },
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
      commentForm: {},
      assignToTeam: null,
      assignToClient: null,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    getData() {
      axios
        .get("admin/users/team-members?per_page=100000&page=1")
        .then((response) => {
          this.loading = false;
          this.teamMembers = response.data.data;
        });

      axios.get("/admin/users?role=client").then((response) => {
        this.clients = response.data.data;
      });
    },
    submit() {
      this.loading = true;
      this.commentForm.to = this.assignToTeam || this.userData.id;
      this.$refs.cmfrm.validate().then((success) => {
        if (success) {
          axios
            .post(`/admin/inquiries/${this.id}/commenting`, this.commentForm)
            .then((response) => {
              this.$toast({
                component: ToastificationContent,
                position: "top-right",
                props: {
                  title: "Success",
                  icon: "CoffeeIcon",
                  variant: "success",
                  text: `You have successfully added the comment!`,
                },
              });
              this.$bvModal.hide("reAssign");
              this.commentForm = {};
              this.assignToTeam = null;
              this.assignToClient = null;
              this.loading = false;
              window.location.reload();
              this.$emit("refresh");
            })
            .catch((err) => {
              console.error(err);
              this.loading = false;
            });
        }
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
    ValidationProvider,
    ValidationObserver,
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
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
