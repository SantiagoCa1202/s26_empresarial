import Vue from "vue";
let element = !!document.getElementById("s26-users-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      document: "",
      name: "",
      role: "",
      establishment: "",
      status: "",
      date: [],
      perPage: 25,
    };
  };
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
          .get("/users/getUsers/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/users/exportUsers/", params);
      },
      onReset() {
        this.filter = def_filter();
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
