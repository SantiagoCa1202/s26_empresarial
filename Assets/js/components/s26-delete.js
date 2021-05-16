Vue.component("s26-delete", {
  props: {
    value: {
      type: String,
      required: true,
    },
    post_delete: String,
  },
  data: function () {
    return {};
  },
  created() {},
  methods: {
    deleteRow() {
      show_loader_points();
      axios
        .post("/" + this.post_delete)
        .then((res) => {
          if (res.data.type == 1) {
            alertify.success(res.data.msg);
          } else {
            alertify.error(res.data.msg);
          }
          hide_loader_points();
          this.$emit("update");
          this.$emit("input", null);
        })
        .catch((e) => {
          console.log(e);
        });
    },
  },
  template: `
    <div id="s26-delete"
      class="s26-modal"
      tabindex="-1"
    >
      <div class="s26-modal-dialog modal-md s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              ¿Realmente desea eliminar este registro?
            </h5>
          </div>
          <div class="modal-body">
            <p>Una vez eliminado el registro no podra ser recuperado.</p>
            <button class="btn btn-outline-danger" @click="$emit('input', null)">
              Cancelar
            </button>
            <button class="btn btn-danger" @click="deleteRow">
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  `,
});
