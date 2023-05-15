<template>
    <b-modal
        title="User Reward Points"
        size="lg"
        :value="show"
        :visible="show"
        @change="$emit('toggle', show)"
        hide-footer
    >
        <b-tabs>
            <b-tab title="Rewards">
                <b-table
                    responsive
                    :striped="striped"
                    :bordered="bordered"
                    :outlined="outlined"
                    :fields="fields"
                    :items="rewards"
                >
                    <template #cell(valid_from)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(valid_till)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(points)="data">
                        {{ data.value }}
                    </template>
                    <template #cell(comments)="data">
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
            <b-tab title="Add Reward">
                <b-form @submit.prevent="addNewRewardSubmitHandler">
                    <b-row>
                        <b-col cols="12" md="6">
                            <b-form-group label="Points" label-for="points">
                                <b-form-input
                                    id="points"
                                    v-model="points"
                                    placeholder="Enter Points"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Validity" label-for="validity">
                                <b-form-input
                                    id="validity"
                                    v-model="validity"
                                    placeholder="Enter Validity"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12">
                            <b-form-group label="Comments" label-for="comments">
                                <b-form-textarea
                                    id="comments"
                                    v-model="comments"
                                    placeholder="Enter Comments"
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
    },
    directives: {
        "b-tooltip": VBTooltip,
    },
    methods: {
        addNewRewardSubmitHandler() {},
        pageChangeHandler() {},
    },
    data() {
        return {
            // reward form
            points: undefined,
            comments: undefined,
            validity: undefined,
            // reward pagination
            page: 1,
            perPage: 10,
            count: 34,
            // reward table
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                {
                    key: "valid_from",
                    label: "Valid From",
                },
                {
                    key: "valid_till",
                    label: "Valid Till",
                },
                {
                    key: "points",
                    label: "Points",
                },
                {
                    key: "comments",
                    label: "Comments",
                },
            ],
            rewards: [
                {
                    valid_from: "2022/08/19",
                    valid_till: "2022/08/19",
                    points: "45",
                    comments: "random comment",
                },
            ],
        };
    },
};
</script>

<style>
</style>