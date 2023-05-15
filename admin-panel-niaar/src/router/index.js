import Vue from "vue";
import VueRouter from "vue-router";

// Routes
import { canNavigate } from "@/libs/acl/routeProtection";
import {
  isUserLoggedIn,
  getUserData,
  getHomeRouteForLoggedInUser,
} from "@/auth/utils";
import users from "./routes/users";
import teamMembers from "./routes/team-members";
import suppliers from "./routes/suppliers";
import shops from "./routes/shops";
import inquiries from "./routes/inquiries";
import clients from "./routes/clients";
import accountant from "./routes/accountant";
import shippings from "./routes/shippings";
import notifications from "./routes/notifications";
import apps from "./routes/apps";
import dashboard from "./routes/dashboard";
import uiElements from "./routes/ui-elements/index";
import pages from "./routes/pages";
import chartsMaps from "./routes/charts-maps";
import formsTable from "./routes/forms-tables";
import others from "./routes/others";
import profile from "./routes/profile";

Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes: [
    { path: "/", redirect: { name: "dashboard" } },
    ...apps,
    ...dashboard,
    ...pages,
    ...chartsMaps,
    ...formsTable,
    ...uiElements,
    ...others,
    ...users,
    ...teamMembers,
    ...suppliers,
    ...shops,
    ...profile,
    ...inquiries,
    ...clients,
    ...notifications,
    ...accountant,
    ...shippings,
    {
      path: "*",
      redirect: "error-404",
    },
  ],
});
// router.beforeEach(async (to, from) => {
//     console.log(to);
//   const isLoggedIn = await isUserLoggedIn();
//   if (
//     // make sure the user is authenticated
//     !isLoggedIn ||
//     // ❗️ Avoid an infinite redirect
//     to.name !== "auth-login"
//   ) {
//       console.log("bayad redirect mishod");
//     // redirect the user to the login page
//     return { name: "auth-login" };
//   }
// });
// router.beforeEach((to, next) => {
//   console.log(to);
//   console.log(next);
//   //   console.log(to);
//     const isLoggedIn = isUserLoggedIn();
//     console.log(!!isLoggedIn);
//     if(isLoggedIn) {
//         console.log("log ine");
//     }
//     else {
//         console.log("log in nist");
//         console.log(this.$router);
//         this.$router.push("/login");
//         // return next({ name: "auth-login" });
//     }
//   // if()
//   // if (!canNavigate(to)) {
//   // Redirect to login if not logged in
//   //   if (!!isLoggedIn && to.name === "auth-login")
//   //     return next({ name: "auth-login" });
//   //   else return next();
//   // If logged in => not authorized
//   //   return next({ name: "misc-not-authorized" });
//   // }

//   // Redirect if logged in
//   //   if (to.meta.redirectIfLoggedIn && isLoggedIn) {
//   //       const userData = getUserData();
//   //       next(getHomeRouteForLoggedInUser(userData ? userData.role : null));
//   //   }

//   // else return next();
// });

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById("loading-bg");
  //   console.log(appLoading);
  //   setTimeout(() => {
  //     appLoading.style.display = "none";
  //   }, 500);
  if (appLoading) {
    appLoading.style.display = "none";
  }
});

export default router;
