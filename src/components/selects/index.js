import Vue from "vue";
const NAME = "s26Selects";

const s26Selects = /*#__PURE__*/ {
  NAME,
};

import selectStatus from "./selectStatus.vue";
Vue.component("s26-select-status", selectStatus);

import selectRole from "./selectRole.vue";
Vue.component("s26-select-role", selectRole);

import selectEstablishment from "./selectEstablishment.vue";
Vue.component("s26-select-establishment", selectEstablishment);

import selectUser from "./selectUser.vue";
Vue.component("s26-select-user", selectUser);

import selectIcon from "./selectIcon.vue";
Vue.component("s26-select-icon", selectIcon);

import selectBankEntity from "./selectBankEntity.vue";
Vue.component("s26-select-bank", selectBankEntity);

import selectBankAccount from "./selectBankAccount.vue";
Vue.component("s26-select-bank-account", selectBankAccount);

import selectGender from "./selectGender.vue";
Vue.component("s26-select-gender", selectGender);

import selectCategory from "./selectCategory.vue";
Vue.component("s26-select-category", selectCategory);

import selectProvider from "./selectProvider.vue";
Vue.component("s26-select-provider", selectProvider);

import selectTypeDocument from "./selectTypeDocument.vue";
Vue.component("s26-select-type-document", selectTypeDocument);

import selectPaymentMethod from "./selectPaymentMethod.vue";
Vue.component("s26-select-payment-method", selectPaymentMethod);

import selectFile from "./selectFile.vue";
Vue.component("s26-select-file", selectFile);

import selectBuys from "./selectBuys.vue";
Vue.component("s26-select-buys", selectBuys);

export default s26Selects;
