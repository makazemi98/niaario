<template>
    <section>
        <!-- page header (breadcrumb) -->
        <b-row class="page-header mb-2">
            <b-col cols="12">
                <div
                    class="
                        d-flex
                        flex-row
                        justify-content-start
                        align-items-center
                    "
                >
                    <b-avatar size="lg" rounded="sm" variant="light-primary">
                        <feather-icon
                            icon="StarIcon"
                            style="transform: scale(1.7)"
                        />
                    </b-avatar>
                    <div
                        class="
                            d-flex
                            flex-column
                            justify-content-start
                            align-items-start
                            ml-1
                        "
                    >
                        <h6>Manage Seller Approval Form</h6>
                        <b-breadcrumb class="breadcrumb-slash p-0">
                            <b-breadcrumb-item href="/">
                                Home
                            </b-breadcrumb-item>
                            <b-breadcrumb-item href="/">
                                Users
                            </b-breadcrumb-item>
                            <b-breadcrumb-item active>
                                Seller Form
                            </b-breadcrumb-item>
                        </b-breadcrumb>
                    </div>
                </div>
            </b-col>
        </b-row>
        <!-- forms table -->
        <b-row class="mt-2">
            <b-col cols="12">
                <div v-if="isLoaded">
                    <b-card v-if="forms.length > 0" no-body>
                        <div
                            class="
                                p-2
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <h4 class="mb-0">Form Fields</h4>
                            <b-button
                                variant="gradient-success"
                                class="btn-icon"
                                v-b-tooltip.hover.top="'Add New'"
                                @click="sellerApprovalFormModal = true"
                            >
                                <feather-icon icon="PlusIcon" />
                            </b-button>
                        </div>
                        <!-- <div>
                            <b-alert
                                v-height-fade.appear
                                :show="this.alert.visible"
                                dismissible
                                class="mb-0"
                                :variant="this.alert.type"
                            >
                                <div class="alert-body">
                                    {{ this.alert.text }}
                                </div>
                            </b-alert>
                        </div> -->

                        <b-table-simple bordered caption-top responsive>
                            <b-thead head-variant="light">
                                <b-tr>
                                    <b-th
                                        v-for="field in fields"
                                        :key="field.key"
                                    >
                                        {{
                                            field.label
                                                ? field.label.toUpperCase()
                                                : field.label
                                        }}
                                    </b-th>
                                </b-tr>
                            </b-thead>
                            <draggable
                                v-model="forms"
                                tag="tbody"
                                role="nogroup"
                            >
                                <b-list-group-item
                                    v-for="items in forms"
                                    :key="items.id"
                                    tag="tr"
                                >
                                    <b-td>
                                        <feather-icon
                                            icon="MoveIcon"
                                        ></feather-icon>
                                    </b-td>
                                    <b-td
                                        v-for="(item, index) in items"
                                        :key="index"
                                    >
                                        {{ item }}
                                    </b-td>
                                    <b-td>
                                        <b-button
                                            variant="gradient-warning"
                                            class="btn-icon"
                                            v-b-tooltip.hover.top="'Edit'"
                                            @click="sellerApprovalFormModal = true"
                                        >
                                            <feather-icon icon="Edit3Icon" />
                                        </b-button>
                                    </b-td>
                                </b-list-group-item>
                            </draggable>
                        </b-table-simple>

                        <b-pagination
                            class="ml-1"
                            v-model="page"
                            :per-page="perPage"
                            :total-rows="count"
                            @change="pageChangeHandler"
                        ></b-pagination>
                    </b-card>
                    <b-card v-else class="text-center">
                        <h4 class="text-secondary mb-0">
                            Admin No records to show ..
                        </h4>
                    </b-card>
                </div>
                <div v-else class="text-center my-3">
                    <b-spinner label="Loading..." />
                    <span class="d-block mt-1"> Loading Content ... </span>
                </div>
            </b-col>
        </b-row>
        <seller-approval-form-modal :show="sellerApprovalFormModal" />
    </section>
</template>

<script>
// * dependencies
import _ from "lodash";
// * components
import {
    BRow,
    BCol,
    BAvatar,
    BBreadcrumb,
    BBreadcrumbItem,
    BButton,
    BCard,
    BCardBody,
    BTableSimple,
    BTbody,
    BThead,
    BTfoot,
    BTh,
    BTr,
    BTd,
    BSpinner,
    BFormCheckbox,
    BPagination,
    VBTooltip,
    BListGroupItem,
} from "bootstrap-vue";
import draggable from "vuedraggable";
import SellerApprovalFormModal from "../components/users-list/seller-approval-form/SellerApprovalFormModal.vue";

export default {
    components: {
        BRow,
        BCol,
        BAvatar,
        BBreadcrumb,
        BBreadcrumbItem,
        BButton,
        BCard,
        BCardBody,
        BTableSimple,
        BTbody,
        BThead,
        BTfoot,
        BTh,
        BTr,
        BTd,
        BSpinner,
        BFormCheckbox,
        BPagination,
        draggable,
        BListGroupItem,
        SellerApprovalFormModal,
    },
    directives: {
        "b-tooltip": VBTooltip,
    },
    methods: {
        pageChangeHandler() {
            console.log("change page");
        },
        selectAllHandler() {
            if (this.isSelectedAll) {
                this.selectedUsers = _.map(this.adminUsers, "id");
            } else {
                this.selectedUsers = [];
            }
        },
    },
    data() {
        return {
            // loading process
            isLoaded: false,
            // table properties
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                { key: "drag" },
                { key: "id", label: "#" },
                { key: "caption", label: "Caption" },
                { key: "type", label: "Type" },
                { key: "required", label: "Required" },
                { key: "action", label: "Actions" },
            ],
            forms: [
                {
                    id: 1,
                    caption: "Contact Number",
                    type: "Phone",
                    required: "No",
                },
                {
                    id: 2,
                    caption: "Business Name",
                    type: "Textbox",
                    required: "Yes",
                },
                {
                    id: 3,
                    caption: "Contact Person",
                    type: "Textbox",
                    required: "No",
                },
            ],
            // table pagination
            page: 1,
            perPage: 10,
            count: 34,
            // seller forms edit
            sellerApprovalFormModal: false
        };
    },
    mounted() {
        this.isLoaded = true;
    },
};
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>