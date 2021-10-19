<template>
  <s26-modal-multiple
    id="formCategory"
    :title="title"
    :levels="levels"
    body_style="min-height: 440px;"
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
      <div class="col-12 s26-align-center mb-3">
        <s26-input-photo id="form-img" v-model="form.photo_id">
        </s26-input-photo>
      </div>
      <div class="col-12" v-if="id !== 0 && form.created_at != ''">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    name: "",
    photo_id: "",
    icon_id: 1,
    color: "#243a46",
    status: 1,
    created_at: "",
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
  data: function () {
    return {
      form: def_form(),
      levels: ["Información de Categoria"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null && this.value == "update") {
      this.infoData(this.id);
    } else if (
      this.id !== 0 &&
      this.id !== null &&
      this.value == "updateSubcategory"
    ) {
      this.infoDataSubcategory(this.id);
    }
  },
  computed: {
    title: function () {
      if (this.value == "update") {
        return (this.id == 0 ? "Nueva " : "Editar ") + "Categoria";
      } else if (this.value == "subcategory") {
        return "Añadir Subcategoria";
      } else if (this.value == "updateSubcategory") {
        return "Editar Subcategoria";
      }
    },
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/categories/getCategory/" + id)
        .then((res) => {
          this.form = res.data;
          if (this.value == "subcategory") {
            this.form.name = "";
            this.form.created_at = "";
          }
        })
        .catch((err) => console.log(err));
    },
    infoDataSubcategory(id) {
      this.axios
        .get("/categories/getSubcategory/" + id)
        .then((res) => {
          this.form = res.data;
          if (this.value == "subcategory") {
            this.form.name = "";
            this.form.created_at = "";
          }
        })
        .catch((err) => console.log(err));
    },
    onSubmit() {
      let dir = "setCategory";

      if (this.value == "subcategory") {
        this.form.id = 0;
        this.form.category_id = this.id;
        dir = "setSubcategory";
      } else if (this.value == "updateSubcategory") {
        this.form.id = this.id;
        dir = "setSubcategory";
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} la Categoria?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/categories/" + dir, formData)
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
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
