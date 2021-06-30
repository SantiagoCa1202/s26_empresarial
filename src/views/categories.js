import Vue from "vue";

let element = !!document.getElementById("s26-categories-view");
if (element) {
  new Vue({
    el: "#s26-categories-view",
    data: function() {
      return {
        fields: [
          {
            name: "Icono",
            class: "length-action",
          },
          {
            name: "Nombre",
            class: "length-description",
          },
          {
            name: "Descripción",
            class: "length-description",
          },
          {
            name: "Estado",
            class: "length-action",
          },
        ],
        filter: {
          id: "",
          name: "",
          description: "",
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
          name: this.filter.name,
          description: this.filter.description,
          date: this.filter.date,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/categories/getCategories/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/categories/exportCategories/", params);
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
