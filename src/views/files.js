import Vue from "vue";

let element = !!document.getElementById("s26-files-view");
if (element) {
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
        filter: {
          id: "",
          name: "",
          date: "",
          favorites: "",
          status: "",
        },
        rows: 0,
        items: [],
        perPage: 25,
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
        const params = {
          id: this.filter.id,
          name: this.filter.name,
          date: this.filter.date,
          favorites: this.filter.favorites,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/files/getFiles/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
        this.url_export = s26.url_get("/files/exportFiles/", params);
      },
      addToFavorites(id) {
        s26.show_loader_points();
        this.axios
          .post("/files/addToFavorites/" + id)
          .then((res) => {
            if (res.data.type == 1) {
              this.$alertify.success(res.data.msg);
            } else {
              this.$alertify.error(res.data.msg);
            }
            s26.hide_loader_points();
            this.allRows();
          })
          .catch((e) => {
            console.log(e);
          });
      },
      filterFavorites() {
        this.filter.favorites = this.filter.favorites == 1 ? "" : 1;
        this.allRows();
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
      },
    },
  });
}
