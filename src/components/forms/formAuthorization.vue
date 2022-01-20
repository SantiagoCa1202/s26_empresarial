<template>
  <s26-modal id="formAuthorizations" @hideModal="hideModal" body_class="h-auto">
    <template v-slot:header>
      <h1 class="h4 modal-title">
        {{ id !== 0 && value != "new_auth" ? "Editar  " : "Nueva " }}
        Autorización
      </h1>
    </template>
    <template v-slot:body>
      <form id="formAuthorizations" @submit.prevent>
        <div class="row">
          <div class="col-6">
            <s26-form-input
              label="N° de Autorización"
              id="form-authorization"
              type="text"
              v-model="form.authorization"
              strictlength="10,49"
              number
              s26_required
              length
            >
            </s26-form-input>
          </div>
          <div class="col-3">
            <s26-form-input
              label="Desde"
              id="form-from_"
              type="text"
              v-model="form.from_"
              maxlength="9"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-3">
            <s26-form-input
              label="Hasta"
              id="form-to_"
              type="text"
              v-model="form.to_"
              maxlength="9"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-sm-6">
            <s26-date-picker
              id="form-authorization_date"
              enable="unique"
              size="sm"
              v-model="form.authorization_date"
              label="Fecha de Autorización"
              s26_required
              select_all_dates
              today
            ></s26-date-picker>
          </div>
          <div class="col-sm-6">
            <s26-date-picker
              id="form-expiration_date"
              enable="unique"
              size="sm"
              v-model="form.expiration_date"
              label="Fecha de Vencimiento"
              s26_required
              select_all_dates
              today
            ></s26-date-picker>
          </div>
          <div class="col-12" v-if="id !== 0 && value != 'new_auth'">
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
        @click="id !== 0 && value != 'new_auth' ? infoData(id) : onReset()"
      >
        {{ id !== 0 && value != "new_auth" ? "Deshacer" : "Resetear" }}
      </button>
      <button type="button" class="btn btn-info" @click="onSubmit">
        {{ id !== 0 && value != "new_auth" ? "Guardar" : "Añadir" }}
      </button>
    </template>
  </s26-modal>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    emission_point_id: "",
    authorization: "",
    from_: "",
    to_: "",
    authorization_date: "",
    expiration_date: "",
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
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null && this.value != "new_auth")
      this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/documents/getAuthorization/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      if (this.value == "new_auth") {
        this.form.emission_point_id = this.id;
        this.form.id = 0;
      } else {
        this.form.id = this.id;
      }
      if (!$s26.val_form("formAuthorizations")) {
        this.$alertify.error(
          "Es Necesario Llenar todos los campos requeridos."
        );
        return false;
      }
      this.$alertify.confirm(
        `Desea ${
          this.value == "new_auth" ? "Ingresar " : "Actualizar"
        } Autorización?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/documents/setAuthorization", formData)
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
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
