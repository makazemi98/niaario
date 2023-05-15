<template>
  <div>
    <div class="mt-3" v-if="!loadRemider">
      <b-table
        v-if="allRemiders.length > 0"
        responsive
        striped
        bordered
        outlined
        :fields="fields"
        :items="allRemiders"
      >
        <template #cell(creator)="data">
          {{ data.item.creator.name }}
        </template>
        <template #cell(title)="data">
          {{ data.item.comment.title }}
        </template>
        <template #cell(comment)="data">
          {{ data.item.comment.body }}
        </template>

        <template #cell(action)="data">
          <b-button
            block
            variant="outline-secondary"
            size="sm"
            :disabled="whileDeletingRem"
            @click="deleteReminder(data.item.id, data.item.comment.title)"
          >
            <span class="align-middle"> Delete </span>
          </b-button>
        </template>
      </b-table>

      <div class="text-center">There is no record to show</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: Number,
    statuses: { type: Object, default: () => [] },
  },
  mounted() {
    this.getReminderData();
  },
  data() {
    return {
      loadRemider: false,
      allRemiders: [],
      whileDeletingRem: false,
      fields: [
        // { key: "id", label: "#" },
        { key: "reminder_date", label: "Reminder Date" },
        { key: "title", label: "Title" },
        { key: "comment", label: "Comment" },
        { key: "creator", label: "Creator" },
        { key: "action", label: "Action" },
      ],
    };
  },
  methods: {
    getReminderData() {
      this.loadRemider = true;
      axios
        .get("/admin/reminders", {
          params: { inquiry_id: this.$route.params.id },
        })
        .then((response) => {
          this.allRemiders = response.data.data;
          this.loadRemider = false;
        });
    },
    deleteReminder(id, name) {
      this.whileDeletingRem = true;
      axios
        .delete(`/admin/reminders/${id}`)
        .then((res) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully deleted reminder ${name}!`,
            },
          });
          this.getReminderData();
          this.whileDeletingRem = false;
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
    StatusLog: () => import("./statusLog.vue"),
  },
};
// * components
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
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import axios from "@axios";
</script>

<style></style>
