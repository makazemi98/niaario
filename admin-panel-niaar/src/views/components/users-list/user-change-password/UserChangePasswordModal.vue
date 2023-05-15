<template>
    <b-modal
        title="Change Password"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-form @submit.prevent="changePasswordSubmitHandler">
            <b-row>
                <b-col cols="12">
                    <b-form-group label="New Password" label-for="new-password">
                        <b-form-input
                            id="new-password"
                            v-model="newPassword"
                            placeholder="Enter New Password"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12">
                    <b-form-group label="Confirm New Password" label-for="confirm-new-password">
                        <b-form-input
                            id="confirm-new-password"
                            v-model="confirmNewPassword"
                            placeholder="Enter Confirm New Password"
                        />
                    </b-form-group>
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
    BButton,
    BFormInput,
} from "bootstrap-vue";

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
    },
    methods: {
        changePasswordSubmitHandler() {},
    },
    data() {
        return {
            // change password form
            newPassword: undefined,
            confirmNewPassword: undefined,
        };
    },
};
</script>

<style>
</style>