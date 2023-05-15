<template>
    <b-modal
        title="User Transactions"
        size="xl"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-tabs>
            <b-tab title="Transactions">
                <b-table
                    responsive
                    :striped="striped"
                    :bordered="bordered"
                    :outlined="outlined"
                    :fields="fields"
                    :items="transactions"
                >
                    <template #cell(transaction_id)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(date)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(credit)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(debit)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(balance)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(description)="data">
                        <div>
                            {{ data.item.description.status }}
                        </div>
                        <div>
                            {{ data.item.description.hash }}
                        </div>
                    </template>
                    <template #cell(status)="data">
                        {{ data.value }}
                    </template>
                </b-table>

                <b-pagination
                    class="ml-1"
                    v-model="page"
                    :per-page="perPage"
                    :total-rows="count"
                    @change="pageChangeHandler"
                ></b-pagination>
            </b-tab>
            <b-tab title="Add Transaction">
                <b-form @submit.prevent="addNewTransactionSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label="Types" label-for="types">
                                <v-select
                                    label="title"
                                    id="types"
                                    :options="types"
                                    placeholder="Select An Option"
                                    v-model="selectedType"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Amount" label-for="amount">
                                <b-form-input
                                    id="amount"
                                    v-model="amount"
                                    placeholder="Enter Amount"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12">
                            <b-form-group
                                label="Description"
                                label-for="description"
                            >
                                <b-form-textarea
                                    id="description"
                                    v-model="description"
                                    placeholder="Enter Description"
                                />
                            </b-form-group>
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
        BButton,
        BTable,
        BPagination,
        VSelect
    },
    directives: {
        "b-tooltip": VBTooltip,
    },
    methods: {
        addNewTransactionSubmitHandler() {},
        pageChangeHandler() {},
    },
    data() {
        return {
            // transaction form
            types: [
                {
                    id: 1,
                    title: "Credit"
                },
                {
                    id: 2,
                    title: "Debit"
                },
            ],
            selectedType: undefined,
            amount: undefined,
            description: undefined,
            // transaction pagination
            page: 1,
            perPage: 10,
            count: 34,
            // transaction table
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                {
                    key: "transaction_id",
                    label: "Transaction Id",
                },
                {
                    key: "date",
                    label: "Date",
                },
                {
                    key: "credit",
                    label: "Credit",
                },
                {
                    key: "debit",
                    label: "Debit",
                },
                {
                    key: "balance",
                    label: "Balance",
                },
                {
                    key: "description",
                    label: "Description",
                },
                {
                    key: "status",
                    label: "Status",
                },
            ],
            transactions: [
                {
                    transaction_id: "TN-00012",
                    date: "2020/02/10",
                    credit: "$100",
                    debit: "$65",
                    balance: "$35",
                    description: {
                        status: "Order Placed",
                        hash: "#O923562",
                    },
                    status: "Completed",
                },
            ],
        };
    },
};
</script>

<style>
</style>