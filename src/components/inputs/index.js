import Vue from "vue";
const NAME = "s26Inputs";

const s26Inputs = /*#__PURE__*/ {
  NAME,
};

import defaultInput from "./defaultInput.vue";
Vue.component("s26-form-input", defaultInput);

import inputSearch from "./inputSearch.vue";
Vue.component("s26-input-search", inputSearch);

import inputTextarea from "./inputTextarea.vue";
Vue.component("s26-textarea", inputTextarea);

import inputReadOnly from "./inputReadOnly.vue";
Vue.component("s26-input-read", inputReadOnly);

import inputMoneyReadOnly from "./inputMoneyReadOnly.vue";
Vue.component("s26-input-money-read", inputMoneyReadOnly);

import inputTextareaReadOnly from "./inputTextareaReadOnly.vue";
Vue.component("s26-textarea-read", inputTextareaReadOnly);

import datepicker from "./datepicker.vue";
Vue.component("s26-date-picker", datepicker);

import inputPhoto from "./inputPhoto.vue";
Vue.component("s26-input-photo", inputPhoto);

import inputDocument from "./inputDocument.vue";
Vue.component("s26-input-document", inputDocument);

import inputDocumentReadOnly from "./inputDocumentReadOnly.vue";
Vue.component("s26-input-read-document", inputDocumentReadOnly);

import editor from "./editor.vue";
Vue.component("s26-editor", editor);

export default s26Inputs;
