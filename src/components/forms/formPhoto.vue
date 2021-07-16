<template>
  <s26-modal-multiple
    id="formPhoto"
    title="Editar Foto"
    :levels="levels"
    body_style="min-height: 365px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-6">
        <div class="row">
          <div class="col-12">
            <s26-form-input
              label="Nombre"
              size="sm"
              id="form-name"
              type="text"
              v-model="form.name"
              maxlength="100"
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Descripción</label>
            <textarea
              id="form-description"
              class="form-control resize-none"
              cols="30"
              rows="5"
              v-model="form.description"
              s26_required
            ></textarea>
          </div>
          <div class="col-12">
            <s26-select-status
              label="Estado"
              id="form-status"
              v-model="form.status"
              s26_required
            >
            </s26-select-status>
          </div>
        </div>
      </div>
      <div class="col-6 h-100 s26-align-center">
        <img :src="form.href" class="rounded shadow-sm w-100" />
      </div>
      <div class="col-12">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at) }}
      </div>
    </template>
  </s26-modal-multiple>
</template>

<script>
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
  data: function () {
    return {
      form: {
        id: "",
        name: "",
        description: "",
        status: 1,
        created_at: "",
      },
      levels: ["Información"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/photos/getPhoto/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea Actualizar Foto?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/photos/updatePhoto", formData)
            .then((res) => {
              if (res.data.type > 0) {
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
      for (let i in this.form) this.form[i] = "";

      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>