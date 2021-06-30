<template>
  <s26-modal id="formCustomer" @hideModal="hideModal">
    <template v-slot:header>
      <h5 class="modal-title">
        {{ id !== 0 ? "Editar  " + id : "Nuevo " }} Cliente
      </h5>
    </template>
    <template v-slot:body>
      <form @submit.prevent>
        <div class="row">
          <div class="col-sm-4">
            <s26-form-input
              label="Cédula / RUC "
              length
              size="sm"
              id="form-document"
              type="text"
              v-model="form.document"
              maxlength="13"
              minlength="10"
              number
              s26_required
              :message="msg_error"
            >
            </s26-form-input>
          </div>
          <div class="col-sm-8">
            <s26-form-input
              label="Nombres / Razón Social"
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
          <div class="col-6">
            <s26-form-input
              label="Teléfono"
              size="sm"
              id="form-phone"
              type="text"
              v-model="form.phone"
              maxlength="9"
              number
              length
              s26_required
              :message="msg_error"
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
              maxlength="10"
              number
              length
              s26_required
              :message="msg_error"
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
              :message="msg_error"
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
              s26_required
              :message="msg_error"
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
        document: "",
        address: "",
        phone: "",
        mobile: "",
        email: "",
        time_limit: "",
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
        .get("/customers/getCustomer/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.full_name;
          this.form.document = res.data.document;
          this.form.address = res.data.address;
          this.form.phone = res.data.phone;
          this.form.mobile = res.data.mobile;
          this.form.email = res.data.email;
          this.form.time_limit = res.data.time_limit;
          this.form.status = res.data.status;
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
    onReset() {
      for (let i in this.form) {
        this.form[i] = "";
      }
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    valForm() {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      if (this.form.document == "") {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un número de cédula.";
        return false;
      }
      if (this.form.document.length < 10 || this.form.document.length > 13) {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error =
          "El número de documento debe contener (10 a 13) dígitos.";
        return false;
      }
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (!s26.validEmail(this.form.email)) {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email incorrecto.";
        return false;
      }
      if (this.form.phone.length !== 9) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono debe contener 9 dígitos.";
        return false;
      }

      if (this.form.mobile.length !== 10) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Celular debe contener 10 dígitos.";
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
