<template>
  <b-card>
    <validation-observer ref="createShippingForm" #default="{ invalid }">
      <b-form class="mt-2">
        <!-- form -->
        <b-row>
          <b-col md="12">
            <b-form-group label="Captain Info">
              <validation-provider
                #default="{ errors }"
                name="Captain Info"
                rules="required"
              >
                <b-form-textarea
                  v-model="form.captain_info"
                  placeholder="Captain Info..."
                  name="captainInfo"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Agent name">
              <validation-provider
                #default="{ errors }"
                name="Agent name"
                rules="required"
              >
                <b-form-input
                  v-model="form.agent_name"
                  placeholder="Agent name..."
                  name="agentName"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Agent number">
              <validation-provider
                #default="{ errors }"
                name="Agent number"
                rules="required"
              >
                <b-form-input
                  v-model="form.agent_no"
                  placeholder="agentNo"
                  name="agentNo"
                  type="number"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="sign">
              <validation-provider #default="{ errors }" name="sign">
                <b-form-input
                  v-model="form.sign"
                  placeholder="sign..."
                  name="sign"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="Handed Over Date">
              <validation-provider
                #default="{ errors }"
                name="Date"
                rules="required"
              >
                <b-form-datepicker
                  v-model="form.handed_over_date"
                  id="date"
                  placeholder="Date"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

          <b-col md="6">
            <b-form-group label="status">
              <validation-provider
                #default="{ errors }"
                name="status"
                rules="required"
              >
                <v-select
                  id="statusList"
                  v-model="form.status"
                  label="title"
                  :options="statuses"
                  :reduce="(title) => title.key"
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
              :disabled="invalid"
            >
              Submit
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              variant="outline-secondary"
              type="reset"
              class="mt-2"
              @click="
                lastName = '';
                firstName = '';
                role = '';
                gender = '';
                url = require('@/assets/images/avatars/placeholder-niaar.png');
                avatar = '';
                email = '';
                password = '';
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
  data() {
    return {
      form: {},
      statuses: [
        { key: "preparing", title: "Preparing" },
        { key: "handed_to_capt", title: "Handed to captain" },
        { key: "left_dubai", title: "Left dubai" },
        { key: "document_collected", title: "Document collected" },
        { key: "reached_to_destination", title: "Reached to destination" },
        { key: "paid", title: "Paid" },
        { key: "delivered", title: "delivered" },
      ],
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
  },
  methods: {
    submit() {
      this.$refs.createShippingForm.validate().then((success) => {
        if (success) {
          axios
            .post("/admin/shipping", this.form)
            .then((response) => {
              this.$router.replace("/shippings").then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: "top-right",
                  props: {
                    title: "Success",
                    icon: "CoffeeIcon",
                    variant: "success",
                    text: `You have successfully created new shipping!`,
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
    BFormDatepicker,
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
  BFormDatepicker,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Ripple from "vue-ripple-directive";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";
import axios from "@axios";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, email } from "@validations";
</script>
