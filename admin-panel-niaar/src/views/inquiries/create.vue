<template>
  <div>
    <h4>New Inquiry</h4>
    <b-card>
      <validation-observer ref="createSupplierForm" #default="{ invalid }">
        <b-form class="mt-2">
          <!-- form -->
          <b-row>
            <b-col v-if="userData.role !== 'client'" cols="12" md="6">
              <b-form-group label="Client :">
                <validation-provider
                  #default="{ errors }"
                  name="Client"
                  rules="required"
                >
                  <v-select
                    v-model="client_id"
                    :options="clients"
                    :reduce="(client) => client.id"
                    label="abbreviation"
                  >
                    <template v-slot:option="option">
                      {{ option.abbreviation }} -
                      {{ option.name }}
                    </template>
                  </v-select>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group label="Title">
                <validation-provider
                  #default="{ errors }"
                  name="title"
                  rules="required|min:4"
                >
                  <b-form-input
                    v-model="title"
                    placeholder="title..."
                    name="title"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-form-group label="Description">
                <validation-provider
                  #default="{ errors }"
                  name="description"
                  rules="required|min:20"
                >
                  <b-form-textarea
                    v-model="description"
                    name="description"
                    placeholder="description"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-form-group label="Documents">
                <b-form-file
                  ref="refInputEl"
                  accept=".pdf"
                  :hidden="true"
                  @change="uploadHandler"
                  multiple
                />
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-form-group label="remarks">
                <validation-provider #default="{ errors }" name="remarks">
                  <b-form-textarea
                    v-model="remarks"
                    name="remarks"
                    placeholder="remarks"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col cols="12">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mt-2 mr-1"
                @click="submit"
                :disabled="invalid || loading"
              >
                Save
              </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                variant="outline-secondary"
                type="reset"
                class="mt-2"
                :disabled="loading"
                @click="
                  (docs = []),
                    (client_id = ''),
                    (title = ''),
                    (qty = ''),
                    (brand = ''),
                    (description = ''),
                    (remarks = ''),
                    (clients = [])
                "
              >
                Reset
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </b-card>
  </div>
</template>

<script>
export default {
  computed: {
    passwordToggleIconRetype() {
      return this.passwordFieldTypeRetype === "password"
        ? "EyeIcon"
        : "EyeOffIcon";
    },
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      docs: [],
      client_id: "",
      title: "",
      qty: "",
      brand: "",
      description: "",
      remarks: "",
      clients: [],
      loading: false,
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    if (this.userData.role !== "client")
      axios.get("/admin/users?role=client").then((response) => {
        this.clients = response.data.data;
      });
  },
  methods: {
    uploadHandler(e) {
      this.docs = e.target.files;
    },

    submit() {
      this.loading = true;
      let formData = new FormData();
      if (this.docs.length > 0)
        for (var i = 0; i < this.docs.length; i++) {
          let file = this.docs[i];
          formData.append("docs[" + i + "]", file);
        }
      if (this.client_id) {
        formData.append("client_id", this.client_id);
      } else formData.append("client_id", this.userData.id);
      formData.append("title", this.title);
      formData.append("description", this.description);
      formData.append("remarks", this.remarks);
      this.whileUploading = true;
      this.error = null;
      axios({
        method: "POST",
        url: `/admin/inquiries`,
        data: formData,
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.loading = false;
          this.$router.replace("/inquiries").then(() => {
            this.$toast({
              component: ToastificationContent,
              position: "top-right",
              props: {
                title: "Success",
                icon: "CoffeeIcon",
                variant: "success",
                text: `You have successfully created new inquiry!`,
              },
            });
          });
          this.whileUploading = false;
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
  },

  setup() {
    const refInputEl = ref(null);
    const previewEl = ref(null);

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, previewEl);

    return {
      refInputEl,
      previewEl,
      inputImageRenderer,
      countries,
    };
  },

  components: {
    BButton,
    BInputGroup,
    BInputGroupAppend,
    BForm,
    BImg,
    BFormFile,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BAvatar,
    BBreadcrumb,
    BBreadcrumbItem,
    vSelect,
    ValidationProvider,
    ValidationObserver,
    BFormTextarea,
  },
  directives: {
    Ripple,
  },
};
import {
  BFormFile,
  BButton,
  BCol,
  BAlert,
  BCard,
  BCardText,
  BMedia,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormInput,
  BFormGroup,
  BForm,
  BRow,
  BFormTextarea,
  BAvatar,
  BBreadcrumb,
  BBreadcrumbItem,
  BInputGroup,
  BInputGroupAppend,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Ripple from "vue-ripple-directive";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";
import axios from "@axios";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
import countries from "@/@fake-db/data/other/countries";
</script>
