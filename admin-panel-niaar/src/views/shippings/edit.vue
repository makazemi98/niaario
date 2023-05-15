<template>
  <div>
    <validation-observer ref="createShippingForm" #default="{ invalid }">
      <b-card>
        <b-form class="mt-2">
          <!-- form -->
          <b-row>
            <b-col md="6">
              <b-form-group label="Captain Info" label-for="captainInfo">
                <validation-provider
                  #default="{ errors }"
                  name="Captain Info"
                  rules="required"
                >
                  <b-form-input
                    v-model="form.captain_info"
                    placeholder="Captain Info..."
                    name="captainInfo"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </b-col>

            <b-col md="6">
              <b-form-group label="Agent name" label-for="agentName">
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
              <b-form-group label="sign" label-for="sign">
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
              <b-form-group label="Handed Over Date" label-for="date">
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
              <b-form-group label-for="statusList" label="status">
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

            <b-col class="mt-2" cols="12">
              <div
                class="py-2 d-flex flex-row justify-content-between align-items-center"
              >
                <h4 class="mb-0">Boxes List</h4>
                <b-button
                  variant="gradient-success"
                  size="sm"
                  @click="showCreateBoxModal = true"
                >
                  <feather-icon icon="PlusIcon" class="mr-50" />
                  <span class="align-middle"> New Box </span>
                </b-button>
              </div>
              <b-table
                responsive
                striped
                bordered
                outlined
                :fields="fieldsOfBoxes"
                :items="form.boxes"
              >
                <template #cell(creator)="data">
                  ID:{{ data.item.creator.id }}-
                  {{ data.item.creator.first_name }}
                  {{ data.item.creator.last_name }}
                </template>
                <template #cell(client)="data">
                  ID:{{ data.item.client.id }}-
                  {{ data.item.client.first_name }}
                  {{ data.item.client.last_name }}
                </template>
                <template #cell(created_at)="data">
                  {{ data.item.created_at | formatDate }}
                </template>
                <template #cell(updated_at)="data">
                  {{ data.item.updated_at | formatDate }}
                </template>
                <template #cell(deleted_at)="data">
                  {{ data.item.deleted_at | formatDate }}
                </template>

                <template #cell(action)="data">
                  <b-button
                    :disabled="!!data.item.deleted_at"
                    block
                    variant="outline-warning"
                    size="sm"
                    @click="showEditBoxModalFunc(data.item)"
                  >
                    <span class="align-middle"> Edit </span>
                  </b-button>

                  <b-button
                    block
                    variant="outline-secondary"
                    size="sm"
                    :disabled="whileDeletingCalc || !!data.item.deleted_at"
                    @click="deleteBox(data.item.id)"
                  >
                    <span class="align-middle"> Delete </span>
                  </b-button>
                </template>
              </b-table>
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
      </b-card>
    </validation-observer>

    <create-box-modal
      :id="$route.params.id"
      v-model="showCreateBoxModal"
      @update="updateCreateBoxModal"
      @refresh="getData()"
    />

    <edit-box-modal
      v-if="currentSelectedBox"
      :item="currentSelectedBox"
      :id="currentIdSelectedBox"
      v-model="showEditBoxModal"
      @update="updateEditBoxModal"
      @refresh="getData()"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      whileDeletingCalc: false,
      showCreateBoxModal: false,
      showEditBoxModal: false,
      currentSelectedBox: null,
      currentIdSelectedBox: null,
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
      fieldsOfBoxes: [
        { key: "id", label: "Id", sortable: true },
        // { key: "client_id", label: "Client ID", sortable: true },
        { key: "client", label: "Client", sortable: true },
        { key: "content", label: "Content", sortable: true },
        { key: "created_at", label: "Created at", sortable: true },
        // { key: "created_by", label: "Created by", sortable: true },
        { key: "creator", label: "Creator", sortable: true },
        // { key: "deleted_at", label: "Deleted at", sortable: true },
        { key: "height", label: "Height", sortable: true },
        { key: "width", label: "Width" },
        { key: "length", label: "Length", sortable: true },
        { key: "volume", label: "Volume" },
        { key: "inquiry_id", label: "Inq ID", sortable: true },
        { key: "shipping_id", label: "Shipping id" },
        { key: "sign", label: "Sign" },
        { key: "updated_at", label: "Updated at" },
        { key: "action", label: "Action" },
      ],
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    this.getData();
  },

  methods: {
    showEditBoxModalFunc(item) {
      // console.log(item);
      this.currentIdSelectedBox = item.id;
      this.currentSelectedBox = item;
      this.showEditBoxModal = true;
      // console.log(this.currentSelectedBox);
    },
    updateCreateBoxModal(val) {
      this.showCreateBoxModal = val;
    },
    updateEditBoxModal(val) {
      this.showEditBoxModal = val;
    },
    getData() {
      axios.get(`/admin/shipping/${this.$route.params.id}`).then((response) => {
        this.form = response.data.data;
      });
      this.isLoaded = true;
    },

    submit() {
      this.$refs.createShippingForm.validate().then((success) => {
        if (success) {
          axios
            .patch(`/admin/shipping/${this.$route.params.id}`, this.form)
            .then((response) => {
              this.$router
                .replace("/shippings")
                .then(() => {
                  this.$toast({
                    component: ToastificationContent,
                    position: "top-right",
                    props: {
                      title: "Success",
                      icon: "CoffeeIcon",
                      variant: "success",
                      text: `You have successfully edit shipping!`,
                    },
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
            });
        }
      });
    },

    deleteBox(id) {
      this.whileDeletingCalc = true;
      axios
        .delete(`/admin/shipping/${this.$route.params.id}/boxes/${id}`)
        .then((res) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully deleted box ${name}!`,
            },
          });
          this.getData();
          this.whileDeletingCalc = false;
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
    BFormDatepicker,
    BTable,
    createBoxModal: () => import("./createBoxModal.vue"),
    editBoxModal: () => import("./editBoxModal.vue"),
  },
  directives: {
    Ripple,
  },
};
import {
  BFormFile,
  BButton,
  BCol,
  BTable,
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
