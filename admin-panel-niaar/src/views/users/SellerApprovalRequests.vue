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
                        <h6>Manage Seller Approval Requests</h6>
                        <b-breadcrumb class="breadcrumb-slash p-0">
                            <b-breadcrumb-item href="/">
                                Home
                            </b-breadcrumb-item>
                            <b-breadcrumb-item href="/">
                                Users
                            </b-breadcrumb-item>
                            <b-breadcrumb-item active>
                                Seller Approval Requests
                            </b-breadcrumb-item>
                        </b-breadcrumb>
                    </div>
                </div>
            </b-col>
        </b-row>
        <!-- collapse button -->
        <b-card
            no-body
            class="
                collapsible-form
                px-2
                py-1
                d-flex
                flex-row
                justify-content-between
                align-items-center
            "
            @click="openRequestsFilterForm"
            v-b-toggle.requests-filter
        >
            <div>
                <feather-icon icon="SearchIcon" style="transform: scale(1.2)" />
                <span class="ml-1 fs-sm">Search In Requests ...</span>
            </div>
            <b-button variant="flat-primary" class="btn-icon" size="sm">
                <feather-icon
                    :icon="
                        requestsFilterFormCollapsed
                            ? 'ChevronUpIcon'
                            : 'ChevronDownIcon'
                    "
                    style="transform: scale(1.4)"
                />
            </b-button>
        </b-card>
        <!-- collapse content (requests filter form) -->
        <b-collapse id="requests-filter" class="mt-1">
            <b-card no-body>
                <b-card-body>
                    <b-row class="mb-1">
                        <b-col cols="12" md="6">
                            <b-form-group label="Keyword" label-for="keyword">
                                <b-form-input
                                    id="keyword"
                                    v-model="keyword"
                                    placeholder="Enter Keyword"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Status" label-for="status">
                                <v-select
                                    label="title"
                                    id="status"
                                    :options="activeStatus"
                                    v-model="selectedActiveStatus"
                                    placeholder="Select An Option"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Date From"
                                label-for="date-from"
                            >
                                <b-form-datepicker
                                    id="date-from"
                                    v-model="dateFrom"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Date To" label-for="date-to">
                                <b-form-datepicker
                                    id="date-to"
                                    v-model="dateTo"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button size="sm" variant="primary" class="mr-1">
                        Filter
                    </b-button>
                    <b-button size="sm" variant="flat-danger"> Reset </b-button>
                </b-card-body>
            </b-card>
        </b-collapse>
        <!-- requests table -->
        <b-row class="mt-2">
            <b-col cols="12">
                <div v-if="isLoaded">
                    <b-card v-if="requests.length > 0" no-body>
                        <div
                            class="
                                p-2
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <h4 class="mb-0">Requests List</h4>
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
                        <b-table
                            responsive
                            :striped="striped"
                            :bordered="bordered"
                            :outlined="outlined"
                            :fields="fields"
                            :items="requests"
                        >
                            <template #cell(id)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(reference_number)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(name)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(username_email)="data">
                                <div>
                                    <strong>Username: </strong>
                                    {{ data.item.username }}
                                </div>
                                <div>
                                    <strong>Email: </strong>
                                    {{ data.item.email }}
                                </div>
                            </template>
                            <template #cell(request_date)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(status)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(action)="data">
                                <b-button
                                    variant="gradient-primary"
                                    class="btn-icon"
                                    v-b-tooltip.hover.top="'View'"
                                    @click="sellerApprovalRequestModal = true"
                                >
                                    <feather-icon icon="EyeIcon" />
                                </b-button>
                            </template>
                        </b-table>

                        <b-pagination
                            class="ml-1"
                            v-model="page"
                            :per-page="perPage"
                            :total-rows="count"
                            @change="pageChangeHandler"
                        ></b-pagination>
                    </b-card>
                    <b-card v-else class="text-center">
                        <h4 class="text-secondary mb-0">No records to show ..</h4>
                    </b-card>
                </div>
                <div v-else class="text-center my-3">
                    <b-spinner label="Loading..." />
                    <span class="d-block mt-1"> Loading Content ... </span>
                </div>
            </b-col>
        </b-row>

        <seller-approval-request-modal :show="sellerApprovalRequestModal" />
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
    BBadge,
    VBTooltip,
} from "bootstrap-vue";
import VSelect from "vue-select";
import SellerApprovalRequestModal from '../components/users-list/seller-approval-request/SellerApprovalRequestModal.vue';

export default {
    components: {
        BRow,
        BCol,
        BAvatar,
        BBreadcrumb,
        BBreadcrumbItem,
        BButton,
        BCollapse,
        BCard,
        BCardBody,
        BFormGroup,
        BFormInput,
        VSelect,
        BFormDatepicker,
        BTable,
        BSpinner,
        BFormCheckbox,
        BDropdown,
        BDropdownItem,
        BPagination,
        BBadge,
        SellerApprovalRequestModal,
    },
    directives: {
        "b-toggle": VBToggle,
        "b-tooltip": VBTooltip,
    },
    computed: {
        activeStatus() {
            return [
                { id: 1, title: "All" },
                { id: 2, title: "Pending" },
                { id: 3, title: "Approved" },
                { id: 4, title: "Cancelled" },
            ];
        },
        checkHasAnyUserSelected() {
            return this.selectedUsers.length > 0;
        },
    },
    methods: {
        openRequestsFilterForm() {
            this.requestsFilterFormCollapsed = this.requestsFilterFormCollapsed
                ? false
                : true;
        },
        pageChangeHandler() {
            console.log("change page");
        },
        selectAllHandler() {
            if (this.isSelectedAll) {
                this.selectedUsers = _.map(this.users, "id");
            } else {
                this.selectedUsers = [];
            }
        },
    },
    data() {
        return {
            // form properties
            selectedActiveStatus: undefined,
            requestsFilterFormCollapsed: false,
            keyword: undefined,
            selectedUserType: undefined,
            dateFrom: undefined,
            dateTo: undefined,
            // loading process
            isLoaded: false,
            // table properties
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                { key: "id", label: "#" },
                { key: "reference_number", label: "Reference Number" },
                { key: "name", label: "Name" },
                { key: "username_email", label: "Username/Email" },
                { key: "request_date", label: "Requested On" },
                { key: "status", label: "Status" },
                { key: "action", label: "Actions" },
            ],
            requests: [
                {
                    id: 1,
                    reference_number: "127110",
                    name: "Mahdi Arjangi",
                    email: "mahdi@mail.com",
                    username: "mahdiarjangi",
                    request_date: "2020/2/12 09:01",
                    status: "Approved",
                },
                {
                    id: 2,
                    reference_number: "127187",
                    name: "Farshid Sohrabiani",
                    email: "farshid@mail.com",
                    username: "farshidsohrabiani",
                    request_date: "2020/2/02 19:39",
                    status: "Approved",
                },
                {
                    id: 3,
                    reference_number: "127007",
                    name: "Mahsa Goudarzi",
                    email: "mahsa@mail.com",
                    username: "mahsagoudarzi",
                    request_date: "2020/2/19 12:51",
                    status: "Approved",
                },
            ],
            // table pagination
            page: 1,
            perPage: 10,
            count: 34,
            // seller approval request modal
            sellerApprovalRequestModal: false
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