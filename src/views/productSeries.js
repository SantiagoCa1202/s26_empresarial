import Vue from "vue";

let element = !!document.getElementById("s26-productsSeries-view");
if (element) {
  const def_filter = () => {
    return {
      serie: "",
      name: "",
      document_id: "",
      status: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-productsSeries-view",
    data: function() {
      return {
        fields: [
          {
            name: "Nombre",
            class: "length-description",
          },
          {
            name: "Serie",
            class: "length-int",
          },
          {
            name: "Documento",
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
          .get("/productsSeries/getSeries/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/productsSeries/exportSeries/", params);
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "productsSeries");
        }
      },
      getDocument(document_id) {
        $s26.create_cookie("id", document_id, "buys");
        window.open(BASE_URL + "/buys", "_blank");
      },
    },
  });
}
