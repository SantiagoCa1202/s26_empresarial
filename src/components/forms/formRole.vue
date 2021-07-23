<template>
  <s26-modal id="formRole" @hideModal="hideModal">
    <template v-slot:header
      ><h5 class="modal-title">{{ id !== 0 ? "Editar " : "Nuevo " }} Rol</h5>
    </template>
    <template v-slot:body>
      <form id="formRole">
        <div class="row">
          <div class="col-12 col-sm-6" v-if="id !== 0">
            <s26-input-read label="id" :content="id"></s26-input-read>
          </div>
          <div class="col">
            <s26-form-input
              label="Nombre"
              size="sm"
              id="form-name"
              type="text"
              v-model="form.name"
              maxlength="100"
              minlength="5"
              text
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Descripción"
              size="sm"
              id="form-description"
              type="text"
              v-model="form.description"
              maxlength="100"
              minlength="5"
              text
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-select-status
              id="form-status"
              label="Estado"
              v-model="form.status"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-12" v-if="id !== 0">
            <span class="fw-bold">Creado el:</span>
            {{ $s26.formatDate(form.created_at, "xl") }}
          </div>
        </div>
      </form>
    </template>
    <template v-slot:footer>
      <button
        type="button"
        class="btn btn-outline-danger"
        @click="id !== 0 ? infoData(id) : onReset()"
      >
        {{ id !== 0 ? "Deshacer" : "Resetear" }}
      </button>
      <button type="button" class="btn btn-info" @click="onSubmit">
        {{ id == 0 ? "Añadir" : "Guardar" }}
      </button>
    </template>
  </s26-modal>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    name: "",
    description: "",
    status: 1,
  };
};
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      form: def_form(),
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/roles/getRol/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      if (!$s26.val_form("formRole")) {
        this.$alertify.error(
          "Es Necesario Llenar todos los campos requeridos."
        );
        return;
      }

      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Rol?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/roles/setRol", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      this.form = def_form();
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>