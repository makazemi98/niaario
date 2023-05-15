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
                        <h6>Manage Shops</h6>
                        <b-breadcrumb class="breadcrumb-slash p-0">
                            <b-breadcrumb-item href="/">
                                Home
                            </b-breadcrumb-item>
                            <b-breadcrumb-item active>
                                Shops
                            </b-breadcrumb-item>
                        </b-breadcrumb>
                    </div>
                </div>
            </b-col>
        </b-row>
        <!-- collapse button -->
        <b-card
            no-body
            class="
                collapsible-form
                px-2
                py-1
                d-flex
                flex-row
                justify-content-between
                align-items-center
            "
            @click="openShopsFilterForm"
            v-b-toggle.shops-filter
        >
            <div>
                <feather-icon icon="SearchIcon" style="transform: scale(1.2)" />
                <span class="ml-1 fs-sm">Search In Shops ...</span>
            </div>
            <b-button variant="flat-primary" class="btn-icon" size="sm">
                <feather-icon
                    :icon="
                        shopsFilterFormCollapsed
                            ? 'ChevronUpIcon'
                            : 'ChevronDownIcon'
                    "
                    style="transform: scale(1.4)"
                />
            </b-button>
        </b-card>
        <!-- collapse content (shops filter form) -->
        <b-collapse id="shops-filter" class="mt-1">
            <b-card no-body>
                <b-card-body>
                    <b-row class="mb-1">
                        <b-col cols="12" md="6">
                            <b-form-group label="Keyword" label-for="keywoed">
                                <b-form-input
                                    id="keywoed"
                                    v-model="Keyword"
                                    placeholder="Enter Keyword"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Featured" label-for="featured">
                                <v-select
                                    label="title"
                                    id="featured"
                                    :options="featured"
                                    v-model="selectedFeatured"
                                    placeholder="Select An Option"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Status" label-for="status">
                                <v-select
                                    label="title"
                                    id="status"
                                    :options="statuses"
                                    v-model="selectedStatus"
                                    placeholder="Select An Option"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Shop Status By Seller"
                                label-for="shop-status-by-filter"
                            >
                                <v-select
                                    label="title"
                                    id="shop-status-by-filter"
                                    :options="ShopStatusByFilter"
                                    placeholder="Select An Option"
                                    v-model="selectedShopStatusByFilter"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group
                                label="Date From"
                                label-for="date-from"
                            >
                                <b-form-datepicker
                                    id="date-from"
                                    v-model="dateFrom"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6">
                            <b-form-group label="Date To" label-for="date-to">
                                <b-form-datepicker
                                    id="date-to"
                                    v-model="dateTo"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button size="sm" variant="primary" class="mr-1">
                        Filter
                    </b-button>
                    <b-button size="sm" variant="flat-danger"> Reset </b-button>
                </b-card-body>
            </b-card>
        </b-collapse>
        <!-- users table -->
        <b-row class="mt-2">
            <b-col cols="12">
                <div v-if="isLoaded">
                    <b-card v-if="shops.length > 0" no-body>
                        <div
                            class="
                                p-2
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <h4 class="mb-0">Shops List</h4>
                            <!-- <b-button
                                :disabled="!checkHasAnyShopSelected"
                                variant="gradient-danger"
                                size="sm"
                            >
                                <feather-icon icon="XIcon" class="mr-50" />
                                <span class="align-middle">
                                    Delete Selected Users
                                </span>
                            </b-button> -->
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
                            :items="shops"
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
                                    v-model="selectedShops"
                                    :value="data.item.id"
                                />
                            </template>
                            <template #cell(id)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(user)="data">
                                <div>
                                    <strong>Name: </strong>
                                    {{ data.item.name }}
                                </div>
                                <div>
                                    <strong>Email: </strong>
                                    {{ data.item.email }}
                                </div>
                            </template>
                            <template #cell(shop)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(type)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(reg_date)="data">
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
                            <template #cell(verified)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(action)="data">
                                <b-button
                                    variant="gradient-warning"
                                    class="btn-icon"
                                    v-b-tooltip.hover.top="'Edit'"
                                    @click="editModal = true"
                                >
                                    <feather-icon icon="Edit3Icon" />
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
                        <h4 class="text-secondary mb-0">No records to show ..</h4>
                    </b-card>
                </div>
                <div v-else class="text-center my-3">
                    <b-spinner label="Loading..." />
                    <span class="d-block mt-1"> Loading Content ... </span>
                </div>
            </b-col>
        </b-row>

        <!-- edit modal -->
        <b-modal size="xl" title="Shop Setup" v-model="editModal" hide-footer>
            <b-tabs>
                <b-tab title="General">
                    <b-form @submit.prevent="editModalGeneralSubmitHandler">
                        <b-row>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Shop Identifier"
                                    label-for="shop-identifier"
                                >
                                    <b-form-input
                                        id="shop-identifier"
                                        v-model="shopIdentifier"
                                        placeholder="Enter Identifier"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Shop SEO Friendly URL"
                                    label-for="shop-seo-friendly-url"
                                    description="https://demo.yo-kart.com/jasons-store"
                                >
                                    <b-form-input
                                        id="shop-seo-friendly-url"
                                        placeholder="Enter URL"
                                        v-model="shopSeoFriendlyUrl"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group label="Phone" label-for="phone">
                                    <b-input-group>
                                        <b-input-group-prepend
                                            class="flags-select"
                                        >
                                            <v-select
                                                v-model="selectedLocal"
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
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Country"
                                    label-for="country"
                                >
                                    <v-select
                                        id="country"
                                        label="title"
                                        :options="countries"
                                        v-model="selectedCountry"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group label="State" label-for="state">
                                    <v-select
                                        id="state"
                                        label="title"
                                        :options="states"
                                        v-model="selectedState"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Postal Code"
                                    label-for="postal-code"
                                >
                                    <b-form-input
                                        id="postal-code"
                                        v-model="postalCode"
                                        placeholder="Enter Postal Code"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group label="Status" label-for="status">
                                    <v-select
                                        id="status"
                                        label="title"
                                        :options="editShopStatuses"
                                        v-model="editShopSelectedStatus"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Minimum Wallet Balance"
                                    label-for="minimum-wallet-balance"
                                    description="Seller Needs To Maintain To Accept Cod Orders. Default is -1"
                                >
                                    <b-form-input
                                        id="minimum-wallet-balance"
                                        placeholder="Enter Minimum Wallet Balance"
                                        v-model="minimumWalletBalance"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-checkbox
                                    v-model="isFeatureSelected"
                                    :value="true"
                                    class="mt-2"
                                >
                                    Feature
                                </b-form-checkbox>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Fullfillment Method"
                                    label-for="fullfillment-method"
                                >
                                    <v-select
                                        id="fullfillment-method"
                                        label="title"
                                        :options="FullfillmentMethods"
                                        v-model="selectedFullfillmentMethod"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Order Return Age (Day)"
                                    label-for="order-return-age"
                                >
                                    <b-form-input
                                        id="order-return-age"
                                        v-model="OrderReturnAge"
                                        placeholder="Enter Days"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Order Cancellation Age (Day)"
                                    label-for="order-cancellation-age"
                                >
                                    <b-form-input
                                        id="order-cancellation-age"
                                        v-model="OrderCancellationAge"
                                        placeholder="Enter Days"
                                    />
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-button variant="primary" class="mt-2">
                            Save Changes
                        </b-button>
                    </b-form>
                </b-tab>
                <b-tab title="Language Data">
                    <b-form @submit.prevent="editModalGeneralSubmitHandler">
                        <b-row>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Language"
                                    label-for="language"
                                >
                                    <v-select
                                        id="language"
                                        label="title"
                                        :options="languages"
                                        v-model="selectedLanguage"
                                        placeholder="Select An Option"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Shop Name"
                                    label-for="shop-name"
                                >
                                    <b-form-input
                                        id="shop-name"
                                        v-model="shopName"
                                        placeholder="Enter Name"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Contact Person"
                                    label-for="contact-person"
                                >
                                    <b-form-input
                                        id="contact-person"
                                        placeholder="Enter Name"
                                        v-model="ContactPerson"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Description"
                                    label-for="description"
                                >
                                    <b-form-textarea
                                        id="description"
                                        v-model="languageDescription"
                                        placeholder="Description"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Payment Policy"
                                    label-for="payment-policy"
                                    description="Shop Payment Terms Comments"
                                >
                                    <b-form-textarea
                                        id="payment-policy"
                                        v-model="paymentPolicy"
                                        placeholder="Payment Policy"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Delivery Policy"
                                    label-for="delivery-policy"
                                    description="Shop Delivery Policy Comments"
                                >
                                    <b-form-textarea
                                        id="delivery-policy"
                                        v-model="deliveryPolicy"
                                        placeholder="Delivery Policy"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Refund Policy"
                                    label-for="delivery-policy"
                                    description="Shop Refund Policy Comments"
                                >
                                    <b-form-textarea
                                        id="refund-policy"
                                        v-model="refundPolicy"
                                        placeholder="Refund Policy"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Additional Information"
                                    label-for="additional-information"
                                    description="Shop Additional Info Comments"
                                >
                                    <b-form-textarea
                                        id="additional-information"
                                        v-model="additionalInformation"
                                        placeholder="Additional Information"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-group
                                    label="Seller Information"
                                    label-for="seller-information"
                                    description="Shop Seller Info Comments"
                                >
                                    <b-form-textarea
                                        id="seller-information"
                                        v-model="sellerInformation"
                                        placeholder="Seller Information"
                                        rows="3"
                                    />
                                </b-form-group>
                            </b-col>
                            <b-col cols="12" md="4">
                                <b-form-checkbox
                                    v-model="updateOtherLanguageData"
                                    :value="true"
                                    class="mt-1"
                                >
                                    Update Other Language Data
                                </b-form-checkbox>
                            </b-col>
                        </b-row>
                        <b-button variant="primary" class="mt-2">
                            Save Changes
                        </b-button>
                    </b-form>
                </b-tab>
                <b-tab title="Media">
                    <div>
                        <b-row>
                            <b-col cols="12" md="6">
                                <b-card title="Logo">
                                    <b-form-group
                                        label="Language"
                                        label-for="logo-language"
                                    >
                                        <v-select
                                            label="title"
                                            id="logo-language"
                                            :options="logoLanguages"
                                            v-model="selectedLogoLanguage"
                                            placeholder="Select An Option"
                                        />
                                    </b-form-group>
                                    <b-form-group
                                        label="Ratio"
                                        label-for="radio"
                                    >
                                        <b-form-radio-group
                                            v-model="selectedRatio"
                                            :options="ratios"
                                            name="radio"
                                        />
                                    </b-form-group>
                                    <b-form-group
                                        label="Upload"
                                        label-for="logo-upload"
                                    >
                                        <div class="d-flex flex-row">
                                            <b-form-file
                                                id="logo-upload"
                                                placeholder="Choose a file or drop it here..."
                                                drop-placeholder="Drop file here..."
                                                no-drop
                                                @change="logoUploadHandler"
                                                class="w-100"
                                            />
                                            <div
                                                class="logo-uploaded-file ml-1"
                                                v-if="isLogoFileUploaded"
                                            >
                                                <img
                                                    :src="logoFile.url"
                                                    :alt="logoFile.name"
                                                    class="w-100"
                                                />
                                            </div>
                                        </div>
                                    </b-form-group>
                                </b-card>
                            </b-col>

                            <b-col cols="12" md="6">
                                <b-card title="Banner">
                                    <b-form-group
                                        label="Language"
                                        label-for="banner-language"
                                    >
                                        <v-select
                                            label="title"
                                            id="banner-language"
                                            :options="bannerLanguages"
                                            v-model="selectedBannerLanguage"
                                            placeholder="Select An Option"
                                        />
                                    </b-form-group>
                                    <b-form-group
                                        label="Display For"
                                        label-for="display-for"
                                    >
                                        <v-select
                                            label="title"
                                            id="display-for"
                                            :options="bannerDisplayFor"
                                            v-model="selectedBannerDisplayFor"
                                            placeholder="Select An Option"
                                        />
                                    </b-form-group>
                                    <b-form-group
                                        label="Upload"
                                        label-for="banner-upload"
                                    >
                                        <div class="d-flex flex-row">
                                            <b-form-file
                                                id="banner-upload"
                                                placeholder="Choose a file or drop it here..."
                                                drop-placeholder="Drop file here..."
                                                no-drop
                                                @change="bannerUploadHandler"
                                                class="w-100"
                                            />
                                            <div
                                                class="logo-uploaded-file ml-1"
                                                v-if="isBannerFileUploaded"
                                            >
                                                <img
                                                    :src="bannerFile.url"
                                                    :alt="bannerFile.name"
                                                    class="w-100"
                                                />
                                            </div>
                                        </div>
                                    </b-form-group>
                                </b-card>
                            </b-col>
                        </b-row>
                    </div>
                </b-tab>
                <b-tab title="Collections" active>
                    <b-card v-if="shopsCollections.length > 0" no-body>
                        <b-table
                            responsive
                            :striped="striped"
                            :bordered="bordered"
                            :outlined="outlined"
                            :fields="fields"
                            :items="shops"
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
                                    v-model="selectedShops"
                                    :value="data.item.id"
                                />
                            </template>
                            <template #cell(id)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(user)="data">
                                <div>
                                    <strong>Name: </strong>
                                    {{ data.item.name }}
                                </div>
                                <div>
                                    <strong>Email: </strong>
                                    {{ data.item.email }}
                                </div>
                            </template>
                            <template #cell(shop)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(type)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(reg_date)="data">
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
                            <template #cell(verified)="data">
                                {{ data.value }}
                            </template>
                            <template #cell(action)="data">
                                <b-button
                                    variant="gradient-warning"
                                    class="btn-icon"
                                    v-b-tooltip.hover.top="'Edit'"
                                    @click="editModal = true"
                                >
                                    <feather-icon icon="Edit3Icon" />
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
                    <b-card v-else>
                        <div
                            class="
                                d-flex
                                flex-row
                                justify-content-between
                                align-items-center
                            "
                        >
                            <span>No Collection Found</span>
                            <b-button variant="gradient-success" size="sm">
                                <span
                                    class="align-middle"
                                    @click="openAddCollectionFormHandler"
                                >
                                    Add Collection
                                </span>
                            </b-button>
                        </div>
                    </b-card>
                </b-tab>
            </b-tabs>
        </b-modal>

        <!-- add collection modal -->
        <b-modal
            v-model="collectionFormStatus"
            title="Add Collection"
            ok-only
            ok-title="Save"
            size="lg"
        >
            <!-- <form-wizard
                color="#3390ec"
                :title="null"
                :subtitle="null"
                finish-button-text="Submit"
                back-button-text="Previous"
                class="steps-transparent mb-3"
                @on-complete="formSubmitted"
            >
                <tab-content
                    title="Account Details"
                    icon="feather icon-file-text"
                >
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Account Details</h5>
                            <small class="text-muted">
                                Enter Your Account Details.
                            </small>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Username"
                                label-for="i-username"
                            >
                                <b-form-input
                                    id="i-username"
                                    placeholder="johndoe"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Email" label-for="i-email">
                                <b-form-input
                                    id="i-email"
                                    type="email"
                                    placeholder="john.doe@email.com"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Password"
                                label-for="i-password"
                            >
                                <b-form-input
                                    id="i-password"
                                    type="password"
                                    placeholder="Password"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Confirm Password"
                                label-for="i-c-password"
                            >
                                <b-form-input
                                    id="i-c-password"
                                    type="password"
                                    placeholder="Re-type Password"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </tab-content>

                <tab-content title="Personal Info" icon="feather icon-user">
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Personal Info</h5>
                            <small class="text-muted"
                                >Enter Your Personal Info.</small
                            >
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label-for="i-first-name"
                                label="First Name"
                            >
                                <b-form-input
                                    id="i-first-name"
                                    placeholder="John"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Last Name"
                                label-for="i-last-name"
                            >
                                <b-form-input
                                    id="i-last-name"
                                    placeholder="Doe"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Country" label-for="i-country">
                                <v-select
                                    id="i-country"
                                    v-model="selectedContry"
                                    :dir="
                                        $store.state.appConfig.isRTL
                                            ? 'rtl'
                                            : 'ltr'
                                    "
                                    :options="countryName"
                                    :selectable="
                                        (option) =>
                                            !option.value.includes(
                                                'select_value'
                                            )
                                    "
                                    label="text"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Language"
                                label-for="i-language"
                            >
                                <v-select
                                    id="i-language"
                                    v-model="selectedLanguage"
                                    :dir="
                                        $store.state.appConfig.isRTL
                                            ? 'rtl'
                                            : 'ltr'
                                    "
                                    :options="languageName"
                                    :selectable="
                                        (option) =>
                                            !option.value.includes(
                                                'select_value'
                                            )
                                    "
                                    label="text"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </tab-content>

                <tab-content title="Address" icon="feather icon-map-pin">
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Address</h5>
                            <small class="text-muted"
                                >Enter Your Address.</small
                            >
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Address" label-for="i-address">
                                <b-form-input
                                    id="i-address"
                                    placeholder="98 Borough bridge Road, Birmingham"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Landmark"
                                label-for="i-landmark"
                            >
                                <b-form-input
                                    id="i-landmark"
                                    placeholder="Borough bridge"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Pincode" label-for="i-pincode">
                                <b-form-input
                                    id="i-pincode"
                                    placeholder="658921"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group label-for="i-city" label="City">
                                <b-form-input
                                    id="i-city"
                                    placeholder="Birmingham"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </tab-content>

                <tab-content title="Social Links" icon="feather icon-link">
                    <b-row>
                        <b-col cols="12" class="mb-2">
                            <h5 class="mb-0">Social Links</h5>
                            <small class="text-muted"
                                >Enter Your Social Links</small
                            >
                        </b-col>
                        <b-col md="6">
                            <b-form-group label="Twitter" label-for="i-twitter">
                                <b-form-input
                                    id="i-twitter"
                                    placeholder="https://twitter.com/abc"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Facebook"
                                label-for="i-facebook"
                            >
                                <b-form-input
                                    id="i-facebook"
                                    placeholder="https://facebook.com/abc"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="Google+"
                                label-for="i-google-plus"
                            >
                                <b-form-input
                                    id="i-google-plus"
                                    placeholder="https://plus.google.com/abc"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col md="6">
                            <b-form-group
                                label="LinkedIn"
                                label-for="i-linked-in"
                            >
                                <b-form-input
                                    id="i-linked-in"
                                    placeholder="https://linkedin.com/abc"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </tab-content>
            </form-wizard> -->
        </b-modal>
    </section>
</template>

<script>
// * dependencies
import _ from "lodash";
import countries from "@/data/countries";
// * components
import {
    BRow,
    BCol,
    BAvatar,
    BBreadcrumb,
    BBreadcrumbItem,
    BButton,
    BCollapse,
    VBToggle,
    BCard,
    BCardBody,
    BFormGroup,
    BFormInput,
    BFormDatepicker,
    BTable,
    BSpinner,
    BFormCheckbox,
    BDropdown,
    BDropdownItem,
    BPagination,
    VBTooltip,
    BModal,
    BTabs,
    BTab,
    BCardText,
    BForm,
    BInputGroup,
    BInputGroupPrepend,
    BFormTextarea,
    BFormRadioGroup,
    BFormFile,
} from "bootstrap-vue";
import VSelect from "vue-select";

export default {
    components: {
        BRow,
        BCol,
        BAvatar,
        BBreadcrumb,
        BBreadcrumbItem,
        BButton,
        BCollapse,
        BCard,
        BCardBody,
        BFormGroup,
        BFormInput,
        VSelect,
        BFormDatepicker,
        BTable,
        BSpinner,
        BFormCheckbox,
        BDropdown,
        BDropdownItem,
        BPagination,
        BModal,
        BTabs,
        BTab,
        BCardText,
        BForm,
        BInputGroup,
        BInputGroupPrepend,
        BFormTextarea,
        BFormRadioGroup,
        BFormFile,
    },
    directives: {
        "b-toggle": VBToggle,
        "b-tooltip": VBTooltip,
    },
    computed: {
        featured() {
            return [
                { title: "Does Not Matter" },
                { title: "Yes" },
                { title: "No" },
            ];
        },
        statuses() {
            return [
                { title: "Does Not Matter" },
                { title: "Active" },
                { title: "Inactive" },
            ];
        },
        ShopStatusByFilter() {
            return [
                { title: "Does Not Matter" },
                { title: "On" },
                { title: "Of" },
            ];
        },
        checkHasAnyShopSelected() {
            return this.selectedShops.length > 0;
        },
    },
    methods: {
        openShopsFilterForm() {
            this.shopsFilterFormCollapsed = this.shopsFilterFormCollapsed
                ? false
                : true;
        },
        pageChangeHandler() {
            console.log("change page");
        },
        selectAllHandler() {
            if (this.isSelectedAll) {
                this.selectedShops = _.map(this.shops, "id");
            } else {
                this.selectedShops = [];
            }
        },
        logoUploadHandler(e) {
            const file = e.target.files[0];
            if (file) {
                this.logoFile = {
                    name: file.name,
                    url: URL.createObjectURL(file),
                };
                this.isLogoFileUploaded = true;
            }
        },
        bannerUploadHandler(e) {
            const file = e.target.files[0];
            if (file) {
                this.bannerFile = {
                    name: file.name,
                    url: URL.createObjectURL(file),
                };
                this.isBannerFileUploaded = true;
            }
        },
        openAddCollectionFormHandler() {
            this.collectionFormStatus = true;
        },
    },
    data() {
        return {
            // form properties
            shopsFilterFormCollapsed: false,
            Keyword: undefined,
            selectedFeatured: undefined,
            selectedStatus: undefined,
            selectedShopStatusByFilter: undefined,
            dateFrom: undefined,
            dateTo: undefined,
            // loading process
            isLoaded: false,
            // table properties
            striped: true,
            bordered: true,
            outlined: true,
            fields: [
                { key: "check" },
                { key: "id", label: "#" },
                { key: "owner", label: "Owner" },
                { key: "name", label: "Name" },
                { key: "products", label: "Products" },
                { key: "reports", label: "Reports" },
                { key: "reviews", label: "Reviews" },
                { key: "featured", label: "Featured" },
                { key: "status", label: "Status" },
                { key: "create_on", label: "Create On" },
                { key: "status_by_seller", label: "Status By Seller" },
                { key: "action", label: "Actions" },
            ],
            shops: [
                {
                    id: 1,
                    owner: "Jason Smith",
                    name: "Jason's Store",
                    products: 11,
                    reports: 0,
                    reviews: 0,
                    featured: "Yes",
                    status: true,
                    create_on: "25/07/2017",
                    status_by_seller: "On",
                },
                {
                    id: 2,
                    owner: "Jason Smith",
                    name: "Jason's Store",
                    products: 11,
                    reports: 0,
                    reviews: 0,
                    featured: "Yes",
                    status: true,
                    create_on: "25/07/2017",
                    status_by_seller: "On",
                },
                {
                    id: 3,
                    owner: "Jason Smith",
                    name: "Jason's Store",
                    products: 11,
                    reports: 0,
                    reviews: 0,
                    featured: "Yes",
                    status: true,
                    create_on: "25/07/2017",
                    status_by_seller: "On",
                },
            ],
            isSelectedAll: false,
            selectedShops: [1],
            // table pagination
            page: 1,
            perPage: 10,
            count: 34,
            // modal
            editModal: false,
            // general tab
            shopIdentifier: undefined,
            shopSeoFriendlyUrl: undefined,
            locals: countries,
            selectedLocal: undefined,
            selectedCountry: undefined,
            countries: [],
            selectedState: undefined,
            states: [],
            postalCode: undefined,
            editShopSelectedStatus: undefined,
            editShopStatuses: [],
            minimumWalletBalance: "-1",
            isFeatureSelected: false,
            selectedFullfillmentMethod: undefined,
            FullfillmentMethods: [],
            OrderReturnAge: undefined,
            OrderCancellationAge: undefined,
            // language data tab
            languages: [],
            selectedLanguage: undefined,
            shopName: undefined,
            ContactPerson: undefined,
            languageDescription: undefined,
            paymentPolicy: undefined,
            deliveryPolicy: undefined,
            refundPolicy: undefined,
            additionalInformation: undefined,
            sellerInformation: undefined,
            updateOtherLanguageData: undefined,
            // media tab
            logoLanguages: [],
            selectedLogoLanguage: undefined,
            ratios: [
                {
                    text: "1:1",
                    value: "1:1",
                },
                {
                    text: "16:9",
                    value: "16:9",
                },
            ],
            selectedRatio: undefined,
            isLogoFileUploaded: false,
            logoFile: undefined,
            bannerLanguages: [],
            selectedBannerLanguage: undefined,
            bannerDisplayFor: [],
            selectedBannerDisplayFor: undefined,
            isBannerFileUploaded: false,
            bannerFile: undefined,
            // collection tab
            shopsCollections: [],
            shopsCollectionFields: [
                { key: "check" },
                { key: "id", label: "#" },
                { key: "collection_name", label: "Collection Name" },
                { key: "status", label: "Status" },
                { key: "products", label: "Products" },
                { key: "reports", label: "Reports" },
                { key: "reviews", label: "Reviews" },
                { key: "featured", label: "Featured" },
                { key: "status", label: "Status" },
                { key: "create_on", label: "Create On" },
                { key: "status_by_seller", label: "Status By Seller" },
                { key: "action", label: "Actions" },
            ],
            collectionFormStatus: false,
        };
    },
    mounted() {
        this.isLoaded = true;
    },
};
</script>

<style lang="scss">
@import "@/assets/scss/pages/shops.scss";
</style>