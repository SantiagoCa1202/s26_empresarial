<template>
  <s26-modal-multiple
    id="formCategory"
    :title="(id == 0 ? 'Nueva ' : 'Editar ') + 'Categoria'"
    :levels="levels"
    body_style="min-height: 375px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-12">
        <s26-form-input
          label="Nombre"
          size="sm"
          id="form-name"
          type="text"
          v-model="form.name"
          maxlength="100"
          text
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-textarea
          id="form-description"
          label="Descripción"
          rows="5"
          v-model="form.description"
          maxlength="100"
          s26_required
        >
        </s26-textarea>
      </div>
      <div class="col-5">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-5">
        <s26-select-icon
          label="Seleccionar Icono"
          size="sm"
          id="form-select_icon"
          v-model="form.icon_id"
          search="filter.icon"
        >
        </s26-select-icon>
      </div>
      <div class="col-2">
        <label>Color</label>
        <input
          type="color"
          class="form-control form-control-color"
          v-model="form.color"
        />
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-12 s26-align-center">
        <s26-input-photo id="form-img" v-model="form.photo_id">
        </s26-input-photo>
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
        photo_id: "",
        icon_id: 1,
        color: "#243a46",
        status: 1,
        created_at: "",
      },
      levels: ["Información de Categoria", "Imagen / Foto"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/categories/getCategory/" + id)
        .then((res) => {
          this.form = res.data;
          let date = new Date(res.data.created_at);
          this.form.created_at = new Intl.DateTimeFormat("es-ES", {
            dateStyle: "full",
            timeStyle: "short",
            calendar: "ecuador",
          }).format(date);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} la Categoria?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/categories/setCategory", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => {
              console.log(e);
            });
        },
        () => {
          this.$alertify.error("Acción Cancelada");
        }
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        for (let i in this.form) {
          this.form[i] = "";
        }
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
