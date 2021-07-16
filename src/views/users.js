import Vue from "vue";
let element = !!document.getElementById("s26-users-view");
if (element) {
  new Vue({
    el: "#s26-users-view",
    data: function() {
      return {
        fields: [
          {
            name: "nombre",
            class: "length-description",
          },
          {
            name: "email",
            class: "length-description",
          },
          {
            name: "telefono",
            class: "length-int",
          },
          {
            name: "rol",
            class: "length-action",
          },
          {
            name: "establ.",
            class: "length-action text-center",
          },
          {
            name: "estado",
            class: "length-action",
          },
        ],
        filter: {
          id: "",
          document: "",
          name: "",
          role: "",
          establishment: "",
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
      if ($s26.readCookie("id")) {
        this.setIdRow($s26.readCookie("id"), "watch");
      }
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {
          id: this.filter.id,
          document: this.filter.document,
          name: this.filter.name,
          role_id: this.filter.role,
          establishment_id: this.filter.establishment,
          status: this.filter.status,
          date: this.filter.date,
          perPage: this.perPage,
        };
        this.axios
          .get("/users/getUsers/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = $s26.url_get("/users/exportUsers/", params);
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
          $s26.create_cookie("id", id, "users");
        }
      },
    },
  });
}
