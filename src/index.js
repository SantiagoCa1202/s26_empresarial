import Vue from "vue";

//JQUERY
import $ from "jquery";
window.$ = $;

import s26 from "./plugins/functions";
window.$s26 = s26;
Vue.prototype.$s26 = s26;

//AXIOS
import axios from "axios";
import VueAxios from "vue-axios";
Vue.use(VueAxios, axios);
axios.defaults.baseURL = BASE_URL;

// Bootstrap
import "/Assets/css/normalize.css";
import "/Assets/css/bootstrap.min.css";
import "./plugins/fontawesome";
import "/Assets/css/s26.css";
import "/Assets/css/themes.css";

//ALERTIFY
import VueAlertify from "vue-alertify";
Vue.use(VueAlertify);

// Components
require("./components");

// Views
require("./views");
