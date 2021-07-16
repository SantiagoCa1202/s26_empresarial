<template>
  <s26-modal-multiple
    id="readBankAccount"
    title="Información de Cuenta Bancaria"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    readOnly
  >
    <template v-slot:level-0>
      <div class="col-sm-6">
        <s26-input-read
          label="Entidad Bancaria"
          :content="form.bank_entity.bank_entity"
        ></s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read
          label="Número de Cuenta"
          :content="form.n_account"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Tipo de Cuenta"
          :content="form.account_type"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Chequera"
          :content="form.checkbook == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Predeterminado"
          :content="form.predetermined == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-sm-8">
        <s26-input-read
          label="Creado El:"
          :content="$s26.formatDate(form.created_at)"
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
        bank_entity: {},
      },
      levels: ["Información de Cuenta Bancaria"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/bankAccounts/getBankAccount/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "bankAccounts");
    },
  },
};
</script>