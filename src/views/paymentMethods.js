import Vue from "vue";

let element = !!document.getElementById("s26-payment-methods-view");
if (element) {
  const def_filter = () => {
    return {
      name: "",
      status: "",
    };
  };
  new Vue({
    el: "#s26-payment-methods-view",
    data: function() {
      return {
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
          .get("/paymentMethods/getPaymentMethods/", {
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
      },
      onSubmit(id, status) {
        const form = {
          id,
          status,
        };
        this.$alertify.confirm(
          `Desea ${status == 2 ? "Inactivar" : "Activar"} Forma de Pago`,
          () => {
            let formData = $s26.json_to_formData(form);
            $s26.show_loader_points();
            this.axios
              .post("/paymentMethods/setPaymentMethod", formData)
              .then((res) => {
                if (res.data.type >= 1) {
                  this.onReset();
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.$emit("update");
              })
              .catch((err) => console.log(err));
          },
          () => this.$alertify.error("Acción Cancelada")
        );
      },
    },
  });
}
