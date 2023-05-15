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
                        <h6>Manage Admin Users</h6>
                        <b-breadcrumb class="breadcrumb-slash p-0">
                            <b-breadcrumb-item href="/">
                                Home
                            </b-breadcrumb-item>
                            <b-breadcrumb-item active>
                                Admin Users List
                            </b-breadcrumb-item>
                        </b-breadcrumb>
                    </div>
                </div>
            </b-col>
        </b-row>
        <!-- admin users table -->
        <b-row class="mt-2">
            <b-col cols="12">
                <div v-if="isLoaded">
                    <b-card v-if="adminUsers.length > 0" no-body>
                        <div
                            class="
                                p-2
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <h4 class="mb-0">Admin Users List</h4>
                            <b-button variant="gradient-success" class="btn-icon" v-b-tooltip.hover.top="'Create New Admin'" @click="createAdminUserModal = true">
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
                        <b-table
                            responsive
                            :striped="striped"
                            :bordered="bordered"
                            :outlined="outlined"
                            :fields="fields"
                            :items="adminUsers"
                        >
                            <template #head(check)>
                                <b-form-checkbox
                                    :value="true"
                                    v-model="isSelectedAll"
                                    @change="selectAllHandler"
                                />
                            </template>
                            <template #cell(check)="data">
                                <b-form-checkbox
                                    v-model="selectedUsers"
                                    :value="data.item.id"
                                />
                            </template>
                            <template #cell(id)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(name)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(username)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(email)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(status)="data">
                                <b-form-checkbox
                                    switch
                                    inline
                                    :checked="data.value"
                                >
                                </b-form-checkbox>
                            </template>
                            <template #cell(action)="data">
                                <b-button variant="gradient-warning" class="btn-icon" v-b-tooltip.hover.top="'Edit'" @click="editAdminUserModal = true">
                                    <feather-icon icon="Edit3Icon" />
                                </b-button>
                                <b-button variant="gradient-primary" class="btn-icon mx-50" v-b-tooltip.hover.top="'Change Password'" @click="userChangePasswordModal = true">
                                    <feather-icon icon="LockIcon" />
                                </b-button>
                                <b-button variant="gradient-secondary" class="btn-icon" v-b-tooltip.hover.top="'Permissions'" to="#">
                                    <feather-icon icon="KeyIcon" />
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
                        <h4 class="text-secondary mb-0">Admin No records to show ..</h4>
                    </b-card>
                </div>
                <div v-else class="text-center my-3">
                    <b-spinner label="Loading..." />
                    <span class="d-block mt-1"> Loading Content ... </span>
                </div>
            </b-col>
        </b-row>
        <edit-admin-user-modal
            :show="editAdminUserModal"
            @toggle="editAdminUserModal = false"
        />
        <create-admin-user-modal
            :show="createAdminUserModal"
            @toggle="createAdminUserModal = false"
        />
        <user-change-password-modal
            :show="userChangePasswordModal"
            @toggle="userChangePasswordModal = false"
        />
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
    BTable,
    BSpinner,
    BFormCheckbox,
    BPagination,
    VBTooltip
} from "bootstrap-vue";
import EditAdminUserModal from '../components/users-list/edit-admin-user/EditAdminUserModal.vue';
import UserChangePasswordModal from '../components/users-list/user-change-password/UserChangePasswordModal.vue';
import CreateAdminUserModal from '../components/users-list/create-admin-user/CreateAdminUserModal.vue';

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
        BTable,
        BSpinner,
        BFormCheckbox,
        BPagination,
        EditAdminUserModal,
        UserChangePasswordModal,
        CreateAdminUserModal,
    },
    directives: {
      'b-tooltip': VBTooltip,
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
                { key: "check" },
                { key: "id", label: "#" },
                { key: "name", label: "Name" },
                { key: "email", label: "Email" },
                { key: "username", label: "Username" },
                { key: "status", label: "Status" },
                { key: "action", label: "Actions" },
            ],
            adminUsers: [
                {
                    id: 1,
                    name: "Mahdi Arjangi",
                    email: "mahdi@mail.com",
                    username: "mahdiarjangi",
                    status: true,
                },
                {
                    id: 2,
                    name: "Farshid Sohrabiani",
                    email: "farshid@mail.com",
                    username: "farshidsohrabiani",
                    status: true,
                },
                {
                    id: 3,
                    name: "Mahsa Goudarzi",
                    email: "mahsa@mail.com",
                    
                    username: "mahsagoudarzi",
                    status: true,
                },
            ],
            isSelectedAll: false,
            selectedUsers: [1],
            // table pagination
            page: 1,
            perPage: 10,
            count: 34,
            // create admin user
            createAdminUserModal: false,
            // edit admin user
            editAdminUserModal: false,
            // change password admin user
            userChangePasswordModal: false
            // change password admin user
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