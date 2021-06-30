import Vue from "vue";

let element = !!document.getElementById("s26-roles-view");
if (element) {
  new Vue({
    el: "#s26-roles-view",
    data: function() {
      return {
        fields: [
          {
            name: "nombre",
            class: "length-description",
          },
          {
            name: "descripción",
            class: "length-description",
          },
          {
            name: "estado",
            class: "length-status",
          },
        ],
        filter: {
          id: "",
          name: "",
          description: "",
          status: "",
          date: "",
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
      setTimeout(() => {
        this.allRows();
      }, 100);
    },
    methods: {
      allRows() {
        const params = {
          id: this.filter.id,
          name: this.filter.name,
          description: this.filter.description,
          status: this.filter.status,
          date: this.filter.date,
          perPage: this.perPage,
        };
        this.axios
          .get("/roles/getRoles/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/roles/exportRoles/", params);
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
