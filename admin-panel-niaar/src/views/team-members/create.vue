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
            <b-form-group label-for="rolesList" label="Role">
              <validation-provider
                #default="{ errors }"
                name="role"
                rules="required"
              >
                <v-select
                  id="rolesList"
                  v-model="role"
                  label="title"
                  :options="roles"
                  :state="errors.length > 0 ? false : null"
                  :reduce="(title) => title.key"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
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

          <b-col cols="6">
            <b-form-group label="Renewal date" label-for="renewal_date">
              <validation-provider #default="{ errors }" name="renewal_date">
                <b-form-datepicker
                  v-model="renewal_date"
                  id="renewal_date"
                  placeholder="Renewal date"
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
                loading = false;
                renewal_date = '';
                abbreviation = '';
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
    // roles(){
    //   if(this.userData.role == "procurement") return [

    //   ]
    // }
  },
  data() {
    return {
      loading: false,
      passwordFieldTypeRetype: "password",
      genders: ["male", "female", "none"],
      roles: [
        { id: 2, title: "Manager", key: "manager" },
        { id: 3, title: "Team Leader", key: "team-leader" },
        { id: 4, title: "Accountant", key: "accountant" },
        { id: 5, title: "Procurements", key: "procurement" },
        // { id: 6, title: "Client" ,key:"client"},
      ],
      url: require("@/assets/images/avatars/placeholder-niaar.png"),
      avatar: null,
      firstName: "",
      lastName: "",
      role: "",
      gender: "",
      email: "",
      password: "",
      renewal_date: "",
      abbreviation: "",
      userData: JSON.parse(localStorage.getItem("userData")),
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
      if (this.renewal_date) formData.append("renewal_date", this.renewal_date);
      formData.append("abbreviation", this.abbreviation);

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
              this.$router.replace("/team-members").then(() => {
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
    BFormDatepicker,
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
  BFormDatepicker,
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
