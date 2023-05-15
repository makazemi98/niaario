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
                        <h6>Messages</h6>
                        <b-breadcrumb class="breadcrumb-slash p-0">
                            <b-breadcrumb-item href="/">
                                Home
                            </b-breadcrumb-item>
                            <b-breadcrumb-item active>
                                Messages List
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
            @click="openMessageFilterForm"
            v-b-toggle.messages-filter
        >
            <div>
                <feather-icon icon="SearchIcon" style="transform: scale(1.2)" />
                <span class="ml-1 fs-sm">Search In Messages ...</span>
            </div>
            <b-button variant="flat-primary" class="btn-icon" size="sm">
                <feather-icon
                    :icon="
                        messagesFilterFormCollapsed
                            ? 'ChevronUpIcon'
                            : 'ChevronDownIcon'
                    "
                    style="transform: scale(1.4)"
                />
            </b-button>
        </b-card>
        <!-- collapse content (messages filter form) -->
        <b-collapse id="messages-filter" class="mt-1">
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
                            <b-form-group
                                label="Message By"
                                label-for="message-by"
                            >
                                <b-form-input
                                    id="message-by"
                                    v-model="messageBy"
                                    placeholder="Enter Username"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Message To"
                                label-for="message-to"
                            >
                                <b-form-input
                                    id="message-to"
                                    v-model="messageTo"
                                    placeholder="Enter Username"
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
        <!-- messages table -->
        <b-row class="mt-2">
            <b-col cols="12">
                <div v-if="isLoaded">
                    <b-card v-if="messages.length > 0" no-body>
                        <div
                            class="
                                p-2
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <h4 class="mb-0">Messages List</h4>
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
                            :items="messages"
                        >
                            <template #cell(from)="data">
                                <div
                                    class="
                                        d-flex
                                        flex-row
                                        justify-content-start
                                        align-items-center
                                    "
                                >
                                    <b-avatar
                                        :src="data.item.from.avatar"
                                    ></b-avatar>
                                    <div
                                        class="
                                            ml-1
                                            d-flex
                                            flex-column
                                            justify-content-start
                                            align-items-start
                                        "
                                    >
                                        <strong>
                                            {{ data.item.from.name }}
                                        </strong>
                                        <small class="text-muted">
                                            {{ data.item.from.shop }}
                                        </small>
                                    </div>
                                </div>
                            </template>
                            <template #cell(to)="data">
                                <div
                                    class="
                                        ml-1
                                        d-flex
                                        flex-row
                                        justify-content-start
                                        align-items-center
                                    "
                                >
                                    <b-avatar
                                        :src="data.item.to.avatar"
                                    ></b-avatar>
                                    <div
                                        class="
                                            ml-1
                                            d-flex
                                            flex-column
                                            justify-content-start
                                            align-items-start
                                        "
                                    >
                                        <strong>
                                            {{ data.item.to.name }}
                                        </strong>
                                        <small class="text-muted">
                                            {{ data.item.to.shop }}
                                        </small>
                                    </div>
                                </div>
                            </template>
                            <template #cell(action)="data">
                                <b-button
                                    variant="gradient-primary"
                                    class="btn-icon"
                                    v-b-tooltip.hover.top="'View'"
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
                        <h4 class="text-secondary mb-0">Messages Not Found</h4>
                    </b-card>
                </div>
                <div v-else class="text-center my-3">
                    <b-spinner label="Loading..." />
                    <span class="d-block mt-1"> Loading Content ... </span>
                </div>
            </b-col>
        </b-row>
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
    BPagination,
    VBTooltip
} from "bootstrap-vue";

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
        BFormDatepicker,
        BTable,
        BSpinner,
        BPagination,
    },
    directives: {
        "b-toggle": VBToggle,
        'b-tooltip': VBTooltip,
    },
    methods: {
        openMessageFilterForm() {
            this.messagesFilterFormCollapsed = this.messagesFilterFormCollapsed
                ? false
                : true;
        },
        pageChangeHandler() {
            console.log("change page");
        },
    },
    data() {
        return {
            // form properties
            messagesFilterFormCollapsed: false,
            keyword: undefined,
            messageBy: undefined,
            messageTo: undefined,
            dateFrom: undefined,
            dateTo: undefined,
            // loading process
            isLoaded: false,
            // table properties
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                { key: "from", label: "From" },
                { key: "to", label: "To" },
                { key: "subject", label: "Subject" },
                {
                    key: "message",
                    label: "Message",
                    formatter: (value) => {
                        return value.slice(0, 25) + ' ...';
                    },
                },
                { key: "date", label: "Date" },
                { key: "action", label: "Actions" },
            ],
            messages: [
                {
                    from: {
                        name: "Mahdi Arjangi",
                        shop: "Mahdi's Store",
                        avatar: require("@/assets/images/avatars/2-small.png"),
                    },
                    to: {
                        name: "Farshid Sohrabiani",
                        shop: "Farshid's Store",
                        avatar: require("@/assets/images/avatars/6-small.png"),
                    },
                    subject: "VueJS And Laravel",
                    message:
                        "Although Scott said it didn't matter to him, he knew deep inside that it did",
                    date: "2020/2/12 09:01",
                },
                {
                    from: {
                        name: "Mahdi Arjangi",
                        shop: "Mahdi's Store",
                        avatar: require("@/assets/images/avatars/2-small.png"),
                    },
                    to: {
                        name: "Mahsa Goudarzi",
                        shop: "Mahsa's Store",
                        avatar: require("@/assets/images/avatars/1-small.png"),
                    },
                    subject: "VueJS And ReactJS",
                    message:
                        "They had been friends as long as he could remember and not once had he had to protest that something Joe apologized for doing didn't really matter",
                    date: "2020/2/11 10:11",
                },
            ],
            // table pagination
            page: 1,
            perPage: 10,
            count: 34,
        };
    },
    mounted() {
        setTimeout(() => {
            this.isLoaded = true;
        }, 2000);
    },
};
</script>

<style lang="scss">
@import "@/assets/scss/pages/users.scss";
</style>