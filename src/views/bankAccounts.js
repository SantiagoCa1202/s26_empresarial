import Vue from "vue";

let element = !!document.getElementById("s26-bankAccounts-view");
if (element) {
  new Vue({
    el: "#s26-bankAccounts-view",
    data: function() {
      return {
        rows: 0,
        items: [],
        status: "",
        perPage: 25,
        idRow: null,
        activeSidebar: true,
        action: "",
        url_export: "",
      };
    },
    created() {
      if ($s26.readCookie("id")) {
        this.setIdRow($s26.readCookie("id"), "watch");
      }
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {
          status: this.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/bankAccounts/getbankAccounts/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = $s26.url_get(
          "/bankAccounts/exportbankAccounts/",
          params
        );
      },
      onReset() {
        for (let fil in this.filter) {
          this.filter[fil] = "";
        }
        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "bankAccounts");
        }
      },
    },
  });
}
