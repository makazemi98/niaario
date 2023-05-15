<template>
    <b-modal
        title="User Setup"
        size="lg"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-tabs>
            <b-tab title="General">
                <b-form @submit.prevent="editModalGeneralSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label="Username" label-for="username">
                                <b-form-input
                                    id="username"
                                    v-model="editUsername"
                                    placeholder="Enter Username"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Full Name"
                                label-for="full-name"
                            >
                                <b-form-input
                                    id="full-name"
                                    placeholder="Enter Full Name"
                                    v-model="editFullName"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Date Of Birth"
                                label-for="date-of-birth"
                            >
                                <b-form-datepicker
                                    id="date-of-birth"
                                    v-model="editDateOfBirth"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Phone" label-for="phone">
                                <b-input-group>
                                    <b-input-group-prepend class="flags-select">
                                        <v-select
                                            v-model="editSelectedLocal"
                                            :options="locals"
                                            label="dial_code"
                                            placeholder="Countries"
                                            transition=""
                                        >
                                            <template
                                                #option="{ code, flag, name }"
                                            >
                                                <img
                                                    :src="flag"
                                                    :alt="name"
                                                    class="
                                                        country-flag
                                                        rounded-circle
                                                        mr-50
                                                    "
                                                />
                                                <span> {{ code }} </span>
                                            </template>
                                        </v-select>
                                    </b-input-group-prepend>
                                    <b-form-input placeholder="Phone Number" />
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Email" label-for="email">
                                <b-form-input
                                    id="email"
                                    placeholder="Enter Email"
                                    v-model="editEmail"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Country" label-for="country">
                                <v-select
                                    id="country"
                                    label="title"
                                    :options="countries"
                                    v-model="editSelectedCountry"
                                    placeholder="Select An Option"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="State" label-for="state">
                                <v-select
                                    id="state"
                                    label="title"
                                    :options="states"
                                    v-model="editSelectedState"
                                    placeholder="Select An Option"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="City" label-for="city">
                                <b-form-input
                                    id="city"
                                    placeholder="City"
                                    v-model="editCity"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button variant="primary" class="mt-2">
                        Save Changes
                    </b-button>
                </b-form>
            </b-tab>
            <b-tab title="Bank Info">
                <b-form @submit.prevent="editModalBankInfoSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Bank Name"
                                label-for="bank-name"
                            >
                                <b-form-input
                                    id="bank-name"
                                    v-model="editBankName"
                                    placeholder="Enter Bank Name"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Account Holder Name"
                                label-for="account-holder-name"
                            >
                                <b-form-input
                                    id="account-holder-name"
                                    v-model="editAccountHolderName"
                                    placeholder="Enter Account Holder Name"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Account Number"
                                label-for="account-number"
                            >
                                <b-form-input
                                    id="account-number"
                                    placeholder="Enter Account Number"
                                    v-model="editAccountNumber"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="IFSC/Swift Code"
                                label-for="swift-code"
                            >
                                <b-form-input
                                    id="swift-code"
                                    v-model="editSwiftCode"
                                    placeholder="Enter Swift Code"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12">
                            <b-form-group
                                label="Bank Address"
                                label-for="bank-address"
                            >
                                <b-form-textarea
                                    id="bank-address"
                                    v-model="editBankAddress"
                                    placeholder="Enter Bank Address"
                                    rows="3"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button variant="primary" class="mt-2">
                        Save Changes
                    </b-button>
                </b-form>
            </b-tab>
            <b-tab title="Addresses">
                <b-card v-if="addNewAddressStatus">
                    <b-form @submit.prevent="addNewAddressSubmitHandler">
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Address Label"
                                    label-for="address-label"
                                >
                                    <b-form-input
                                        id="address-label"
                                        v-model="newAddressLabel"
                                        placeholder="Enter Address Label"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Name" label-for="name">
                                    <b-form-input
                                        id="name"
                                        v-model="newAddressName"
                                        placeholder="Enter Name"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Address Line 1"
                                    label-for="address-line-1"
                                >
                                    <b-form-datepicker
                                        id="address-line-1"
                                        v-model="newAddressLine1"
                                        placeholder="Enter Address Line 1"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Address Line 2"
                                    label-for="address-line-2"
                                >
                                    <b-form-datepicker
                                        id="address-line-2"
                                        v-model="newAddressLine2"
                                        placeholder="Enter Address Line 2"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="Phone" label-for="phone">
                                    <b-input-group>
                                        <b-input-group-prepend
                                            class="flags-select"
                                        >
                                            <v-select
                                                v-model="
                                                    newAddressSelectedLocal
                                                "
                                                :options="locals"
                                                label="dial_code"
                                                placeholder="Countries"
                                                transition=""
                                            >
                                                <template
                                                    #option="{
                                                        code,
                                                        flag,
                                                        name,
                                                    }"
                                                >
                                                    <img
                                                        :src="flag"
                                                        :alt="name"
                                                        class="
                                                            country-flag
                                                            rounded-circle
                                                            mr-50
                                                        "
                                                    />
                                                    <span> {{ code }} </span>
                                                </template>
                                            </v-select>
                                        </b-input-group-prepend>
                                        <b-form-input
                                            placeholder="Phone Number"
                                        />
                                    </b-input-group>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Postal Code"
                                    label-for="postal-code"
                                >
                                    <b-form-input
                                        id="postal-code"
                                        placeholder="Enter Postal Code"
                                        v-model="newAddressPostalCode"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group
                                    label="Country"
                                    label-for="country"
                                >
                                    <v-select
                                        id="country"
                                        label="title"
                                        :options="countries"
                                        v-model="newAddressSelectedCountry"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="State" label-for="state">
                                    <v-select
                                        id="state"
                                        label="title"
                                        :options="states"
                                        v-model="newAddressSelectedState"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="6">
                                <b-form-group label="City" label-for="city">
                                    <b-form-input
                                        id="city"
                                        placeholder="City"
                                        v-model="newAddressCity"
                                    />
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <div class="mt-2">
                            <b-button
                                type="submit"
                                variant="primary"
                                class="mr-50"
                            >
                                Save Changes
                            </b-button>
                            <b-button
                                variant="flat-danger"
                                @click="addNewAddressStatus = false"
                            >
                                Close
                            </b-button>
                        </div>
                    </b-form>
                </b-card>
                <div v-else>
                    <span>
                        <b-button
                            size="sm"
                            variant="gradient-success"
                            class="mb-1 d-inline-flex"
                            @click="addNewAddressStatus = true"
                        >
                            Add New Address
                        </b-button>
                    </span>

                    <b-table
                        responsive
                        :striped="striped"
                        :bordered="bordered"
                        :outlined="outlined"
                        :fields="fields"
                        :items="addresses"
                    >
                        <template #cell(id)="data">
                            {{ data.value }}
                        </template>
                        <template #cell(identifier)="data">
                            {{ data.value }}
                        </template>
                        <template #cell(address)="data">
                            {{ data.value }}
                        </template>
                        <template #cell(default)="data">
                            {{ data.value }}
                        </template>
                        <template #cell(action)="data">
                            <b-button
                                variant="gradient-warning"
                                class="btn-icon mr-50"
                                v-b-tooltip.hover.top="'Edit'"
                            >
                                <feather-icon icon="Edit3Icon" />
                            </b-button>
                            <b-button
                                variant="gradient-danger"
                                class="btn-icon"
                                v-b-tooltip.hover.top="'Delete'"
                            >
                                <feather-icon icon="TrashIcon" />
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
                </div>
            </b-tab>
        </b-tabs>
    </b-modal>
</template>

<script>
// * dependencies
import _ from "lodash";
import countries from "@/data/countries";
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
    BInputGroup,
    BFormDatepicker,
    BInputGroupPrepend,
    BFormTextarea,
    BFormCheckbox,
    BCard,
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
        BInputGroup,
        BFormDatepicker,
        BInputGroupPrepend,
        BFormTextarea,
        BFormCheckbox,
        BCard,
        VSelect,
        BButton,
        BTable,
        BPagination,
    },
    directives: {
        "b-tooltip": VBTooltip,
    },
    methods: {
        editModalGeneralSubmitHandler() {},
        editModalBankInfoSubmitHandler() {},
        addNewAddressSubmitHandler() {},
        pageChangeHandler() {},
    },
    data() {
        return {
            // general
            editCity: undefined,
            editSelectedState: undefined,
            states: [],
            editSelectedCountry: undefined,
            countries: [],
            editEmail: undefined,
            locals: countries,
            editSelectedLocal: undefined,
            editUsername: undefined,
            editFullName: undefined,
            editDateOfBirth: undefined,
            // bank info
            editBankName: undefined,
            editAccountHolderName: undefined,
            editAccountNumber: undefined,
            editSwiftCode: undefined,
            editBankAddress: undefined,
            // new address
            addNewAddressStatus: false,
            newAddressLabel: undefined,
            newAddressName: undefined,
            newAddressLine1: undefined,
            newAddressLine2: undefined,
            newAddressSelectedLocal: undefined,
            newAddressPostalCode: undefined,
            newAddressSelectedCountry: undefined,
            newAddressSelectedState: undefined,
            newAddressCity: undefined,
            // addresses pagination
            page: 1,
            perPage: 10,
            count: 34,
            // addresses table
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                {
                    key: "id",
                    label: "#",
                },
                {
                    key: "identifier",
                    label: "Identifier",
                },
                {
                    key: "address",
                    label: "Address",
                },
                {
                    key: "default",
                    label: "Default",
                },
                {
                    key: "action",
                    label: "Actopm",
                },
            ],
            addresses: [
                {
                    id: 1,
                    identifier: 1,
                    address: "Iran, Tehran",
                    default: "No",
                },
            ],
        };
    },
};
</script>

<style>
</style>