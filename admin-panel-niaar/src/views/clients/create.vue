<template>
  <b-card>
    <validation-observer ref="createMemberForm" #default="{ invalid }">
      <b-form class="mt-2">
        <!-- media -->
        <b-media class="mb-2" no-body>
          <b-media-aside class="align-item-center m-auto">
            <b-link>
              <b-img :src="url" alt="" rounded height="80" />
            </b-link>
            <!--/ avatar -->
          </b-media-aside>

          <b-media-body class="mt-75 ml-75">
            <b-form-group description="Allowed JPG, JPEG or PNG">
              <b-form-file
                ref="refInputEl"
                accept=".jpg, .png, .gif"
                :hidden="true"
                @change="uploadHandler"
              />
            </b-form-group>
          </b-media-body>
        </b-media>
        <!--/ media -->

        <!-- form -->
        <b-row>
          <b-col md="6">
            <b-form-group label-for="genderList" label="Gender">
              <validation-provider
                #default="{ errors }"
                name="Gender"
                rules="required"
              >
                <v-select
                  id="genderList"
                  v-model="gender"
                  :options="genders"
                  :state="errors.length > 0 ? false : null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="First Name" label-for="account-firstName">
              <validation-provider
                #default="{ errors }"
                name="firstName"
                rules="required"
              >
                <b-form-input
                  v-model="firstName"
                  placeholder="first name..."
                  name="firstName"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Last Name" label-for="account-lastName">
              <validation-provider
                #default="{ errors }"
                name="lastName"
                rules="required"
              >
                <b-form-input
                  v-model="lastName"
                  placeholder="last name..."
                  name="lastName"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Abbreviation" label-for="abbreviation">
              <validation-provider
                #default="{ errors }"
                name="abbreviation"
                rules="required"
              >
                <b-form-input
                  v-model="abbreviation"
                  placeholder="Abbreviation..."
                  name="abbreviation"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="E-mail" label-for="account-e-mail">
              <validation-provider
                #default="{ errors }"
                name="email"
                rules="required|email"
              >
                <b-form-input
                  v-model="email"
                  name="email"
                  placeholder="Email"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <!-- // -->
          <b-col md="6">
            <b-form-group label="contact person" label-for="contact_person">
              <validation-provider #default="{ errors }" name="contact_person">
                <b-form-input
                  v-model="contact_person"
                  placeholder="contact person..."
                  name="contact_person"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="mobile number" label-for="mobile_number">
              <validation-provider #default="{ errors }" name="mobile_number">
                <b-form-input
                  v-model="mobile_number"
                  placeholder="mobile number..."
                  name="mobile_number"
                  type="number"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="company number" label-for="company_number">
              <validation-provider #default="{ errors }" name="company_number">
                <b-form-input
                  v-model="company_number"
                  placeholder="company number..."
                  name="company_number"
                  type="number"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="contact name 2" label-for="contact_name_2">
              <validation-provider #default="{ errors }" name="contact_name_2">
                <b-form-input
                  v-model="contact_name_2"
                  placeholder="contact name 2..."
                  name="contact_name_2"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="mobile number 2" label-for="mobile_number_2">
              <validation-provider #default="{ errors }" name="mobile_number_2">
                <b-form-input
                  v-model="mobile_number_2"
                  placeholder="mobile number 2..."
                  name="mobile_number_2"
                  type="number"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group
              label="company abbreviation"
              label-for="company_abbreviation"
            >
              <validation-provider
                #default="{ errors }"
                name="company_abbreviation"
              >
                <b-form-input
                  v-model="company_abbreviation"
                  placeholder="company abbreviation..."
                  name="company_abbreviation"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!-- // -->

          <b-col md="6">
            <b-form-group
              description="Just enter alpha numeric password [Aa-Zz] [0-9]"
              label="Password"
              label-for="account-password"
            >
              <validation-provider
                #default="{ errors }"
                name="password"
                rules="required|min:8|alpha-num"
              >
                <b-input-group class="input-group-merge">
                  <b-form-input
                    id="account-retype-new-password"
                    v-model="password"
                    :type="passwordFieldTypeRetype"
                    name="retype-password"
                    placeholder="New Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      :icon="passwordToggleIconRetype"
                      class="cursor-pointer"
                      @click="togglePasswordRetype"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group label="Confidential Note">
              <validation-provider
                #default="{ errors }"
                name="Confidential Note"
              >
                <b-form-textarea
                  v-model="confidential"
                  placeholder="Confidential Note"
                  name="Confidential Note"
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
              Submit
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              variant="outline-secondary"
              type="reset"
              class="mt-2"
              :disabled="loading"
              @click="
                lastName = '';
                firstName = '';
                role = '';
                gender = '';
                url = require('@/assets/images/avatars/placeholder-niaar.png');
                avatar = '';
                email = '';
                password = '';
                confidential = '';
                abbreviation = '';
                contact_person = '';
                mobile_number = '';
                company_name = '';
                company_number = '';
                contact_name_2 = '';
                mobile_number_2 = '';
                company_abbreviation = '';
                loading = false;
              "
            >
              Reset
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
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
      loading: false,
      passwordFieldTypeRetype: "password",
      genders: ["male", "female", "none"],
      confidential: "",
      url: require("@/assets/images/avatars/placeholder-niaar.png"),
      avatar: null,
      firstName: "",
      lastName: "",
      role: "client",
      gender: "",
      email: "",
      password: "",
      abbreviation: "",

      contact_person: "",
      mobile_number: "",
      company_name: "",
      company_number: "",
      contact_name_2: "",
      mobile_number_2: "",
      company_abbreviation: "",
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
  },
  methods: {
    uploadHandler(e) {
      this.avatar = e.target.files[0];
      this.url = URL.createObjectURL(e.target.files[0]);
    },

    togglePasswordRetype() {
      this.passwordFieldTypeRetype =
        this.passwordFieldTypeRetype === "password" ? "text" : "password";
    },

    submit() {
      this.loading = true;
      const formData = new FormData();
      formData.append("title", this.gender == "male" ? "Mr" : "Ms");
      formData.append("avatar", this.avatar);
      formData.append("first_name", this.firstName);
      formData.append("last_name", this.lastName);
      formData.append("role", this.role);
      formData.append("gender", this.gender);
      formData.append("email", this.email);
      formData.append("password", this.password);
      formData.append("password", this.password);
      formData.append("confidential", this.confidential);
      formData.append("abbreviation", this.abbreviation);

      formData.append("contact_person", this.contact_person);
      formData.append("mobile_number", this.mobile_number);
      formData.append("company_name", this.company_name);
      formData.append("company_number", this.company_number);
      formData.append("contact_name_2", this.contact_name_2);
      formData.append("mobile_number_2", this.mobile_number_2);
      formData.append("company_abbreviation", this.company_abbreviation);

      this.$refs.createMemberForm.validate().then((success) => {
        if (success) {
          axios({
            method: "POST",
            url: "/admin/users",
            data: formData,
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
            .then((response) => {
              this.loading = false;
              this.$router.replace("/clients").then(() => {
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
              });
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
        }
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
import { required, email, alphaNum } from "@validations";
</script>
