import Vue from "vue";

// axios
import axios from "axios";

const axiosIns = axios.create({
  // You can add your headers here
  
  // ================================
  // baseURL: "http://backend.farshidsohrabiani.ir/api/v1",
  baseURL: "https://backend.niaar.io/api/v1",

  // baseURL: process.env.VUE_APP_API_ENDPOINT,
});
Vue.prototype.$http = axiosIns;

export default axiosIns;
