import Vue from "vue";
const NAME = "s26Views";

const s26Views = /*#__PURE__*/ {
  NAME,
};

import newSaleDefault from "./newSaleDefault.vue";
Vue.component("s26-newsale-default", newSaleDefault);

import wallet from "./wallet.vue";
Vue.component("s26-wallet", wallet);

import warnings from "./warnings.vue";
Vue.component("s26-warnings", warnings);

export default s26Views;
