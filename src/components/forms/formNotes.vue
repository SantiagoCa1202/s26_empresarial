<template>
  <s26-modal id="formNotes" @hideModal="hideModal">
    <template v-slot:header>
      <h5 class="modal-title">{{ value !== 0 ? "Editar " : "Nueva " }} Nota</h5>
    </template>
    <template v-slot:body>
      <form @submit.prevent>
        <div class="row">
          <div class="col-12">
            <s26-form-input
              label="Título"
              size="sm"
              id="form-name"
              type="text"
              v-model="form.name"
              maxlength="100"
              text
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-textarea
              id="form-description"
              label="Descripción"
              rows="9"
              v-model="form.note"
            >
            </s26-textarea>
          </div>
          <div class="col-12">
            <div class="row">
              <label class="col-sm-6 col-form-label">Seleccionar Color:</label>
              <div class="col-sm-6">
                <input
                  type="color"
                  class="form-control form-control-color float-end"
                  v-model="form.color"
                  title="Choose your color"
                />
              </div>
            </div>
          </div>
          <div class="col-12 mb-4" v-if="value !== 0">
            {{ $s26.formatDate(form.created_at, "xl") }}
          </div>
        </div>
      </form>
    </template>
    <template v-slot:footer>
      <button
        type="button"
        class="btn btn-outline-danger"
        @click="value !== 0 ? infoData(value) : onReset"
      >
        {{ value !== 0 ? "Deshacer" : "Resetear" }}
      </button>
      <button type="button" class="btn btn-info" @click="onSubmit">
        {{ value == 0 ? "Añadir" : "Guardar" }}
      </button>
    </template>
  </s26-modal>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    name: "",
    note: "",
    color: "#ffc107",
    created_at: "",
  };
};
export default {
  props: {
    value: {
      type: Number,
      required: true,
    },
  },
  data: function () {
    return {
      form: def_form(),
    };
  },
  created() {
    if (this.value !== 0 && this.value !== null) this.infoData(this.value);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/users/getMyNote/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.value;
      $s26.show_loader_points();

      let formData = $s26.json_to_formData(this.form);
      this.axios
        .post("/users/setNote", formData)
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
    onReset() {
      this.form = def_form();
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
