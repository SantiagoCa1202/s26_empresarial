import Vue from "vue";
const NAME = "s26Forms";

const s26Forms = /*#__PURE__*/ {
  NAME,
};

import formRole from "./formRole.vue";
Vue.component("s26-form-role", formRole);

import formRolePermits from "./formRolePermits.vue";
Vue.component("s26-roles-permits", formRolePermits);

import formUser from "./formUser.vue";
Vue.component("s26-form-user", formUser);

import formNotes from "./formNotes.vue";
Vue.component("s26-form-notes", formNotes);

import formNotifications from "./formNotifications.vue";
Vue.component("s26-form-notifications", formNotifications);

import formCustomer from "./formCustomer.vue";
Vue.component("s26-form-customer", formCustomer);

import formCategory from "./formCategory.vue";
Vue.component("s26-form-category", formCategory);

import formProvider from "./formProvider.vue";
Vue.component("s26-form-provider", formProvider);

import formPhoto from "./formPhoto.vue";
Vue.component("s26-form-photo", formPhoto);

import formDelete from "./formDelete.vue";
Vue.component("s26-delete", formDelete);

import formSecurityCode from "./formSecurityCode.vue";
Vue.component("s26-security-code", formSecurityCode);

export default s26Forms;
