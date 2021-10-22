import Vue from "vue";

let element = !!document.getElementById("s26-products-view");
if (element) {
  const def_filter = () => {
    return {
      variants: "",
      sku: "",
      product: "",
      model: "",
      trademark: "",
      provider: "",
      category: "",
      pvp: "",
      status: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-products-view",
    data: function() {
      return {
        filter: def_filter(),
        s26_data: {
          info: {
            rows: "",
            total_stock: "",
            total_entries: "",
            total_outputs: "",
            total_cost: "",
            total_pvp: "",
          },
        },
        loading_data: false,
        idRow: null,
        activeSidebar: true,
        action: "",
        url_export: "",
        code: "",
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
        this.loading_data = true;
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];
        this.axios
          .get("/products/getProducts/", {
            params,
          })
          .then((res) => {
            console.log(res);
            this.s26_data = res.data;
            this.loading_data = false;
          })
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/products/exportProducts/", params);
      },
      onReset() {
        this.filter = def_filter();
        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "products");
        }
      },
      get_code: function(code) {
        this.code = code;
      },
      getProvider(id) {
        $s26.create_cookie("id", id, "providers");
        window.open(BASE_URL + "/providers", "_blank");
      },
    },
  });
}
