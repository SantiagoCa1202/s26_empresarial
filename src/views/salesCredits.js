import Vue from "vue";

let element = !!document.getElementById("s26-sales-credits-view");
if (element) {
  const def_filter = () => {
    return {
      sale_id: "",
      n_document: "",
      customer: "",
      establishment_id: "",
      type_doc_id: "",
      status: "",
      date: [],
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-sales-credits-view",
    data: function() {
      return {
        fields: [
          {
            name: "N° de Crédito",
            class: "length-int",
          },
          {
            name: "Art.",
            class: "length-status text-center",
          },
          {
            name: "Desc.",
            class: "length-status text-center",
          },
          {
            name: "Total",
            class: "length-status text-center",
          },
          {
            name: "Saldo",
            class: "length-status text-center",
          },
          {
            name: "Doc.",
            class: "length-status text-center",
          },
          {
            name: "N° de Documento",
            class: "length-description text-center",
          },
          {
            name: "Cliente",
            class: "length-int text-center",
          },
          {
            name: "Action",
            class: "length-action text-center",
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
          .get("/salesCredits/getSalesCredits/", {
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
          $s26.create_cookie("id", id, "salesCredits");
        }
      },
      loadMore() {
        this.filter.perPage =
          this.s26_data.info.count > 25
            ? this.filter.perPage + 25
            : this.filter.perPage;
        this.allRows();
      },
      cancelSale(sale) {
        const name_document =
          sale.name_document != undefined
            ? sale.name_document
            : sale.document.name;

        const msg = `Esta Seguro de Anular.</br> 
          <span class="fw-bold">
            (${sale.id}) ${name_document} - ${sale.n_document} 
          </span>
        `;
        this.$alertify.confirm(
          msg,
          () => {
            $s26.show_loader_points();
            this.axios
              .post("/salesCredits/cancelSaleCredit/" + sale.id)
              .then((res) => {
                if (res.data.type == 2) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.allRows();
              })
              .catch((e) => console.log(e));
          },
          () => {}
        );
      },
    },
  });
}
