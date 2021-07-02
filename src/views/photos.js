import Vue from "vue";
let element = !!document.getElementById("s26-photos-view");
if (element) {
  new Vue({
    el: "#s26-photos-view",
    data: function() {
      return {
        filter: {
          name: "",
          favorites: "",
          date: "",
          status: "",
        },
        rows: 0,
        items: [],
        perPage: 8,
        idRow: null,
        activeSidebar: true,
        activeUploadPhoto: false,
        action: "",
      };
    },
    created() {
      this.allRows();
    },
    methods: {
      allRows() {
        const params = {
          name: this.filter.name,
          date: this.filter.date,
          favorites: this.filter.favorites,
          status: this.filter.status,
          perPage: this.perPage,
        };
        this.axios
          .get("/photos/getPhotos/", {
            params,
          })
          .then((res) => {
            this.items = res.data.items;
            this.rows = res.data.info.count;
          })
          .catch((err) => {
            console.log(err);
          });
      },
      addToFavorites(id) {
        s26.show_loader_points();
        this.axios
          .post("/photos/addToFavorites/" + id)
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
        this.perPage = 8;
        for (let fil in this.filter) {
          this.filter[fil] = "";
        }
        this.allRows();
      },
      setIdRow(id, type) {
        this.idRow = parseInt(id);
        this.action = type;
      },
      loadMore() {
        let perPage = this.rows - this.perPage;
        this.perPage = perPage > 8 ? this.perPage + 8 : this.rows;
        this.allRows();
      },
    },
  });
}
