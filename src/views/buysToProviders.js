import Vue from "vue";

let element = !!document.getElementById("s26-buysToProviders-view");
if (element) {
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
            name: "N° de Documento",
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
        filter: {
          id: "",
          ruc: "",
          business_name: "",
          n_document: "",
          n_authorization: "",
          establishment: "",
          status: "",
          date: "",
        },
        items: [],
        info: {
          rows: "",
          total_rise: "",
          total_bi_0: "",
          total_bi_: "",
          total_iva: "",
          total: "",
        },
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
          ruc: this.filter.ruc,
          business_name: this.filter.business_name,
          n_document: this.filter.n_document,
          n_authorization: this.filter.n_authorization,
          establishment: this.filter.establishment,
          status: this.filter.status,
          date: this.filter.date,
          perPage: this.perPage,
        };
        this.axios
          .get("/buysToProviders/getBuys/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.info.rows = res.data.info.count;
            this.info.total_rise = res.data.info.total_rise;
            this.info.total_bi_0 = res.data.info.total_bi_0;
            this.info.total_bi_ = res.data.info.total_bi_;
            this.info.total_iva = res.data.info.total_iva;
            this.info.total = res.data.info.total;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/buysToProviders/exportBuys/", params);
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
          s26.create_cookie("id", id, "buysToProviders");
        }
      },
    },
  });
}
