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

import selectGender from "./selectGender.vue";
Vue.component("s26-select-gender", selectGender);

export default s26Selects;
