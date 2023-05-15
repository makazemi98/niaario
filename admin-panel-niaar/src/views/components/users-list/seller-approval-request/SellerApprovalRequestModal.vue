<template>
    <b-modal
        title="User Details"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
        size="lg"
    >
        <b-card no-body>
            <div class="div px-2 pt-2">
                <h5 class="mb-0">Profile Information</h5>
            </div>
            <hr class="my-2" />
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Full Name</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ profileInfo.full_name }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Email</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ profileInfo.email }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Username</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ profileInfo.username }}
                </b-col>
            </b-row>
        </b-card>
        <b-card no-body>
            <div class="div px-2 pt-2">
                <h5 class="mb-0">Seller Information</h5>
            </div>
            <hr class="my-2" />
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Reference Number</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ sellerInfo.reference_number }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Status</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ sellerInfo.status }}
                </b-col>
            </b-row>
        </b-card>
        <b-card no-body>
            <div class="div px-2 pt-2">
                <h5 class="mb-0">Additional  Information</h5>
            </div>
            <hr class="my-2" />
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Contact Number</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ additionalInfo.contact_number }}
                </b-col>
            </b-row>
            <b-row class="px-2 pb-2">
                <b-col cols="12" md="4">
                    <strong>Business Name</strong>
                </b-col>
                <b-col cols="12" md="8">
                    {{ additionalInfo.business_name }}
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
            profileInfo: {
                full_name: "KH",
                username: "nonemail95021",
                email: "nonemail95021@gmail.com",
            },
            sellerInfo: {
                reference_number: 54545544,
                status: "Approved",
            },
            additionalInfo: {
                contact_number: 54545544,
                business_name: "Mahdi",
            },
        };
    },
};
</script>

<style>
</style>