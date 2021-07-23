import Vue from "vue";
let element = !!document.getElementById("s26-photos-view");
if (element) {
  const def_filter = () => {
    return {
      name: "",
      favorites: "",
      date: [],
      status: "",
      perPage: 8,
    };
  };
  new Vue({
    el: "#s26-photos-view",
    data: function() {
      return {
        filter: def_filter(),
        s26_data: { info: {} },
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
        const params = {};
        for (let fil in this.filter) params[fil] = this.filter[fil];

        this.axios
          .get("/photos/getPhotos/", {
            params,
          })
          .then((res) => (this.s26_data = res.data))
          .catch((err) => console.log(err));
      },
      addToFavorites(id) {
        $s26.show_loader_points();
        this.axios
          .post("/photos/addToFavorites/" + id)
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
      loadMore() {
        let perPage = this.s26_data.info.count - this.filter.perPage;
        this.filter.perPage =
          perPage > 8 ? this.filter.perPage + 8 : this.s26_data.info.count;
        this.allRows();
      },
    },
  });
}
