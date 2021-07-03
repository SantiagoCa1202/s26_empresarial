import Vue from "vue";
const NAME = "s26ReadOnly";

const s26ReadOnly = /*#__PURE__*/ {
  NAME,
};

import readUser from "./readUser.vue";
Vue.component("s26-read-user", readUser);

import readRol from "./readRol.vue";
Vue.component("s26-read-role", readRol);

import readPayroll from "./readPayroll.vue";
Vue.component("s26-info-payroll", readPayroll);

import readCustomer from "./readCustomer.vue";
Vue.component("s26-read-customer", readCustomer);

import readCategory from "./readCategory.vue";
Vue.component("s26-read-category", readCategory);

import readProvider from "./readProvider.vue";
Vue.component("s26-read-provider", readProvider);

import readPhoto from "./readPhoto.vue";
Vue.component("s26-read-photo", readPhoto);

import readEstablishment from "./readEstablishment.vue";
Vue.component("s26-read-establishment", readEstablishment);

export default s26ReadOnly;
