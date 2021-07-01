<template>
  <s26-modal id="formCustomer" @hideModal="hideModal">
    <template v-slot:header>
      <h5 class="modal-title">
        {{ id !== 0 ? "Editar  " : "Nuevo " }} Cliente
      </h5>
    </template>
    <template v-slot:body>
      <form id="formCustomer" @submit.prevent>
        <div class="row">
          <div class="col-sm-5">
            <s26-form-input
              label="Cédula / RUC "
              size="sm"
              id="form-document"
              type="text"
              v-model="form.document"
              strictlength="10,13"
              number
              length
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-sm-7">
            <s26-form-input
              label="Nombres / Razón Social"
              size="sm"
              id="form-name"
              type="text"
              v-model="form.full_name"
              maxlength="100"
              text
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-6">
            <s26-form-input
              label="Teléfono"
              size="sm"
              id="form-phone"
              type="text"
              v-model="form.phone"
              strictlength="9"
              number
              length
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-6">
            <s26-form-input
              label="Celular"
              size="sm"
              id="form-mobile"
              type="text"
              v-model="form.mobile"
              strictlength="10"
              number
              length
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-sm-9">
            <s26-form-input
              label="Dirección"
              size="sm"
              id="form-address"
              type="text"
              v-model="form.address"
              maxlength="100"
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-sm-3">
            <s26-form-input
              label="Plazo"
              size="sm"
              id="form-time_limit"
              type="text"
              v-model="form.time_limit"
              maxlength="10"
              placeholder="0 dias"
            >
            </s26-form-input>
          </div>
          <div class="col-7">
            <s26-form-input
              label="Email"
              size="sm"
              id="form-email"
              type="text"
              v-model="form.email"
              maxlength="100"
              email
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
          <div class="col-12" v-if="id !== 0">
            <span class="fw-bold">Creado el:</span> {{ form.created_at }}
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
        {{ id !== 0 ? "Guardar" : "Añadir" }}
      </button>
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
        full_name: "",
        document: "",
        address: "",
        phone: "",
        mobile: "",
        email: "",
        time_limit: "",
        status: 1,
        created_at: "",
      },
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
        .get("/customers/getCustomer/" + id)
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
      if (!s26.val_form("formCustomer")) {
        this.$alertify.error(
          "Es Necesario Llenar todos los campos requeridos."
        );
        return false;
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Cliente?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/customers/setCustomer", formData)
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
      for (let i in this.form) {
        this.form[i] = "";
      }
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
