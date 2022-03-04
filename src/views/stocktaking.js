import Vue from "vue";

let element = !!document.getElementById("s26-stocktaking-view");
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
    el: "#s26-stocktaking-view",
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
            name: "Stock",
            class: "length-action text-center",
          },
          {
            name: "Contable",
            class: "length-action text-center",
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
          .get("/stocktaking/getProducts/", {
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
          $s26.create_cookie("id", id, "stocktaking");
        }
      },
      prepareInventory() {
        this.$alertify.confirm(
          `Desea Preparar Inventario?, El proceso tardara unos minutos.
          <ul class="mt-3">
            <li>No cierre el sistema</li>
            <li>No actualice el sistema</li>
            <li>Se reiniciara el inventario actual</li>
            <li>Por favor espere...</li>
          </ul>
          
          `,
          () => {
            $s26.show_loader_points();
            this.axios
              .post("/stocktaking/prepareInventory")
              .then((res) => {
                if (res.data.type == 1) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.allRows();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Preparación de Inventario Cancelada")
        );
      },

      resetInventory() {
        this.$alertify.confirm(
          `Desea Resetear Inventario?, El proceso tardara unos minutos.
          `,
          () => {
            $s26.show_loader_points();
            this.axios
              .post("/stocktaking/resetInventory")
              .then((res) => {
                if (res.data.type == 1) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.allRows();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Reseteo de Inventario Cancelada")
        );
      },

      recalculateStock() {
        this.$alertify.confirm(
          `Desea Recalcular Stock?, El proceso tardara unos minutos.
          <ul class="mt-3">
            <li>No cierre el sistema</li>
            <li>No actualice el sistema</li>
            <li>Se recalculara el stock del establecimiento.</li>
            <li>Por favor espere...</li>
          </ul>
          
          `,
          () => {
            $s26.show_loader_points();
            this.axios
              .post("/stocktaking/recalcStock")
              .then((res) => {
                if (res.data.type == 1) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.allRows();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Recalculo de Stock Cancelado.")
        );
      },
      getInfo(id, module) {
        $s26.create_cookie("id", id, module);
        window.open(BASE_URL + "/" + module, "_blank");
      },
    },
  });
}
