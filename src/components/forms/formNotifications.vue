<template>
  <s26-modal id="formNotifications" @hideModal="hideModal">
    <template v-slot:header>
      <h5 class="modal-title">Nueva Notificación</h5>
    </template>
    <template v-slot:body>
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="col-12" v-if="create_notifications_users == 1">
            <s26-select-user
              label="Seleccionar Usuario"
              size="sm"
              id="form-select_user"
              v-model="form.idUser"
            >
            </s26-select-user>
          </div>
          <div class="col-12">
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
          <div class="col-12 mb-3">
            <s26-textarea
              id="form-description"
              label="Descripción"
              rows="3"
              v-model="form.description"
              :message="msg_error"
              s26-required
            >
            </s26-textarea>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Url"
              size="sm"
              id="form-url"
              type="text"
              v-model="form.url"
              maxlength="1000"
              text
            >
            </s26-form-input>
          </div>
          <div class="col-12 mb-3">
            <s26-date-picker
              id="form-expiration_date"
              enable="unique"
              size="sm"
              v-model="form.expiration_date"
              label="Fecha de Vencimiento"
              s26_required
              :message="msg_error"
              select_all_dates
            ></s26-date-picker>
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
export default {
  props: {
    value: {
      type: Number,
      required: true,
    },
  },
  data: function () {
    return {
      form: {
        idUser: 0,
        name: "",
        description: "",
        url: "",
        expiration_date: "",
      },
      msg_error: "",
      create_notifications_users: create_notifications_users,
    };
  },
  methods: {
    onSubmit() {
      $("[s26-required]").removeClass("is-invalid");
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un nombre.";
        return false;
      }
      if (this.form.description == "") {
        $("#form-description").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar una descripción.";
        return false;
      }
      if (this.form.expiration_date == "") {
        $("#form-expiration_date").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar una fecha.";
        return false;
      }

      let formData = $s26.json_to_formData(this.form);

      $s26.show_loader_points();
      this.axios
        .post("/users/setNotification", formData)
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
      for (let form in this.form) this.form[form] = "";
    },

    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
