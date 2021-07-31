import Vue from "vue";

let element = !!document.getElementById("s26-checkBooks-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      bank_account_id: "",
      n_check: "",
      date_issue: [],
      date_payment: [],
      beneficiary: "",
      reason: "",
      type: "",
      payment_status: "",
      perPage: 25,
    };
  };
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
        filter: def_filter(),
        s26_data: { info: {} },
        idRow: null,
        activeSidebar: true,
        action: "",
        url_export: "",
      };
    },
    created() {
      if ($s26.readCookie("id")) this.setIdRow($s26.readCookie("id"), "watch");

      this.allRows();
    },
    methods: {
      allRows() {
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];

        this.axios
          .get("/checkBooks/getCheckBooks/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/checkbooks/exportCheckBooks/", params);
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "checkBooks");
        }
      },
    },
  });
}
