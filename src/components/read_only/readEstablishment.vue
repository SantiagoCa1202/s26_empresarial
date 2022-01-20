<template>
  <s26-modal-multiple
    id="readEstablishment"
    title="Información de Establecimiento"
    :levels="levels"
    body_style="min-height: 400px"
    size="lg"
    @hideModal="hideModal"
    readOnly
  >
    <template v-slot:level-0>
      <div class="col-6 col-sm-3">
        <s26-input-read
          label="N° de Establecimiento"
          :content="form.n_establishment.padStart(3, '0')"
        ></s26-input-read>
      </div>
      <div class="col-6 col-sm-9">
        <s26-input-read
          label="Nombre Comercial"
          :content="form.tradename"
        ></s26-input-read>
      </div>
      <div class="col-6 col-sm-6">
        <s26-input-read
          label="Provincia"
          :content="form.province"
        ></s26-input-read>
      </div>
      <div class="col-6 col-sm-6">
        <s26-input-read
          label="Parroquia"
          :content="form.parish"
        ></s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Dirección"
          :content="form.address"
        ></s26-input-read>
      </div>
      <div class="col-4">
        <s26-input-read label="Teléfono" :content="form.phone"></s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read
          label="Gerente Administrador"
          :content="form.name + ' ' + form.last_name"
          :link="'users,' + form.executive_id"
        ></s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Creado El:"
          :content="$s26.formatDate(form.created_at, 'xl')"
        ></s26-input-read>
      </div>
      <div class="col-3">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-3">
        <s26-input-read
          label="Dirección IP"
          :content="form.ip_adress"
        ></s26-input-read>
      </div>
    </template>
    <template v-slot:level-1> </template>
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
      form: {},
      levels: [
        "Información de Establecimiento",
        "Puntos de Emisión",
        "Kardex de Ventas",
        "Kardex de transacciones",
        "Información General",
      ],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/establishments/getEstablishment/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "establishments");
    },
  },
};
</script>