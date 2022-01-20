import Vue from "vue";

let element = !!document.getElementById("s26-documents-view");
if (element) {
  const def_filter = () => {
    return {
      type_doc_id: "",
      n_point: "",
      status: "",
      perPage: 25,
    };
  };
  const def_filter_auth = () => {
    return {
      n_authorization: "",
      perPage: 25,
    };
  };
  const def_fields = () => {
    return [
      {
        name: "Documento",
        class: "length-int",
      },
      {
        name: "N° Punto",
        class: "length-action",
      },
      {
        name: "N° Secuencial",
        class: "length-int",
      },
      {
        name: "Estado",
        class: "length-status text-center",
      },
    ];
  };
  new Vue({
    el: "#s26-documents-view",
    data: function() {
      return {
        fields: def_fields(),
        filter: def_filter(),
        authorizations: false,
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
          .get("/documents/getDocuments/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
      },
      getAuthorizations() {
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];

        this.axios
          .get("/documents/getAuthorizations/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
      },
      onReset() {
        this.filter = this.authorizations ? def_filter_auth() : def_filter();

        if (this.authorizations) {
          this.getAuthorizations();
        } else {
          this.allRows();
        }
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "documents");
        }
      },
      changeAuth() {
        this.authorizations = !this.authorizations;

        if (this.authorizations == true) {
          this.filter = def_filter_auth();
          this.getAuthorizations();
          this.fields = [
            {
              name: "Documento",
              class: "length-int",
            },
            {
              name: "N° Punto",
              class: "length-action",
            },
            {
              name: "Autorización",
              class: "length-description",
            },
            {
              name: "Desde",
              class: "length-status text-center",
            },
            {
              name: "Hasta",
              class: "length-status text-center",
            },
            {
              name: "Emisión",
              class: "length-status text-center",
            },
            {
              name: "Expiración",
              class: "length-status text-center",
            },
          ];
        } else {
          this.fields = def_fields();
          this.filter = def_filter();

          this.allRows();
        }
      },
    },
  });
}
