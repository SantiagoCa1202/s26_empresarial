import Vue from "vue";

let element = !!document.getElementById("s26-productsDamageds-view");
if (element) {
  const def_filter = () => {
    return {
      code: "",
      name: "",
      document_id: "",
      date: [],
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-productsDamageds-view",
    data: function() {
      return {
        fields: [
          {
            name: "Código",
            class: "length-int",
          },
          {
            name: "Nombre",
            class: "length-description",
          },
          {
            name: "Cant.",
            class: "length-action text-center",
          },
          {
            name: "Costo",
            class: "length-action text-center",
          },
          {
            name: "Documento",
            class: "length-description",
          },
          {
            name: "Fecha",
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
          .get("/productsDamageds/getProductsDamageds/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "productsDamageds");
        }
      },
      getDocument(document_id) {
        $s26.create_cookie("id", document_id, "buys");
        window.open(BASE_URL + "/buys", "_blank");
      },
    },
  });
}
