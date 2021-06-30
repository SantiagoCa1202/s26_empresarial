import Vue from "vue";

let element = !!document.getElementById("s26-customers-view");
if (element) {
  new Vue({
    el: "#s26-customers-view",
    data: function() {
      return {
        fields: [
          {
            name: "C.I / Ruc",
            class: "length-int",
          },
          {
            name: "Razón Social",
            class: "length-description",
          },
          {
            name: "Teléfono",
            class: "length-int",
          },
          {
            name: "Celular",
            class: "length-int",
          },
          {
            name: "Dirección",
            class: "length-description",
          },
          {
            name: "Estado",
            class: "length-action",
          },
        ],
        filter: {
          id: "",
          document: "",
          name: "",
          email: "",
          date: "",
          status: "",
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
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {
          id: this.filter.id,
          document: this.filter.document,
          name: this.filter.name,
          email: this.filter.email,
          date: this.filter.date,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/customers/getCustomers/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/customers/exportCustomers/", params);
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
      },
    },
  });
}
