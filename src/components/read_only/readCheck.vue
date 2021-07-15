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
          <s26-input-read label="N° de Cheque" :content="form.n_check">
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Beneficiario" :content="form.beneficiary">
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-textarea-read label="Motivo" :content="form.reason" rows="2">
          </s26-textarea-read>
        </div>
        <div class="col-4">
          <s26-input-read label="Fecha de Emisión" :content="form.date_issue">
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read label="Fecha de Pago" :content="form.date_payment">
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
          <s26-input-read label="Creado el:" :content="form.created_at">
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
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/checkBooks/getCheckBook/" + id)
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
      s26.delete_cookie("id", "checkBooks");
    },
  },
};
</script>
