import Vue from "vue";

import "/Assets/css/style_view/header.css";
let element = !!document.getElementById("s26-header");
if (element) {
  new Vue({
    el: "#s26-header",
    data: {
      date: "",
      active: false,
      options_user: false,
      dark_mode: false,
      page_user: "dashboard",
      showMenu: {
        left: "-250px",
      },
      showBg: {
        width: "0%",
        background: "rgba(0,0,0,.0)",
      },
      activeSubMenu: 0,
      route: "dashboard",
      subHome: ["sales", "credits", "returns", "calculator", "close_box"],
      subProducts: [
        "products",
        "productsEntries",
        "products_outlet",
        "products_returned",
        "products_damaged",
        "productsSeries",
        "stocktaking",
        "stock_information",
        "suppliers_orders",
        "customer_orders",
        "categories",
      ],
      subTransactions: [
        "expenses",
        "temporary_expenses",
        "transfers",
        "deposits",
        "external_income",
      ],
      subDocuments: ["buys", "creditNotes", "withholdings"],
      subWallet: ["wallet", "estadisticas", "bankAccounts"],
      subAccounts: [
        "accounts_to_pay",
        "accounts_to_receivable",
        "accounts_info",
        "checkBooks",
        "vouchers",
      ],
      subBusiness: [
        "providers",
        "debts",
        "photos",
        "files",
        "documents",
        "establishments",
        "general_config",
        "catalogue",
        "goal_savings",
      ],
      subUsers: ["users", "roles", "permits"],
    },
    created() {
      let date = new Date();
      let options = {
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      this.date = date.toLocaleDateString("es-ES", options);

      let route = window.location.pathname.split("/");
      this.route = route[2];

      if (this.subHome.includes(this.route)) {
        this.activeSubMenu = 1;
      } else if (this.subProducts.includes(this.route)) {
        this.activeSubMenu = 2;
      } else if (this.subTransactions.includes(this.route)) {
        this.activeSubMenu = 3;
      } else if (this.subDocuments.includes(this.route)) {
        this.activeSubMenu = 4;
      } else if (this.subWallet.includes(this.route)) {
        this.activeSubMenu = 5;
      } else if (this.subAccounts.includes(this.route)) {
        this.activeSubMenu = 6;
      } else if (this.subBusiness.includes(this.route)) {
        this.activeSubMenu = 7;
      } else if (this.subUsers.includes(this.route)) {
        this.activeSubMenu = 8;
      }
      setTimeout(() => {
        let dark_mode = $s26.readCookie("dark-mode");
        if (dark_mode == 1) {
          $("body").addClass("s26-dark-theme");
          this.dark_mode = true;
        } else {
          $("body").removeClass("s26-dark-theme");
          this.dark_mode = false;
        }
        $("html").on("click", (e) => {
          this.hideOpsUser();
        });
        $(".dropdown-options-user").click(function(e) {
          e.stopPropagation();
        });
      }, 100);
    },
    methods: {
      menu: function(active) {
        this.active = active;

        if (this.active == true) {
          (this.showMenu = {
            left: "0",
          }),
            (this.showBg = {
              width: "100%",
              background: "rgba(0,0,0,.3)",
            });
        } else {
          (this.showMenu = {
            left: "-250px",
          }),
            (this.showBg = {
              width: "0%",
              background: "rgba(0,0,0,.0)",
            });
          this.activeSubMenu = 0;
          this.$emit("hideMenu", false);
        }
      },
      hideMenu(val) {
        this.active = val;
      },
      focusSubMenu: function(route) {
        if (route == this.route) {
          return "btn-focus";
        }
      },
      showSubMenu: function(sub) {
        if (this.activeSubMenu == sub) {
          this.activeSubMenu = 0;
        } else {
          this.activeSubMenu = sub;
        }
      },
      location() {
        this.menu();
      },
      hideOpsUser() {
        this.options_user = false;
      },
      on_dark_mode() {
        this.axios
          .get("/users/dark_mode/")
          .then((res) => {
            let dark_mode = $s26.readCookie("dark-mode");
            if (dark_mode == 1) {
              $("body").addClass("s26-dark-theme");
              this.dark_mode = true;
            } else {
              $("body").removeClass("s26-dark-theme");
              this.dark_mode = false;
            }
          })
          .catch((err) => {
            console.log(err);
          });
      },
    },
  });
}
