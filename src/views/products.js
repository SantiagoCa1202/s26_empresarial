import Vue from "vue";

let element = !!document.getElementById("s26-products-view");
if (element) {
  new Vue({
    el: "#s26-products-view",
    data: function() {
      return {
        filter: {
          id: "",
          auxiliary_code: "",
          ean_code: "",
          name: "",
          model: "",
          trademark: "",
          provider: "",
          category: "",
          cost: "",
          pvp: "",
          status: "",
        },
        items: [],
        info: {
          rows: "",
          total_stock: "",
          total_entries: "",
          total_outputs: "",
          total_cost: "",
          total_pvp: "",
        },
        perPage: 25,
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
        const params = {
          id: this.filter.id,
          auxiliary_code: this.filter.auxiliary_code,
          ean_code: this.filter.ean_code,
          name: this.filter.name,
          serie: this.filter.serie,
          trademark: this.filter.trademark,
          provider: this.filter.provider,
          category: this.filter.category,
          cost: this.filter.cost,
          pvp: this.filter.pvp,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/products/getProducts/", {
            params,
          })
          .then((res) => {
            console.log(res);
            this.loading_data = false;
            this.items = res.data.items;
            this.info.rows = res.data.info.count;
            this.info.total_stock = res.data.info.total_stock;
            this.info.total_entries = res.data.info.total_entries;
            this.info.total_outputs = res.data.info.total_outputs;
            this.info.total_cost = res.data.info.total_cost;
            this.info.total_pvp = res.data.info.total_pvp;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = $s26.url_get("/products/exportProducts/", params);
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
