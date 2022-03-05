<template>
  <s26-modal-multiple
    id="readTransfer"
    title="Información de Transferencia"
    :levels="levels"
    body_style="height: 450px"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <template v-slot:level-0>
      <div class="col-sm-12">
        <s26-input-read
          label="Importe"
          :content="form.amount"
          money
        ></s26-input-read>
      </div>
      <div class="col-sm-5">
        <s26-input-read
          label="Cuenta Origen"
          :content="form.source_account"
          variant_input="text-break overflow-hidden"
        ></s26-input-read>
      </div>
      <div class="col-sm-2 s26-align-center fs-5 text-primary">
        <s26-icon icon="exchange-alt"></s26-icon>
      </div>
      <div class="col-sm-5">
        <s26-input-read
          label="Cuenta Destino"
          :content="form.destination_account"
          variant_input="text-break overflow-hidden"
        ></s26-input-read>
      </div>
      <div class="col-sm-12">
        <s26-textarea-read
          label="Descripción"
          :content="form.description"
          rows="4"
        >
        </s26-textarea-read>
      </div>
      <div class="col-6">
        <s26-input-read label="Establecimiento" :content="form.establishment">
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivos'"
        >
        </s26-input-read>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
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
      form: {},
      levels: ["Información de Transferencia"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/transfers/getTransfer/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "transfers");
    },
  },
};
</script>