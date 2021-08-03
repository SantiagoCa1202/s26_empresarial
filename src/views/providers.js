import Vue from "vue";

let element = !!document.getElementById("s26-providers-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      document: "",
      business_name: "",
      tradename: "",
      city_id: "",
      date: [],
      status: "",
      perPage: 25,
    };
  };
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
        filter: def_filter(),
        s26_data: { info: {} },
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
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];

        this.axios
          .get("/providers/getProviders/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))

          .catch((err) => console.log(err));

        this.url_export = $s26.url_get("/providers/exportProviders/", params);
      },
      onReset() {
        this.filter = def_filter();

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
