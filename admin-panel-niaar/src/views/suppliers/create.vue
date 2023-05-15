<template>
  <b-card>
    <validation-observer ref="createSupplierForm" #default="{ invalid }">
      <b-form class="mt-2">
        <!-- form -->
        <b-row>
          <b-col cols="12" md="6">
            <b-form-group label="To Client :">
              <validation-provider
                #default="{ errors }"
                name="Client"
                rules="required"
              >
                <v-select
                  multiple
                  v-model="form.customers_id"
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
            <b-form-group label="Company">
              <validation-provider
                #default="{ errors }"
                name="company"
                rules="required"
              >
                <b-form-input
                  v-model="form.company"
                  placeholder="Company..."
                  name="company"
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
                  v-model="form.email"
                  name="email"
                  placeholder="Email"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Phone">
              <validation-provider
                #default="{ errors }"
                name="phone"
                rules="required"
              >
                <b-form-input
                  v-model="form.phone"
                  placeholder="Phone..."
                  type="number"
                  name="phone"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Mobile">
              <validation-provider
                #default="{ errors }"
                name="mobile"
                rules="required"
              >
                <b-form-input
                  v-model="form.mobile"
                  placeholder="mobile..."
                  type="number"
                  name="mobile"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Website">
              <validation-provider #default="{ errors }" name="website">
                <b-form-input
                  v-model="form.website"
                  placeholder="Website..."
                  name="website"
                />
                <small class="font-weight-bold"
                  >Pattern: https://website.com</small
                >
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="In charge">
              <validation-provider #default="{ errors }" name="In charge">
                <v-select
                  multiple
                  v-if="teamMembers"
                  v-model="form.persons_in_charge"
                  :options="teamMembers"
                  :reduce="(val) => val.id"
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
            <validation-provider
              #default="{ errors }"
              name="Country"
              rules="required"
            >
              <b-form-group label="Country" label-for="country">
                <v-select
                  v-model="form.country"
                  :options="countries"
                  :clearable="false"
                  input-id="country"
                  label="name"
                  :reduce="(option) => option.name"
                />
              </b-form-group>
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-col>

          <b-col md="6">
            <b-form-group label="Product Category">
              <validation-provider
                #default="{ errors }"
                name="Product Category"
              >
                <b-input-group class="input-group-merge">
                  <b-form-input
                    v-model="productCategory"
                    name="product categories"
                    placeholder="Product categories"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      icon="PlusIcon"
                      class="cursor-pointer"
                      @click="
                        if (productCategory)
                          form.product_categories.push(productCategory);
                        productCategory = null;
                      "
                    />
                  </b-input-group-append>
                </b-input-group>
                <span v-for="item in form.product_categories" :key="item">
                  {{ item }} ,</span
                >
                <small class="text-danger">{{ errors[0] }}</small>
                <b-button
                  v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                  variant="outline-secondary"
                  class="d-block mt-1"
                  size="sm"
                  @click="form.product_categories = []"
                >
                  Reset
                </b-button>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Supplying Brands">
              <validation-provider
                #default="{ errors }"
                name="Supplying Brands"
              >
                <b-input-group class="input-group-merge">
                  <b-form-input
                    v-model="supplyingBrand"
                    name="Supplying Brands"
                    placeholder="Supplying Brands"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      icon="PlusIcon"
                      class="cursor-pointer"
                      @click="
                        if (supplyingBrand)
                          form.supplying_brands.push(supplyingBrand);
                        supplyingBrand = null;
                      "
                    />
                  </b-input-group-append>
                </b-input-group>
                <span v-for="item in form.supplying_brands" :key="item">
                  {{ item }} ,</span
                >
                <small class="text-danger">{{ errors[0] }}</small>
                <b-button
                  v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                  variant="outline-secondary"
                  class="d-block mt-1"
                  size="sm"
                  @click="form.supplying_brands = []"
                >
                  Reset
                </b-button>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col cols="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mt-2 mr-1"
              @click="submit"
              :disabled="
                invalid ||
                form.supplying_brands.length < 1 ||
                form.product_categories < 1
              "
            >
              Save
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              variant="outline-secondary"
              type="reset"
              class="mt-2"
              @click="
                form = {
                  product_categories: [],
                  supplying_brands: [],
                }
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
      roles: [
        { key: "manager", title: "Manager" },
        { key: "team-leader", title: "Team Leader" },
        { key: "accountant", title: "Accountant" },
        { key: "procurement", title: "Procurements" },
        { key: "client", title: "Client" },
      ],
      supplyingBrand: "",
      productCategory: "",
      clients: [],
      form: {
        product_categories: [],
        supplying_brands: [],
      },
      teamMembers: null,
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    axios.get("/admin/users?role=client").then((response) => {
      this.clients = response.data.data;
    });
    this.getTeam();
  },
  methods: {
    getTeam() {
      axios
        .get("admin/users/team-members?per_page=100000&page=1")
        .then((response) => {
          this.teamMembers = response.data.data;
        });
    },
    uploadHandler(e) {
      this.avatar = e.target.files[0];
      this.url = URL.createObjectURL(e.target.files[0]);
    },

    submit() {
      this.$refs.createSupplierForm.validate().then((success) => {
        if (success) {
          this.loading = true;
          axios({
            method: "POST",
            url: "/admin/suppliers",
            data: this.form,
          })
            .then((response) => {
              this.loading = false;

              this.form = {
                product_categories: [],
                supplying_brands: [],
              };
              this.$router.replace("/suppliers").then(() => {
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
import countries from "@/data/countries";
</script>
