<template>
  <s26-modal
    id="readCheckBook"
    @hideModal="hideModal"
    footer_none
    body_class="h-auto"
  >
    <template v-slot:header>
      <h5 class="modal-title">Información de Cheque</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-4">
          <s26-input-read label="id" :content="form.id"> </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Cuenta Bancaria"
            :content="form.bank_account.bank_entity.bank_entity"
          >
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="N° de Cheque"
            :content="form.n_check.padStart(6, '0')"
          >
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Beneficiario" :content="form.beneficiary">
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-textarea-read label="Motivo" :content="form.reason" rows="5">
          </s26-textarea-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Fecha de Emisión"
            :content="$s26.formatDate(form.date_issue)"
          >
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Fecha de Pago"
            :content="$s26.formatDate(form.date_payment)"
          >
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read label="Tipo" :content="form.type"> </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read label="Estado" :content="form.payment_status">
          </s26-input-read>
        </div>
        <div class="col-8">
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
      form: {
        bank_account: {
          bank_entity: {},
        },
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/checkBooks/getCheckBook/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "checkBooks");
    },
  },
};
</script>
