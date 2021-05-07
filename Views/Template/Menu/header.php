<header id="s26-header" class="header-menu">
  <div class="s26-navbar-top">
    <div class="navbar-top-left">
      <button class="btn-icon" v-on:click="menu(true)">
        <i class="fas fa-bars"></i>
      </button>
      <a class="btn-s26-dash"> S26 Empresarial </a>
      <div>|</div>
      <div class="text-capitalize">
        <?= $_SESSION['userData']['establishment']['tradename'] ?>
        -
        <?= str_pad(
          $_SESSION['userData']['establishment']['n_establishment'],
          3,
          "0",
          STR_PAD_LEFT
        )
        ?>
      </div>
    </div>
    <div class="navbar-top-center">
      <a class="btn-icon" href="dashboard">
        <i class="fas fa-home"></i>
      </a>
    </div>
    <div class="navbar-top-right">
      <div class="text-capitalize">
        <?= $_SESSION['userData']['short_name'] ?>
        -
        <?= $_SESSION['userData']['role']['id'] ?>
      </div>
      <div>|</div>
      <div class="text-capitalize"> {{ date }} </div>
      <button class="btn-icon">
        <i class="fas fa-bell"></i>
      </button>
      <a href="<?= BASE_URL ?> /logout" class="btn-icon">
        <i class="fas fa-power-off"></i>
      </a>
    </div>
    <?php require_once('nav.php') ?>
  </div>
</header>

<script>
  let S26Header = new Vue({
    el: "#s26-header",
    data: function() {
      return {
        date: "",
        active: false,
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
        subHome: [
          "sales",
          "credits",
          "returns",
          "external_income",
          "calculator",
          "close_box",
        ],
        subProducts: [
          "products",
          "products_entry",
          "products_outlet",
          "products_returned",
          "products_damaged",
          "stocktaking",
          "stock_information",
          "suppliers_orders",
          "customer_orders",
        ],
        subDocuments: ["purchases_to_providers", "credit_notes", "withholdings"],
        subWallet: ["wallet", "estadisticas", "bank_entity"],
        subAccounts: [
          "accounts_to_pay",
          "accounts_to_receivable",
          "accounts_info",
          "checks",
          "vouchers",
        ],
        subBusiness: [
          "provider",
          "debts",
          "images",
          "info_vouchers",
          "establishments",
          "general_config",
        ],
        subUsers: ["users", "roles", "permits"],
      };
    },
    created() {
      let date = new Date();
      let options = {
        year: "numeric",
        month: "long",
        day: "numeric"
      };
      this.date = date.toLocaleDateString("es-ES", options);

      let route = window.location.pathname.split("/");
      this.route = route[2];

      if (this.subHome.includes(this.route)) {
        this.activeSubMenu = 1;
      } else if (this.subProducts.includes(this.route)) {
        this.activeSubMenu = 2;
      } else if (this.subDocuments.includes(this.route)) {
        this.activeSubMenu = 3;
      } else if (this.subWallet.includes(this.route)) {
        this.activeSubMenu = 4;
      } else if (this.subAccounts.includes(this.route)) {
        this.activeSubMenu = 5;
      } else if (this.subBusiness.includes(this.route)) {
        this.activeSubMenu = 6;
      } else if (this.subUsers.includes(this.route)) {
        this.activeSubMenu = 7;
      }
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
        this.setLoading(true);
        this.menu();
      },
    },
  });
</script>