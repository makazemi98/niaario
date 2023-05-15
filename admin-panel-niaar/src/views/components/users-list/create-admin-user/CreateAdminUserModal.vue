<template>
    <b-modal
        title="Admin User Setup"
        :value="show"
        :visible="show"
        size="lg"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-form @submit.prevent="createAdminUserSubmitHandler">
            <b-row>
                <b-col cols="12" md="6">
                    <b-form-group label="Full Name" label-for="full-name">
                        <b-form-input
                            id="full-name"
                            v-model="fullName"
                            placeholder="Enter Full Name"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="Username" label-for="username">
                        <b-form-input
                            id="username"
                            v-model="username"
                            placeholder="Enter Username"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="Email" label-for="email">
                        <b-form-input
                            id="email"
                            v-model="email"
                            placeholder="Enter Email"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="New Password" label-for="new-password">
                        <b-form-input
                            id="new-password"
                            v-model="newPassword"
                            placeholder="Enter New Password"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group
                        label="Confirm New Password"
                        label-for="confirm-new-password"
                    >
                        <b-form-input
                            id="confirm-new-password"
                            v-model="confirmNewPassword"
                            placeholder="Enter Confirm New Password"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="6">
                    <b-form-group label="Status" label-for="status">
                        <v-select
                            label="title"
                            id="status"
                            :options="statuses"
                            placeholder="Select An Option"
                            v-model="selectedStatus"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12" md="12">
                    <b-form-checkbox
                        v-model="emailNotificationChecked"
                        class="custom-control-primary"
                        value="true"
                    >
                        Send Email Notification
                    </b-form-checkbox>
                </b-col>
            </b-row>
            <b-button variant="primary" class="mt-2"> Save Changes </b-button>
        </b-form>
    </b-modal>
</template>

<script>
// * components
import {
    BModal,
    BRow,
    BCol,
    BForm,
    BFormGroup,
    BFormCheckbox,
    BButton,
    BFormInput,
} from "bootstrap-vue";
import VSelect from "vue-select";

export default {
    props: {
        show: {
            type: Boolean,
        },
    },
    components: {
        BModal,
        BRow,
        BCol,
        BForm,
        BFormGroup,
        BFormInput,
        BButton,
        BFormCheckbox,
        VSelect,
    },
    methods: {
        createAdminUserSubmitHandler() {},
    },
    data() {
        return {
            // edit admin form
            fullName: undefined,
            username: undefined,
            email: undefined,
            selectedStatus: undefined,
            statuses: [
                {
                    id: 1,
                    title: "Active",
                },
                {
                    id: 2,
                    title: "In Active",
                },
            ],
            newPassword: undefined,
            confirmNewPassword: undefined,
            emailNotificationChecked: undefined,
        };
    },
};
</script>

<style>
</style>