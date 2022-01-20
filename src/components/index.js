import Vue from "vue";
const NAME = "s26Components";

const s26Components = /*#__PURE__*/ {
  NAME,
};

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
import "./views";

import tests from "./tests.vue";
Vue.component("s26-test", tests);

export default s26Components;
