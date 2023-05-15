<template>
    <b-modal
        title="Set Up Form Fields"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-tabs>
            <b-tab title="General">
                <b-form @submit.prevent="generalTabSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="12">
                            <b-form-group
                                label="Identifier"
                                label-for="identifier"
                            >
                                <b-form-input
                                    id="identifier"
                                    v-model="Identifier"
                                    placeholder="Enter Identifier"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="12">
                            <b-form-group label="Required" label-for="required">
                                <v-select
                                    label="title"
                                    id="required"
                                    :options="required"
                                    placeholder="Select An Option"
                                    v-model="selectedRequired"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="12">
                            <b-form-group
                                label="Field Type"
                                label-for="field-type"
                            >
                                <v-select
                                    label="title"
                                    id="field-type"
                                    :options="fieldTypes"
                                    placeholder="Select An Option"
                                    v-model="selectedFieldType"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button variant="primary" class="mt-2">
                        Save Changes
                    </b-button>
                </b-form>
            </b-tab>
            <b-tab title="Language Data" :disabled="!isGeneralSaved">
                <b-form @submit.prevent="languageDataTabSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="12">
                            <b-form-group label="Language" label-for="language">
                                <v-select
                                    label="title"
                                    id="language"
                                    :options="languages"
                                    placeholder="Select An Option"
                                    v-model="selectedLanguage"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="12">
                            <b-form-group label="Caption" label-for="caption">
                                <b-form-input
                                    id="caption"
                                    v-model="caption"
                                    placeholder="Enter Caption"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="12">
                            <b-form-group label="Comments" label-for="comments">
                                <b-form-textarea
                                    id="comments"
                                    v-model="comments"
                                    placeholder="Enter Comments"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="12">
                            <b-form-checkbox
                                v-model="updateLanguageChecked"
                                class="custom-control-primary"
                                value="true"
                            >
                                Update Other Languages Data
                            </b-form-checkbox>
                        </b-col>
                    </b-row>
                    <b-button variant="primary" class="mt-2">
                        Save Changes
                    </b-button>
                </b-form>
            </b-tab>
        </b-tabs>
    </b-modal>
</template>

<script>
// * components
import {
    BModal,
    BRow,
    BCol,
    BTabs,
    BTab,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BFormCheckbox,
    BButton,
    VBTooltip,
    BTable,
    BPagination,
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
        BTabs,
        BTab,
        BForm,
        BFormGroup,
        BFormInput,
        BFormTextarea,
        BFormCheckbox,
        BButton,
        BTable,
        BPagination,
        VSelect,
    },
    directives: {
        "b-tooltip": VBTooltip,
    },
    methods: {
        languageDataTabSubmitHandler() {},
        generalTabSubmitHandler() {},
        pageChangeHandler() {},
    },
    data() {
        return {
            // seller approval form
            Identifier: undefined,
            selectedRequired: undefined,
            selectedFieldType: undefined,
            selectedLanguage: undefined,
            caption: undefined,
            comments: undefined,
            updateLanguageChecked: undefined,

            required: [
                {
                    id: 1,
                    title: "Yes"
                },
                {
                    id: 2,
                    title: "No"
                },
            ],
            fieldTypes: [
                {
                    id: 1,
                    title: "Textbox"
                },
                {
                    id: 2,
                    title: "Text Area"
                },
                {
                    id: 3,
                    title: "File"
                },
                {
                    id: 4,
                    title: "Date"
                },
                {
                    id: 5,
                    title: "Datetime"
                },
                {
                    id: 6,
                    title: "Time"
                },
                {
                    id: 7,
                    title: "Phone"
                },
            ],
            languages: [
                {
                    id: 1,
                    title: "English"
                },
                {
                    id: 2,
                    title: "Arabic"
                }
            ],
            isGeneralSaved: true,
        };
    },
};
</script>

<style>
</style>