<template>
  <s26-modal
    id="readCustomer"
    @hideModal="hideModal"
    footer_none
    body_class="h-auto"
  >
    <template v-slot:header>
      <h5 class="modal-title">Información de Cliente</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-12 col-sm-6" v-if="id !== 0">
          <s26-input-read label="id" :content="form.id"> </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Cédula" :content="form.document">
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Nombres" :content="form.full_name">
          </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Teléfono" :content="form.phone">
          </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Celular" :content="form.mobile">
          </s26-input-read>
        </div>
        <div class="col-9">
          <s26-input-read label="Dirección" :content="form.address">
          </s26-input-read>
        </div>
        <div class="col-3">
          <s26-input-read label="Plazo" :content="form.time_limit">
          </s26-input-read>
        </div>
        <div class="col-8">
          <s26-input-read label="Email" :content="form.email"> </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Estado"
            :content="form.status == 1 ? 'Activo' : 'Inactivo'"
          >
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read
            label="Creado el:"
            :content="$s26.formatDate(form.created_at, 'xl')"
          >
          </s26-input-read>
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
  data: function () {
    return {
      form: {},
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/customers/getCustomer/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "customers");
    },
  },
};
</script>
