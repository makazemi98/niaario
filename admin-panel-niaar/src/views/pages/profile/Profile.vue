<template>
  <div id="user-profile">
    <!-- profile info  -->
    <section id="profile-info">
      <b-row>
        <b-col lg="6" cols="12">
          <b-card>
            <div v-if="isLoaded">
              <b-media>
                <template #aside>
                  <b-img
                    v-if="form.avatar"
                    :src="form.avatar"
                    width="78"
                    alt="placeholder"
                    rounded
                  ></b-img>

                  <b-img
                    v-else
                    width="78"
                    rounded
                    :src="
                      require('@/assets/images/avatars/placeholder-niaar.png')
                    "
                  />
                </template>
                <h6 class="mb-1 d-inline-block">
                  <b> {{ form.role }}</b>
                </h6>

                <h6 class="mb-1">
                  First name: <b> {{ form.first_name }}</b>
                </h6>

                <h6 class="mb-1">
                  Last name: <b> {{ form.last_name }}</b>
                </h6>

                <h6 v-if="form.abbreviation" class="mb-1">
                  Abbreviation:
                  <small class="d-inline-block"
                    >( {{ form.abbreviation }} )</small
                  >
                </h6>

                <h6 class="mb-1">Email: {{ form.email }}</h6>

                <p
                  v-if="
                    form.role == 'client' &&
                    userData.role !== 'client' &&
                    form.confidential
                  "
                >
                  Confidential Note: {{ form.confidential }}
                </p>

                <p v-if="form.role !== 'client' && form.renewal_date">
                  Renewal Date : <b>{{ form.renewal_date }}</b>
                </p>

                <div v-if="form.role == 'client'">
                  <p v-if="form.contact_person">
                    contact person : <b>{{ form.contact_person }}</b>
                  </p>
                  <p v-if="form.mobile_number">
                    mobile number : <b>{{ form.mobile_number }}</b>
                  </p>
                  <p v-if="form.company_name">
                    company name : <b>{{ form.company_name }}</b>
                  </p>
                  <p v-if="form.company_number">
                    company number : <b>{{ form.company_number }}</b>
                  </p>
                  <p v-if="form.contact_name_2">
                    contact name 2 : <b>{{ form.contact_name_2 }}</b>
                  </p>
                  <p v-if="form.mobile_number_2">
                    mobile number 2 : <b>{{ form.mobile_number_2 }}</b>
                  </p>
                  <p v-if="form.company_abbreviation">
                    company abbreviation :
                    <b>{{ form.company_abbreviation }}</b>
                  </p>
                </div>
              </b-media>
            </div>
            <p class="mb-0" v-else>
              <b-spinner small label="Spinning"></b-spinner> Loading...
            </p>
          </b-card>
        </b-col>
        <!-- about suggested page and twitter feed -->
        <!-- <b-col lg="3" cols="12" order="2" order-lg="1">
          <profile-about
            :profileData="profileData"
            :about-data="profileData.userAbout"
          />
        </b-col> -->
        <!--/ about suggested page and twitter feed -->

        <!-- post -->
        <!-- <b-col lg="6" cols="12" order="1" order-lg="2">
          <profile-post :posts="profileData.post" />
        </b-col> -->
        <!-- post -->
      </b-row>
    </section>
    <!--/ profile info  -->
  </div>
</template>

<script>
import { BRow, BCol, BCard, BMedia, BImg, BSpinner } from "bootstrap-vue";

import ProfileHeader from "./ProfileHeader.vue";
import ProfileAbout from "./ProfileAbout.vue";
import ProfileSuggestedPages from "./ProfileSuggestedPages.vue";
import ProfileTwitterFeed from "./ProfileTwitterFeed.vue";
import ProfilePost from "./ProfilePost.vue";
import ProfileLatestPhotos from "./ProfileLatestPhotos.vue";
import ProfileSuggestion from "./ProfileSuggestion.vue";
import ProfilePolls from "./ProfilePolls.vue";
import profileBottom from "./profileBottom.vue";

/* eslint-disable global-require */
export default {
  components: {
    BRow,
    BCol,
    BCard,
    BMedia,
    BImg,
    BSpinner,

    ProfileHeader,
    ProfileAbout,
    ProfileSuggestedPages,
    ProfileTwitterFeed,
    ProfilePost,
    ProfileLatestPhotos,
    ProfileSuggestion,
    ProfilePolls,
    profileBottom,
  },
  data() {
    return {
      profileData: {},
      form: {},
      isLoaded: false,
      userData: JSON.parse(localStorage.getItem("userData")),
    };
  },
  mounted() {
    if (!localStorage.getItem("accessToken")) {
      this.$router.replace("/login");
    }
    this.getData();
  },

  methods: {
    getData() {
      axios.get(`/admin/users/${this.$route.params.id}`).then((response) => {
        this.isLoaded = true;
        this.form = response.data.data;
      });
    },
  },

  created() {
    this.$http.get("/profile/data").then((res) => {
      this.profileData = res.data;
    });
  },
};
/* eslint-disable global-require */
import axios from "@axios";
</script>

<style lang="scss">
@import "@core/scss/vue/pages/page-profile.scss";
</style>
