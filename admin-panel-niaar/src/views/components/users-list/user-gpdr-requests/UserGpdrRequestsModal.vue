<template>
    <b-modal
        title="User Request"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
        size="lg"
    >
        <b-card no-body>
            <div class="div px-2 pt-2">
                <h5 class="mb-0">User Request</h5>
            </div>
            <hr class="my-2" />
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Full Name</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ info.full_name }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Email</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ info.email }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Username</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ info.username }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Purpose Of Request</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ info.purpose_of_request || '-----'}}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Request Date</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ info.request_date }}
                </b-col>
            </b-row>
        </b-card>
    </b-modal>
</template>

<script>
// * components
import {
    BModal,
    BRow,
    BCol,
    BCard,
    BCardHeader,
    BCardBody,
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
        BCard,
        BCardHeader,
        BCardBody,
    },
    data() {
        return {
            info: {
                full_name: "Michael Williams",
                email: "login@dummyid.com",
                username: "michael",
                purpose_of_request: undefined,
                request_date: "2020-12-21 12:03:14"
            },
        };
    },
};
</script>

<style>
</style>