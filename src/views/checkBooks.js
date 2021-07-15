import Vue from "vue";

let element = !!document.getElementById("s26-checkBooks-view");
if (element) {
  new Vue({
    el: "#s26-checkBooks-view",
    data: function() {
      return {
        fields: [
          {
            name: "N° de Cheque",
            class: "length-int",
          },
          {
            name: "Fecha",
            class: "length-date",
          },
          {
            name: "Beneficiario",
            class: "length-description",
          },
          {
            name: "Monto",
            class: "length-action",
          },
          {
            name: "Tipo",
            class: "length-action",
          },
          {
            name: "Estado",
            class: "length-action",
          },
        ],
        filter: {
          id: "",
          bank_account_id: "",
          n_check: "",
          date_issue: "",
          date_payment: "",
          beneficiary: "",
          reason: "",
          type: "",
          payment_status: "",
        },
        rows: 0,
        items: [],
        perPage: 25,
        idRow: null,
        activeSidebar: true,
        action: "",
        url_export: "",
      };
    },
    created() {
      if (s26.readCookie("id")) {
        this.setIdRow(s26.readCookie("id"), "watch");
      }
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {
          id: this.filter.id,
          bank_account_id: this.filter.bank_account_id,
          n_check: this.filter.n_check,
          date_issue: this.filter.date_issue,
          date_payment: this.filter.date_payment,
          beneficiary: this.filter.beneficiary,
          reason: this.filter.reason,
          type: this.filter.type,
          payment_status: this.filter.payment_status,
          perPage: this.perPage,
        };
        this.axios
          .get("/checkBooks/getCheckBooks/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/checkbooks/exportCheckbBooks/", params);
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
        if (!s26.readCookie("id") && type == "watch") {
          s26.create_cookie("id", id, "checkBooks");
        }
      },
    },
  });
}
