import Vue from "vue";

let element = !!document.getElementById("s26-establishments-view");
if (element) {
  const def_filter = () => {
    return {
      n_establishment: "",
      tradename: "",
      city_id: "",
      status: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-establishments-view",
    data: function() {
      return {
        fields: [
          {
            name: "N° Estab.",
            class: "length-action text-center",
          },
          {
            name: "Nombre Comercial",
            class: "length-description",
          },
          {
            name: "Ciudad",
            class: "length-int",
          },
          {
            name: "Teléfono",
            class: "length-int",
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
          .get("/establishments/getEstablishments/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get(
          "/establishments/exportEstablishments/",
          params
        );
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "establishments");
        }
      },
    },
  });
}
