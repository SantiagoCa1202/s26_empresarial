import Vue from "vue";

let element = !!document.getElementById("s26-establishments-view");
if (element) {
  new Vue({
    el: "#s26-establishments-view",
    data: function() {
      return {
        fields: [
          {
            name: "N° Estab.",
            class: "length-action text-center",
          },
          {
            name: "Nombre Comercial",
            class: "length-description",
          },
          {
            name: "Ciudad",
            class: "length-int",
          },
          {
            name: "Teléfono",
            class: "length-int",
          },
          {
            name: "Estado",
            class: "length-action",
          },
        ],
        filter: {
          n_establishment: "",
          tradename: "",
          city: "",
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
          n_establishment: this.filter.n_establishment,
          tradename: this.filter.tradename,
          city: this.filter.city,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/establishments/getEstablishments/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = $s26.url_get(
          "/establishments/exportEstablishments/",
          params
        );
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
          $s26.create_cookie("id", id, "establishments");
        }
      },
    },
  });
}
