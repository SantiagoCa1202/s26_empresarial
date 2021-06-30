<template>
  <s26-modal id="formCategory" @hideModal="hideModal" body_class="h-auto">
    <template v-slot:header>
      <h5 class="modal-title">
        {{ id !== 0 ? "Editar  " + id : "Nueva " }} categoría
      </h5>
    </template>
    <template v-slot:body>
      <form id="formSetCategory" @submit.prevent>
        <div class="row">
          <div class="col-6">
            <div class="row">
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
                  :message="msg_error"
                >
                </s26-form-input>
              </div>
              <div class="col-12">
                <s26-textarea
                  id="form-description"
                  label="Descripción"
                  rows="5"
                  v-model="form.description"
                  :message="msg_error"
                >
                </s26-textarea>
              </div>
              <div class="col-9">
                <s26-select-status
                  label="Estado"
                  id="form-status"
                  v-model="form.status"
                  s26_required
                >
                </s26-select-status>
              </div>
              <div class="col-3">
                <label>Color</label>
                <input
                  type="color"
                  class="form-control form-control-color"
                  v-model="form.color"
                />
              </div>
              <div class="col-12">
                <s26-select-icon
                  label="Seleccionar Icono"
                  size="sm"
                  id="form-select_icon"
                  v-model="form.icon_id"
                  search="filter.icon"
                  ref="selectIcon"
                >
                </s26-select-icon>
              </div>
            </div>
          </div>
          <div class="col-6 s26-align-center">
            <s26-input-photo
              ref="inputPhoto"
              id="form-img"
              v-model="form.photo_id"
            >
            </s26-input-photo>
          </div>
          <div class="col-12" v-if="id !== 0">{{ form.created_at }}</div>
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
      <button
        type="button"
        class="btn btn-info"
        @click="id !== 0 ? (code = true) : onSubmit()"
      >
        {{ id !== 0 ? "Guardar" : "Añadir" }}
      </button>
    </template>
    <template v-slot:subModal>
      <transition name="slide-fade">
        <s26-security-code
          :func="onSubmit"
          v-if="code"
          v-model="code"
        ></s26-security-code>
      </transition>
    </template>
  </s26-modal>
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
      msg_error: "",
      code: false,
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
          this.$refs.inputPhoto.getPhoto(res.data.photo_id);
          this.$refs.selectIcon.getIcon(res.data.icon_id);
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
      if (!this.valForm()) {
        return false;
      }
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
    onReset() {
      for (let i in this.form) {
        this.form[i] = "";
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    valForm() {
      $("[s26-required]").removeClass("is-invalid");

      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (this.form.description == "") {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un número de cédula.";
        return false;
      }

      if (this.form.status == "") {
        $("#form-status").addClass("is-invalid").focus();
        return false;
      }

      return true;
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
