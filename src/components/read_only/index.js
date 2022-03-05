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

import readBankAccount from "./readBankAccount.vue";
Vue.component("s26-read-bank-account", readBankAccount);

import readCheck from "./readCheck.vue";
Vue.component("s26-read-check", readCheck);

import readBuys from "./readBuys.vue";
Vue.component("s26-read-buy", readBuys);

import readCreditNotes from "./readCreditNotes.vue";
Vue.component("s26-read-credit-note", readCreditNotes);

import readWithholdings from "./readWithholdings.vue";
Vue.component("s26-read-withholding", readWithholdings);

import readSingleProduct from "./readSingleProduct.vue";
Vue.component("s26-read-single-product", readSingleProduct);

import readProduct from "./readProduct.vue";
Vue.component("s26-read-product", readProduct);

import readDocument from "./readDocument.vue";
Vue.component("s26-read-document", readDocument);

import readSale from "./readSale.vue";
Vue.component("s26-read-sale", readSale);

import readSaleCredit from "./readSaleCredit.vue";
Vue.component("s26-read-sale-credit", readSaleCredit);

import readProductDamaged from "./readProductDamaged.vue";
Vue.component("s26-read-product-damaged", readProductDamaged);

import readExpense from "./readExpense.vue";
Vue.component("s26-read-expense", readExpense);

import readTransfer from "./readTransfer.vue";
Vue.component("s26-read-transfer", readTransfer);

export default s26ReadOnly;
