import Vue from "vue";

let element = !!document.getElementById("s26-boxes-view");
if (element) {
  const def_filter = () => {
    return {
      id: "",
      establishment_id: "",
      name: "",
      status: "",
      perPage: 25,
    };
  };
  new Vue({
    el: "#s26-boxes-view",
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
          .get("/boxes/getBoxes/", {
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
        if (!$s26.readCookie("id") && type == "watch") {
          $s26.create_cookie("id", id, "boxes");
        }
      },
      loadMore() {
        this.filter.perPage =
          this.s26_data.info.count > 25
            ? this.filter.perPage + 25
            : this.filter.perPage;
        this.allRows();
      },
    },
  });
}
