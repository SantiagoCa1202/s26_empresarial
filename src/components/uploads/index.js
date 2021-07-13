import Vue from "vue";
const NAME = "s26Uploads";

const s26Uploads = /*#__PURE__*/ {
  NAME,
};

import uploadPhotos from "./uploadPhotos.vue";
Vue.component("s26-upload-photos", uploadPhotos);

import uploadFiles from "./uploadFiles.vue";
Vue.component("s26-upload-files", uploadFiles);

export default s26Uploads;
