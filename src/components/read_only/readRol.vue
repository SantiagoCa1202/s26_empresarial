<template>
  <s26-modal id="formRole" @hideModal="hideModal" footer_none>
    <template v-slot:header
      ><h5 class="modal-title">Información De Rol</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-12 col-sm-6" v-if="id !== 0">
          <s26-input-read label="id" :content="id"></s26-input-read>
        </div>
        <div class="col-12 col-sm-6">
          <s26-input-read label="Nombre" :content="form.name"></s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read
            label="Descripción"
            :content="form.description"
          ></s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Estado"
            :content="form.status == 1 ? 'Activo' : 'Inactivo'"
          ></s26-input-read>
        </div>
        <div class="col-8">
          <s26-input-read
            label="Creado El:"
            :content="$s26.formatDate(form.created_at, 'xl')"
          ></s26-input-read>
        </div>
      </div>
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
  data() {
    return {
      form: {
        id: "",
        name: "",
        description: "",
        status: 1,
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/roles/getRol/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "roles");
    },
  },
};
</script>