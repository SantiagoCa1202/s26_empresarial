<template>
  <s26-modal-multiple
    id="readUser"
    title="Información de Usuario"
    v-model="level_select"
    :levels="levels"
    body_style="min-height: 400px; height: 400px"
    @hideModal="hideModal"
    readOnly
  >
    <template v-slot:body>
      <transition-group name="fade" mode="out-in">
        <div
          v-if="level_select == 0"
          class="container-level0 row"
          key="info_user"
        >
          <div class="col-12 col-sm-6">
            <s26-input-read label="id" :content="form.id"></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Cédula"
              :content="form.document"
            ></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Nombres"
              :content="form.name"
            ></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Apellidos"
              :content="form.last_name"
            ></s26-input-read>
          </div>
          <div class="col-8">
            <s26-input-read
              label="Email"
              :content="form.email"
            ></s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              label="Telefono"
              :content="form.phone"
            ></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Género / Sexo."
              :content="form.gender.name"
            ></s26-input-read>
          </div>
          <div class="col-5">
            <s26-input-read
              label="Fecha de Nacimiento."
              :content="form.date_of_birth"
            ></s26-input-read>
          </div>
          <div class="col-7">
            <s26-input-read
              label="Creado El:"
              :content="form.created_at"
            ></s26-input-read>
          </div>
        </div>
        <div
          v-if="level_select == 1"
          class="container-level1 row"
          key="info_company"
        >
          <div class="col-6">
            <s26-input-read
              label="Establecimiento"
              :content="form.establishment.tradename"
            ></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Rol"
              :content="form.role.name"
            ></s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              label="Estado"
              :content="form.status == 1 ? 'Activo' : 'Inactivo'"
            ></s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              label="Afiliado al IESS"
              :content="form.insurance == 1 ? 'Activo' : 'Inactivo'"
            ></s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              label="Acceso a Usuarios"
              :content="form.user_access == 1 ? 'Activo' : 'Inactivo'"
            ></s26-input-read>
          </div>
          <div class="col-6">
            <s26-input-read
              label="Generar Notif."
              :content="
                form.create_notifications_users == 1 ? 'Activo' : 'Inactivo'
              "
            ></s26-input-read>
          </div>
        </div>
      </transition-group>
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
        establishment: {},
        role: {},
        gender: {},
      },
      levels: ["Información de Usuario", "Información Empresarial"],
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
      this.axios
        .get("/users/getUser/" + id)
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
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>