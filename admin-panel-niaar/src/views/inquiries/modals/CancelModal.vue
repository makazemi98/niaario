<template>
  <b-modal
    id="cancelModalId"
    :visible="showCancelModal"
    title="Cancel Modal"
    size="lg"
    static
    @change="updateModalVal"
  >
    <b-row> 
      <b-col cols="12">
        <b-form-group label="Reason: " label-for="reason">
          <b-form-textarea id="reason" v-model="form.reason" />
        </b-form-group>
      </b-col>
    </b-row>

    <template #modal-footer>
      <b-button
        :disabled="!form.reason || loading"
        variant="gradient-primary"
        size="sm"
        @click="cancelInq()"
      >
        <feather-icon icon="CheckIcon" />
        <span class="align-middle"> Save </span>
      </b-button>

      <b-button
        :disabled="loading"
        @click="$bvModal.hide('cancelModalId')"
        variant="outline-secondary"
        size="sm"
      >
        <span class="align-middle"> Cancel </span>
      </b-button>
    </template>
  </b-modal>
</template>

<script>
export default {
  model: {
    prop: "showCancelModal",
    event: "update:show-cancel-modal",
  },
  props: {
    showCancelModal: {
      type: Boolean,
      required: true,
    },
    status: String,
    id: Number,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem("userData")),
      form: {},
      loading: false,
    };
  },
 
  methods: {
    updateModalVal(val) {
      this.$emit("update", val);
    },
    cancelInq() {
      this.loading = true;
      axios
        .post(`/admin/inquiries/${this.id}/actions`, {
          action: "cancel",
          reason:this.form.reason
        })
        .then((response) => {
          this.loading = false;
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully cancel inquiry ${this.id} !`,
            },
          });
          this.$router.replace("/inquiries");
          this.$bvModal.hide("cancelModalId");
        }).catch((err)=> {
          console.error(err);
          this.loading = false;
          this.$bvModal.hide("cancelModalId");
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
import axios from "@axios";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
</script>
