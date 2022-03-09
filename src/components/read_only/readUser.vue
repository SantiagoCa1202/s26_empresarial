<template>
  <s26-modal-multiple
    id="readUser"
    title="Información de Usuario"
    :levels="levels"
    body_style="min-height: 325px"
    @hideModal="hideModal"
    readOnly
  >
    <template v-slot:level-0>
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
        <s26-input-read label="Nombres" :content="form.name"></s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Apellidos"
          :content="form.last_name"
        ></s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Email" :content="form.email"></s26-input-read>
      </div>
      <div class="col-4">
        <s26-input-read label="Telefono" :content="form.phone"></s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Género / Sexo."
          :content="form.gender.name"
        ></s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Fecha de Nacimiento."
          :content="$s26.formatDate(form.date_of_birth)"
        ></s26-input-read>
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6">
        <s26-input-read
          label="Establecimiento"
          :content="form.establishment.tradename"
          :link="'establishments,' + form.establishment_id"
        ></s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Rol"
          :content="form.role.name"
          :link="'roles,' + form.role_id"
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
      <div class="col-4">
        <s26-input-read
          label="Acceso a Cajas"
          :content="form.access_boxes == 1 ? 'Activo' : 'Inactivo'"
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
      <div class="col-12">
        <s26-input-read
          label="Creado El:"
          :content="$s26.formatDate(form.created_at, 'xl')"
        ></s26-input-read>
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
        establishment: {},
        role: {},
        gender: {},
      },
      levels: ["Información de Usuario", "Información Empresarial"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/users/getUser/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "users");
    },
  },
};
</script>