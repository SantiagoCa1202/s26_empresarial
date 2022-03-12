<template>
  <s26-modal-multiple
    id="formBox"
    title="Editar Caja"
    :levels="levels"
    body_style="height: 200px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-form-input
          label="Nombre"
          id="form-name"
          v-model="form.name"
          maxlength="50"
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-sm-8">
        <s26-form-input
          label="Efectivo"
          size="sm"
          id="form-cash"
          type="tel"
          v-model="form.cash"
          money
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-4">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-12" v-if="id !== 0">
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
        cash: "",
        status: 1,
        created_at: "",
      },
      levels: ["Información de Caja"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/boxes/getBox/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea Actualizar Caja`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/boxes/setBox", formData)
            .then((res) => {
              if (res.data.type >= 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((err) => console.log(err));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        for (let i in this.form) this.form[i] = "";
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
