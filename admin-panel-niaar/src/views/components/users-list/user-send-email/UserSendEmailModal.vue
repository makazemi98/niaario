<template>
    <b-modal
        title="Send Email"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-form @submit.prevent="sendEmailSubmitHandler">
            <b-row>
                <b-col cols="12">
                    <b-form-group label="Subject" label-for="subject">
                        <b-form-input
                            id="subject"
                            v-model="subject"
                            placeholder="Enter Subject"
                        />
                    </b-form-group>
                </b-col>
                <b-col cols="12">
                    <b-form-group label="Message" label-for="message">
                        <b-form-textarea
                            id="message"
                            v-model="message"
                            placeholder="Enter Message"
                        />
                    </b-form-group>
                </b-col>
            </b-row>
            <b-button variant="primary" class="mt-2"> Send Email </b-button>
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
    BFormTextarea,
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
        BFormTextarea,
        BFormInput,
        BButton,
    },
    methods: {
        sendEmailSubmitHandler() {},
    },
    data() {
        return {
            // send email form
            subject: undefined,
            message: undefined,
        };
    },
};
</script>

<style>
</style>