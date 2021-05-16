let S26UsersView = new Vue({
  el: "#s26-users-view",
  data: function () {
    return {
      fields: [
        {
          name: "nombre",
          class: "length-description",
        },
        {
          name: "email",
          class: "length-description",
        },
        {
          name: "telefono",
          class: "length-int",
        },
        {
          name: "rol",
          class: "length-action",
        },
        {
          name: "establ.",
          class: "length-action text-center",
        },
        {
          name: "estado",
          class: "length-action",
        },
      ],
      filter: {
        id: "",
        document: "",
        name: "",
        role: "",
        establishment: "",
        status: "",
        date: "",
      },
      rows: 0,
      items: [],
      perPage: 25,
      idRow: null,
      checkedRows: [],
      activeSidebar: true,
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
        document: this.filter.document,
        name: this.filter.name,
        role_id: this.filter.role,
        establishment_id: this.filter.establishment,
        status: this.filter.status,
        date: this.filter.date,
        perPage: this.perPage,
      };
      axios
        .get("/users/getUsers/", {
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
    singleName(fullName) {
      let name = fullName;
      name = name.split(" ");
      name = name[0] + " " + name[2];
      return name;
    },
    onReset() {
      for (fil in this.filter) {
        this.filter[fil] = "";
      }
      this.allRows();
    },
    setIdRow(id, type) {
      this.idRow = id;
      this.action = type;
    },
  },
});
