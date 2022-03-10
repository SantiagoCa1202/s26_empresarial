<template>
  <s26-modal-multiple
    id="formCloseBox"
    :title="'Cierre de Caja - ' + form.name"
    :levels="levels"
    body_style="height: 520px; max-height: 520px"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <div class="row">
          <div class="col-4 text-center fw-600">Moneda</div>
          <div class="col-4 text-center fw-600">Monto</div>
          <div class="col-4 text-center fw-600">Total</div>
        </div>
      </div>
      <div class="col-12 coins" v-for="(co, index) in coins" :key="index">
        <div class="row">
          <div class="col-4">
            <s26-input-read :content="co.coin" money></s26-input-read>
          </div>
          <div class="col-4">
            <s26-form-input type="tel" v-model="co.amount" money s26_required>
            </s26-form-input>
          </div>
          <div class="col-4">
            <s26-input-read
              :content="co.coin * co.amount"
              money
            ></s26-input-read>
          </div>
        </div>
      </div>
      <div class="col-12 coins">
        <div class="row">
          <div class="col-4 text-center fw-600">Digital</div>
          <div class="col-4 text-center fw-600">Efectivo</div>
          <div class="col-4 text-center fw-600">Diferencia</div>
          <div class="col-4">
            <s26-input-read
              :content="
                coins.reduce(
                  (a, b) => a + (parseInt(b.amount) || 0) * parseFloat(b.coin),
                  0
                )
              "
              money
            >
            </s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read :content="form.amount" money> </s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              :content="
                coins.reduce(
                  (a, b) => a + (parseInt(b.amount) || 0) * parseFloat(b.coin),
                  0
                ) - form.amount
              "
              money
              :variant_input="
                coins.reduce(
                  (a, b) => a + (parseInt(b.amount) || 0) * parseFloat(b.coin),
                  0
                ) -
                  form.amount >=
                0
                  ? 'text-success'
                  : 'text-danger'
              "
            >
            </s26-input-read>
          </div>
        </div>
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
      coins: [
        {
          coin: "100",
          amount: "",
        },
        {
          coin: "50",
          amount: "",
        },
        {
          coin: "20",
          amount: "",
        },
        {
          coin: "10",
          amount: "",
        },
        {
          coin: "5",
          amount: "",
        },
        {
          coin: "1",
          amount: "",
        },
        {
          coin: "0.50",
          amount: "",
        },
        {
          coin: "0.25",
          amount: "",
        },
        {
          coin: "0.10",
          amount: "",
        },
        {
          coin: "0.05",
          amount: "",
        },
        {
          coin: "0.01",
          amount: "",
        },
      ],
      form: {},
      levels: ["Cierre de Caja"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/boxes/getBox/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea Actualizar Caja`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/boxes/setBox", formData)
            .then((res) => {
              if (res.data.type > 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((err) => console.log(err));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        for (let i in this.form) this.form[i] = "";
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
<style scoped>
.coins .mb-3 {
  margin-bottom: 0.5rem !important;
}
</style>