<template>
  <s26-modal id="infoPayroll" @hideModal="hideModal" footer_none>
    <template v-slot:header>
      <h5 class="modal-title">Información de pago</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-12 col-sm-6">
          <s26-input-read label="Id" :content="form.id"></s26-input-read>
        </div>
        <div class="col-12 col-sm-6">
          <s26-input-read label="Fecha" :content="form.amount_date">
          </s26-input-read>
        </div>
        <div class="col-12 col-sm-6">
          <s26-input-money-read label="Importe / Valor" :content="form.amount">
          </s26-input-money-read>
        </div>
        <div class="col-12 col-sm-6">
          <s26-input-read
            label="Forma de Pago"
            :content="form.payment_method.name"
          >
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-textarea-read
            label="Descripción"
            :content="form.description"
            rows="4"
          >
          </s26-textarea-read>
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
        payment_method: {
          name: "",
        },
      },
    };
  },
  created() {
    if (this.value !== 0 && this.value !== null) this.infoData(this.value);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/users/getPayRecord/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },

    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>