<template>
  <div>
    <!-- <h3>profit of current project</h3> -->
    <!-- <b-card v-if="meta">
      <h5 class="">assumed profit : {{ meta.profit.assumed_profit }}</h5>

      <h5 class="mb-0">actual profit : {{ meta.profit.actual_profit }}</h5>
    </b-card> -->

    <b-card v-if="clientConfidentialNote">
      <h5 class="">confidential Note</h5>

      <h5 class="mb-0">{{ clientConfidentialNote }}</h5>
    </b-card>

    <!-- calculations -->
    <b-card no-body>
      <div
        class="p-2 d-flex flex-row justify-content-between align-items-center"
      >
        <h4 class="mb-0">Calculation</h4>
        <b-button
          variant="gradient-success"
          size="sm"
          @click="showAddCalculation = !showAddCalculation"
        >
          <feather-icon icon="FilePlusIcon" class="mr-50" />
          <span class="align-middle"> Add new Calculation </span>
        </b-button>
      </div>
      <b-table
        v-if="allCalculations"
        foot-clone
        responsive
        striped
        bordered
        outlined
        :fields="fields"
        :items="allCalculations.calculations"
      >
        <template #cell(inq_no)="data">
          {{ $route.params.id }}
        </template>
        <template #cell(client)="data">
          {{ data.item.supplier_id.company }}
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
            @click="showEditCalcModal(data.item)"
          >
            <span class="align-middle"> Edit </span>
          </b-button>

          <b-button
            block
            variant="outline-secondary"
            size="sm"
            :disabled="whileDeletingCalc || !!data.item.deleted_at"
            @click="deleteCalc(data.item.id)"
          >
            <span class="align-middle"> Delete </span>
          </b-button>
        </template>

        <template #foot(id)="data">
          <small>total :</small> <b>{{ metaCalc.total }}</b>
        </template>
        <template #foot(actual_ordered_price_aed)="data">
          <small>total :</small> <b>{{ metaCalc.total_actual_ordered_aed }}</b>
        </template>
        <template #foot(buying_total_price_aed)="data">
          <small>total :</small> <b> {{ metaCalc.total_buying_aed }}</b>
        </template>
        <template #foot(quoted_price_aed)="data">
          <small>total :</small> <b> {{ metaCalc.total_quoted_aed }}</b>
        </template>
        <template #foot()="data"> _ </template>
      </b-table>
    </b-card>
    <!-- calculations -->

    <!-- future dues -->
    <b-card no-body>
      <div
        class="p-2 d-flex flex-row justify-content-between align-items-center"
      >
        <h4 class="mb-0">Future Dues</h4>
        <b-button
          variant="gradient-success"
          size="sm"
          @click="showAddFutureDues = !showAddFutureDues"
        >
          <feather-icon icon="FilePlusIcon" class="mr-50" />
          <span class="align-middle"> Add new Future Dues </span>
        </b-button>
      </div>

      <div v-if="allFutureDues">
        <div class="p-2">
          <h5 class="">
            assumed profit : {{ metaFeat.profit.assumed_profit }}
          </h5>

          <h5>actual profit : {{ metaFeat.profit.actual_profit }}</h5>

          <h5 class="">total : {{ metaFeat.total }}</h5>

          <h5 class="">
            total buying (aed) : {{ metaFeat.profit.total_buying_aed }}
          </h5>

          <h5 class="">
            total qouted (aed) : {{ metaFeat.profit.total_quoted_aed }}
          </h5>

          <h5 class="mb-0">
            total actual ordered (aed) :
            {{ metaFeat.profit.total_actual_ordered_aed }}
          </h5>
        </div>

        <b-table
          foot-clone
          responsive
          striped
          bordered
          outlined
          :fields="fieldsFutureDues"
          :items="allFutureDues"
        >
          <template #cell(inq_no)="data">
            {{ $route.params.id }}
          </template>
          <!-- <template #cell(client)="data">
            {{ data.client.name }}
          </template> -->
          <template #cell(due_date)="data">
            {{ data.item.due_date | formatDate }}
          </template>
          <template #cell(is_paid)="data">
            <feather-icon
              v-if="data.item.is_paid == 1"
              icon="CheckIcon"
              class="mr-50"
            />

            <feather-icon v-else icon="XIcon" class="mr-50" />
          </template>

          <template #foot(id)="data">
            <small>total :</small> <b>{{ metaFeat.total }}</b>
          </template>
          <template #foot(actual_ordered_price_aed)="data">
            <small>total :</small>
            <b>{{ metaFeat.total_actual_ordered_aed }}</b>
          </template>
          <template #foot(buying_total_price_aed)="data">
            <small>total :</small> <b> {{ metaFeat.total_buying_aed }}</b>
          </template>
          <template #foot(quoted_price_aed)="data">
            <small>total :</small> <b> {{ metaFeat.total_quoted_aed }}</b>
          </template>
          <template #foot()="data"> _ </template>

          <template #cell(action)="data">
            <b-button
              block
              variant="outline-warning"
              size="sm"
              @click="showEditFeatModal(data.item)"
            >
              <span class="align-middle"> Edit </span>
            </b-button>

            <b-button
              v-if="!data.item.is_paid == 1"
              block
              variant="outline-secondary"
              size="sm"
              :disabled="whilePaid"
              @click="paidFutureDue(data.item.id)"
            >
              <span class="align-middle"> Paid </span>
            </b-button>
          </template>
        </b-table>
      </div>
    </b-card>

    <!-- modals -->
    <editFutureModal
      v-model="showEditFutureDues"
      @update="updateEditFuture"
      @refresh="getFutureDues()"
      :data="selectedData"
    />

    <addFutureModal
      v-model="showAddFutureDues"
      @update="updateAddFuture"
      @refresh="getFutureDues()"
    />

    <editCalcModal
      v-model="showEditCalculation"
      @update="updateEditCalculation"
      @refresh="getCalculations()"
      :data="selectedData"
    />

    <addCalcModal
      v-model="showAddCalculation"
      @update="updateAddCalculation"
      @refresh="getCalculations()"
    />
    <!-- modals -->
  </div>
</template>

<script>
export default {
  props: {
    id: Number,
    statuses: { type: Object, default: () => [] },
    info: { type: Object, default: () => [] },
  },
  data() {
    return {
      fields: [
        // { key: "id", label: "#" },
        { key: "inq_no", label: "INQ No." },
        { key: "client", label: "Customer" },
        { key: "desc", label: "Product name" },
        { key: "qty", label: "Quantity" },
        { key: "supplier_id.company", label: "Supplier" },
        { key: "buying_price", label: "Buying proce" },
        { key: "buying_currency", label: "Buying currency" },
        { key: "buying_total_price_aed", label: "Buying total price AED" },
        { key: "quoted_price", label: "Qouted price" },
        { key: "quoted_currency", label: "Qouted currency" },
        { key: "quoted_price_aed", label: "Qouted price AED" },
        { key: "actual_ordered_price", label: "Actual ordered price" },
        { key: "actual_ordered_price_aed", label: "Actual ordered price AED" },
        { key: "remark.body", label: "Remark" },
        { key: "deleted_at", label: "deleted date" },
        { key: "action", label: "Action" },
      ],
      userData: JSON.parse(localStorage.getItem("userData")),
      fieldsFutureDues: [
        // { key: "id", label: "#" },
        { key: "inquiry_id", label: "INQ ID" },
        { key: "client", label: "Customer" },
        { key: "desc", label: "Description" },
        { key: "bill_to", label: "Bill to" },
        { key: "payee_name", label: "Payee name" },
        { key: "payable_amount", label: "Payable amount" },
        { key: "receivable_amount", label: "Receivable amount" },
        { key: "currency", label: "Currency" },
        { key: "due_date", label: "Due date" },
        { key: "is_paid", label: "Is paid" },
        { key: "action", label: "Action" },
      ],
      showAddCalculation: false,
      showEditCalculation: false,
      showEditFutureDues: false,
      showAddFutureDues: false,
      whilePaid: false,
      allCalculations: null,
      metaCalc: null,
      metaFeat: null,
      selectedData: {},
      allFutureDues: null,
      whileDeletingCalc: false,
      clientConfidentialNote: "",
    };
  },
  mounted() {
    this.getCalculations();
    this.getFutureDues();
    this.getClientData();
  },

  methods: {
    getClientData() {
      axios.get(`/admin/users/${this.info.client.id}`).then((response) => {
        this.clientConfidentialNote = response.data.data.confidential;
      });
    },
    async showEditCalcModal(data) {
      this.selectedData = { ...data };
      this.showEditCalculation = !this.showEditCalculation;
    },
    async showEditFeatModal(data) {
      this.selectedData = { ...data };
      this.showEditFutureDues = !this.showEditFutureDues;
    },
    deleteCalc(id) {
      this.whileDeletingCalc = true;
      axios
        .delete(`/admin/inquiries/${this.$route.params.id}/calculations/${id}`)
        .then((res) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `You have successfully deleted calculation ${name}!`,
            },
          });
          this.getCalculations();
          this.whileDeletingCalc = false;
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
    updateAddCalculation(val) {
      this.showAddCalculation = val;
    },
    updateEditCalculation(val) {
      this.showEditCalculation = val;
    },
    updateAddFuture(val) {
      this.showAddFutureDues = val;
    },
    updateEditFuture(val) {
      this.showEditFutureDues = val;
    },
    getCalculations() {
      try {
        axios
          .get(`/admin/inquiries/${this.$route.params.id}/calculations`)
          .then((response) => {
            this.allCalculations = response.data.data;
            this.metaCalc = response.data.data.meta;
            this.allCalculations.calculations.forEach((element) => {
              if (element.deleted_at != null) element._rowVariant = "secondary";
            });
          })
          .catch((err) => {
            console.error(err);
          });
      } catch (error) {}
    },
    getFutureDues() {
      try {
        axios
          .get(`/admin/inquiries/${this.$route.params.id}/future-dues`)
          .then((response) => {
            this.metaFeat = response.data.data.meta;
            this.allFutureDues = response.data.data.future_dues;
          })
          .catch((err) => {
            console.error(err);
          });
      } catch (error) {}
    },
    paidFutureDue(id) {
      axios
        .post(
          `/admin/inquiries/${this.$route.params.id}/future-dues/${id}/payment-status`,
          { is_paid: 1 }
        )
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            position: "top-right",
            props: {
              title: "Success",
              icon: "CoffeeIcon",
              variant: "success",
              text: `The payment status of row ${id} successfully changed to paid!`,
            },
          });
          this.getFutureDues();
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
    addCalcModal: () => import("./calculation/addCalcModal.vue"),
    editCalcModal: () => import("./calculation/editCalcModal.vue"),
    addFutureModal: () => import("./calculation/addFutureModal.vue"),
    editFutureModal: () => import("./calculation/editFutureModal.vue"),
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
