<template>
  <s26-modal-multiple
    id="formUser"
    :title="id == 0 ? 'Nuevo Usuario' : 'Editar Usuario'"
    v-model="level_select"
    :levels="levels"
    body_style="min-height: 335px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:body>
      <form id="formUser" @submit.prevent>
        <transition-group name="fade" mode="out-in">
          <div
            v-show="level_select == 0"
            class="container-level container-level0 row"
            key="info_user"
          >
            <div class="col-12 col-sm-6">
              <s26-form-input
                label="Cédula"
                size="sm"
                id="form-document"
                type="text"
                v-model="form.document"
                strictlength="10"
                number
                length
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-12 col-sm-6">
              <s26-form-input
                label="Nombres"
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
            <div class="col-12 col-sm-6">
              <s26-form-input
                label="Apellidos"
                size="sm"
                id="form-last_name"
                type="text"
                v-model="form.last_name"
                maxlength="100"
                minlength="5"
                text
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-12 col-sm-6">
              <s26-form-input
                label="Email"
                size="sm"
                id="form-email"
                type="text"
                v-model="form.email"
                maxlength="100"
                email
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-6">
              <s26-select-gender
                label="Género / Sexo"
                size="sm"
                id="form-select_role"
                v-model="form.gender_id"
                s26_required
              >
              </s26-select-gender>
            </div>
            <div class="col-6">
              <s26-select-status
                label="Afiliado al IESS"
                id="form-insurance"
                v-model="form.insurance"
                s26_required
              >
              </s26-select-status>
            </div>
            <div class="col-6">
              <s26-form-input
                label="Teléfono"
                size="sm"
                id="form-phone"
                type="text"
                v-model="form.phone"
                strictlength="10"
                number
                length
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-6">
              <s26-date-picker
                id="form-date_of_birth"
                enable="unique"
                size="sm"
                v-model="form.date_of_birth"
                label="Fecha de Nacimiento"
                s26_required
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-12" v-if="id !== 0">
              <span class="fw-bold">Creado el:</span> {{ form.created_at }}
            </div>
          </div>
          <div
            v-show="level_select == 1"
            class="container-level container-level1 row"
            key="info_company"
          >
            <div class="col-12">
              <s26-select-establishment
                id="form-establishment"
                v-model="form.establishment_id"
                s26_required
              >
              </s26-select-establishment>
            </div>
            <div class="col-6">
              <s26-select-role
                label="Seleccionar Rol"
                size="sm"
                id="form-select_role"
                v-model="form.role_id"
                s26_required
              >
              </s26-select-role>
            </div>
            <div class="col-6">
              <s26-select-status
                label="Estado"
                id="form-status"
                v-model="form.status"
                s26_required
              >
              </s26-select-status>
            </div>
            <div class="col-6">
              <s26-select-status
                label="Acceso a Usuarios"
                id="form-user_access"
                v-model="form.user_access"
                s26_required
              >
              </s26-select-status>
            </div>
            <div class="col-6">
              <s26-select-status
                label="Crear Notif."
                id="form-create_notifications_users"
                v-model="form.create_notifications_users"
                s26_required
              >
              </s26-select-status>
            </div>
            <div class="col-6">
              <s26-form-input
                label="Contraseña"
                size="sm"
                id="form-new_password"
                type="password"
                v-model="form.new_password"
                maxlength="100"
                autocomplete="off"
              >
              </s26-form-input>
            </div>
            <div class="col-6">
              <s26-form-input
                label="Confirmar Contraseña"
                size="sm"
                id="form-confirm_password"
                type="password"
                v-model="form.confirm_password"
                maxlength="100"
                autocomplete="off"
              >
              </s26-form-input>
            </div>
          </div>
        </transition-group>
      </form>
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
        last_name: "",
        document: "",
        email: "",
        new_password: "",
        confirm_password: "",
        phone: "",
        gender_id: "",
        date_of_birth: [],
        role_id: "",
        insurance: "",
        establishment_id: "",
        user_access: "",
        create_notifications_users: "",
        status: 1,
        created_at: "",
      },
      code: false,
      levels: ["Información Personal", "Información Empresarial"],
      level_select: 0,
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoData(id) {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      this.axios
        .get("/users/getUser/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.name;
          this.form.last_name = res.data.last_name;
          this.form.document = res.data.document;
          this.form.email = res.data.email;
          this.form.phone = res.data.phone;
          this.form.gender_id = res.data.gender_id;
          this.form.date_of_birth.push(res.data.date_of_birth);
          this.form.role_id = res.data.role_id;
          this.form.insurance = res.data.insurance;
          this.form.establishment_id = res.data.establishment_id;
          this.form.user_access = res.data.user_access;
          this.form.create_notifications_users =
            res.data.create_notifications_users;
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
      let formData = s26.json_to_formData(this.form);
      s26.show_loader_points();
      this.axios
        .post("/users/setUser", formData)
        .then((res) => {
          console.log(res);
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
    showSecurityCode() {
      this.code = true;
    },
    valForm() {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      if (this.form.document == "") {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un número de cédula.";
        return false;
      }
      if (this.form.document.length !== 10) {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "El número de cédula debe contener 10 dígitos.";
        return false;
      }
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (this.form.last_name == "") {
        $("#form-last_name").addClass("is-invalid").focus();
        this.msg_error = "Apellidos requeridos.";
        return false;
      }
      if (this.form.email == "") {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email requerido.";
        return false;
      }
      if (!s26.validEmail(this.form.email)) {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email incorrecto.";
        return false;
      }
      if (this.form.phone == "") {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono requerido.";
        return false;
      }
      if (this.form.phone.length !== 10) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono debe contener 10 dígitos.";
        return false;
      }

      if (this.form.establishment_id == "") {
        $("#form-establishment").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.role_id == "") {
        $("#form-role").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.create_notifications_users == "") {
        $("#form-create_notifications_users").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.status == "") {
        $("#form-status").addClass("is-invalid").focus();
        return false;
      }

      if (
        (this.form.new_password == "" || this.form.confirm_password == "") &&
        this.id == 0
      ) {
        $("#form-new_password, #form-confirm_password")
          .addClass("is-invalid")
          .focus();
        this.msg_error = "Contraseña requerida.";
        return false;
      }
      if (this.form.new_password !== this.form.confirm_password) {
        $("#form-new_password, #form-confirm_password")
          .addClass("is-invalid")
          .focus();
        this.msg_error = "Las contraseñas deben ser iguales.";
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