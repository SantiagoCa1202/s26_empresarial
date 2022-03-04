import Vue from "vue";

let element = !!document.getElementById("s26-stock-adjustment-view");
if (element) {
  const def_filter = () => {
    return {
      ean_code: "",
      sku: "",
      product: "",
      model: "",
      trademark: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-stock-adjustment-view",
    data: function() {
      return {
        fields: [
          {
            name: "Código",
            class: "length-int",
          },
          {
            name: "Producto",
            class: "length-description",
          },
          {
            name: "Precio",
            class: "length-action text-center",
          },
          {
            name: "Ajuste",
            class: "length-action text-center",
          },
        ],
        filter: def_filter(),
        s26_data: { info: {} },
        idRow: null,
        activeSidebar: true,
        action: "",
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
          .get("/stockAdjustment/getAdjustments/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      getInfo(id, module) {
        $s26.create_cookie("id", id, module);
        window.open(BASE_URL + "/" + module, "_blank");
      },
    },
  });
}
