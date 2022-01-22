import Vue from "vue";

let element = !!document.getElementById("s26-productOutlet-view");
if (element) {
  const def_filter = () => {
    return {
      code: "",
      name: "",
      sale_id: "",
      date: [],
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-productOutlet-view",
    data: function() {
      return {
        fields: [
          {
            name: "N° de Venta",
            class: "length-int",
          },
          {
            name: "Código",
            class: "length-int",
          },
          {
            name: "Producto.",
            class: "length-description",
          },
          {
            name: "Monto",
            class: "length-status text-center",
          },
          {
            name: "Costo",
            class: "length-status text-center",
          },
          {
            name: "P.V.p",
            class: "length-status text-center",
          },
          {
            name: "Descuento",
            class: "length-status text-center",
          },
          {
            name: "Total",
            class: "length-status text-center",
          },
        ],
        filter: def_filter(),
        s26_data: { info: {}, items: [] },
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
          .get("/productsOutlet/getProductsOutlet/", {
            params,
          })
          .then((res) => {
            this.s26_data = res.data;
          })
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
          $s26.create_cookie("id", id, "productsOutlet");
        }
      },
      getSale(id) {
        $s26.create_cookie("id", id, "sales");
        window.open(BASE_URL + "/sales", "_blank");
      },
      getProduct(id) {
        $s26.create_cookie("id", id, "products");
        window.open(BASE_URL + "/products", "_blank");
      },
      loadMore() {
        this.filter.perPage =
        this.s26_data.info.count > 25 ? this.filter.perPage + 25 : this.filter.perPage;
        this.allRows();
      },
    },
  });
}
