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

import formFile from "./formFile.vue";
Vue.component("s26-form-file", formFile);

import formBuys from "./formBuys.vue";
Vue.component("s26-form-buy", formBuys);

import formCreditNotes from "./formCreditNotes.vue";
Vue.component("s26-form-credit-note", formCreditNotes);

import formWithholdings from "./formWithholdings.vue";
Vue.component("s26-form-withholding", formWithholdings);

import formBankAccount from "./formBankAccount.vue";
Vue.component("s26-form-bank-account", formBankAccount);

import formCheckBook from "./formCheckBook.vue";
Vue.component("s26-form-check", formCheckBook);

import formProduct from "./formProduct.vue";
Vue.component("s26-form-product", formProduct);

import formAmountProduct from "./formAmountProduct.vue";
Vue.component("s26-form-amount-product", formAmountProduct);

import formValCode from "./formValCode.vue";
Vue.component("s26-form-val-code", formValCode);

import formDelete from "./formDelete.vue";
Vue.component("s26-delete", formDelete);

import formProductEntry from "./formProductEntry.vue";
Vue.component("s26-form-product-entry", formProductEntry);

import formSecurityCode from "./formSecurityCode.vue";
Vue.component("s26-security-code", formSecurityCode);

import formDisableProduct from "./formDisableProduct.vue";
Vue.component("s26-disable-product", formDisableProduct);
export default s26Forms;
