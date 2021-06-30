import Vue from "vue";
const NAME = "s26Modals";

const s26Modals = /*#__PURE__*/ {
  NAME,
};

import defaultModal from "./defaultModal.vue";
Vue.component("s26-modal", defaultModal);

import modalMultiple from "./modalMultiple.vue";
Vue.component("s26-modal-multiple", modalMultiple);

export default s26Modals;
