import Vue from "vue";

let element = !!document.getElementById("s26-customers-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      document: "",
      name: "",
      email: "",
      date: [],
      status: "",
      perPage: 25,
    };
  };
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
          .get("/customers/getCustomers/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/customers/exportCustomers/", params);
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "customers");
        }
      },
    },
  });
}
