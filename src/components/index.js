import Vue from "vue";
const NAME = "s26Components";

const s26Components = /*#__PURE__*/ {
  NAME,
};

import $ from "jquery";
window.$ = $;

import s26 from "../plugins/functions";
window.s26 = s26;

//AXIOS
import axios from "axios";
import VueAxios from "vue-axios";
Vue.use(VueAxios, axios);
axios.defaults.baseURL = BASE_URL;

//ALERTIFY
import VueAlertify from "vue-alertify";

Vue.use(VueAlertify);
import "./modals";
import "./loaders";
import "./forms";
import "./read_only";
import "./inputs";
import "./selects";
import "./sidebars";
import "./tables";
import "./dropdowns";
import "./tarjets";
import "./uploads";

import tests from "./tests.vue";
Vue.component("s26-test", tests);

export default s26Components;
