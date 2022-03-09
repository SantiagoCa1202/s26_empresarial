<template>
  <s26-modal-multiple
    id="readExpense"
    title="Información de Egreso"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <template v-slot:level-0>
      <div class="col-sm-12">
        <s26-input-read
          label="Razón Social"
          :content="form.tradename"
        ></s26-input-read>
      </div>
      <div class="col-sm-12">
        <s26-textarea-read
          label="Descripción"
          :content="form.description"
          rows="6"
        >
        </s26-textarea-read>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6">
        <s26-input-read label="Importe" :content="form.amount" money>
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read
          label="Cuenta"
          :content="form.account == 1 ? 'Costo' : 'Ganancia'"
        >
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read label="Fecha" :content="$s26.formatDate(form.date)">
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read label="N° de Documento" :content="form.n_document">
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read label="Forma de Pago" :content="form.payment_method">
        </s26-input-read>
      </div>
      <div class="col-6">
        <s26-input-read label="Cuenta Bancaria" :content="form.bank_account">
        </s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Caja" :content="form.box">
        </s26-input-read>
      </div>
      <div class="col-4">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        >
        </s26-input-read>
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
      levels: ["Información de Egreso", "Información de Importe"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/expenses/getExpense/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "expenses");
    },
  },
};
</script>