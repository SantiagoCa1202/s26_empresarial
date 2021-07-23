import Vue from "vue";

let element = !!document.getElementById("s26-files-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      name: "",
      date: [],
      favorites: "",
      status: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-files-view",
    data: function() {
      return {
        fields: [
          {
            name: "Tipo",
            class: "length-action text-center",
          },
          {
            name: "Nombre",
            class: "length-description",
          },
          {
            name: "Descripción",
            class: "length-description",
          },
          {
            name: "Fav",
            class: "length-action text-center",
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
        activeUploadFile: false,
        action: "",
      };
    },
    created() {
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];

        this.axios
          .get("/files/getFiles/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
        this.url_export = $s26.url_get("/files/exportFiles/", params);
      },
      addToFavorites(id) {
        $s26.show_loader_points();
        this.axios
          .post("/files/addToFavorites/" + id)
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
      filterFavorites() {
        this.filter.favorites = this.filter.favorites == 1 ? "" : 1;
        this.allRows();
      },
      onReset() {
        this.filter = def_filter();

        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
      },
    },
  });
}
