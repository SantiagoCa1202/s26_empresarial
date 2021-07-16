import Vue from "vue";

let element = !!document.getElementById("s26-providers-view");
if (element) {
  new Vue({
    el: "#s26-providers-view",
    data: function() {
      return {
        fields: [
          {
            name: "Ruc",
            class: "length-int",
          },
          {
            name: "Razón Social",
            class: "length-description",
          },
          {
            name: "Cel. Vendedor",
            class: "length-int",
          },
          {
            name: "Alias",
            class: "length-action",
          },
          {
            name: "Estado",
            class: "length-action",
          },
        ],
        filter: {
          id: "",
          document: "",
          business_name: "",
          tradename: "",
          city: "",
          date: "",
          status: "",
        },
        rows: 0,
        items: [],
        perPage: 25,
        idRow: null,
        activeSidebar: true,
        action: "",
        url_export: "",
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
        const params = {
          id: this.filter.id,
          document: this.filter.document,
          business_name: this.filter.business_name,
          tradename: this.filter.tradename,
          date: this.filter.date,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/providers/getProviders/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = $s26.url_get("/providers/exportProviders/", params);
      },
      onReset() {
        for (fil in this.filter) {
          this.filter[fil] = "";
        }
        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "providers");
        }
      },
    },
  });
}
