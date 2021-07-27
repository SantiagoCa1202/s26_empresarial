import Vue from "vue";

let element = !!document.getElementById("s26-buysToProviders-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      ruc: "",
      business_name: "",
      n_document: "",
      n_authorization: "",
      establishment_id: "",
      type_doc_id: "",
      status: "",
      date_issue: [],
      created_at: [],
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-buysToProviders-view",
    data: function() {
      return {
        fields: [
          {
            name: "Fecha",
            class: "length-date",
          },
          {
            name: "N° de Doc.",
            class: "length-int",
          },
          {
            name: "Razón Social",
            class: "length-description",
          },
          {
            name: "Iva",
            class: "length-action",
          },
          {
            name: "Total",
            class: "length-action",
          },
          {
            name: "Tipo",
            class: "length-action",
          },
          {
            name: "Ride",
            class: "length-action text-center",
          },
          {
            name: "Estab.",
            class: "length-action text-center",
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
          .get("/buysToProviders/getBuys/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/buysToProviders/exportBuys/", params);
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "buysToProviders");
        }
      },
    },
  });
}
