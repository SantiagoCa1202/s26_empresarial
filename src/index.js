import Vue from "vue";

//JQUERY
import $ from "jquery";
window.$ = $;

import s26 from "./plugins/functions";
window.s26 = s26;

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
import s26Components from "./components";
Vue.component(s26Components);

import sidebarTables from "./components/sidebars/sidebarTables.vue";
Vue.component("s26-sidebar", sidebarTables);
// Views
import s26Views from "./views";
Vue.component(s26Views);
