import Vue from "vue";
const NAME = "s26Tarjets";

const s26Tarjets = /*#__PURE__*/ {
  NAME,
};

import cardDefault from "./cardDefault.vue";
Vue.component("s26-card", cardDefault);

import tarjetInfo from "./tarjetInfo.vue";
Vue.component("s26-tarjet-info", tarjetInfo);

import cardUtilities from "./cardUtilities.vue";
Vue.component("s26-card-utilities", cardUtilities);

export default s26Tarjets;
