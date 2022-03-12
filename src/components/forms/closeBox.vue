<template>
  <s26-modal-multiple
    id="formCloseBox"
    :title="'Cierre de Caja - ' + form.name"
    :levels="levels"
    body_style="height: 465px; max-height: 520px"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <h3 class="h5 fw-600 s26-text-blue">
          Ventas

          <span
            class="float-end text-danger ms-2 opacity-50"
            title="Pagos de Ventas Pendientes"
            v-if="form.report.total_sales_pending > 0"
          >
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(form.report.total_sales_pending) }}
          </span>
          <span class="float-end" title="Solo Ventas Procesadas">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(form.report.total_sales) }}
          </span>
        </h3>
      </div>
      <div class="col-sm-3 col-6" title="Solo Ventas Procesadas">
        <s26-input-read label="Articulos" :content="form.report.articles">
        </s26-input-read>
      </div>
      <div class="col-sm-3 col-6" title="Solo Ventas Procesadas">
        <s26-input-read
          label="Descuento"
          :content="form.report.total_discount"
          money
        >
        </s26-input-read>
      </div>
      <div class="col-sm-3 col-6" title="Devoluciones a la Fecha">
        <s26-input-read
          label="Devoluciones"
          :content="form.report.total_returns"
          money
        >
        </s26-input-read>
      </div>
      <div class="col-sm-3 col-6" title="Abonos a la fecha">
        <s26-input-read
          label="Abonos"
          :content="form.report.total_payments_credit"
          money
        >
        </s26-input-read>
      </div>
      <div class="col-12 mt-3">
        <h3 class="h5 fw-600 s26-text-blue">
          Ingresos Externos
          <a
            class="btn btn-sm btn-link text-decoration-none m-0 p-0 ms-1"
            @click="action = 'new_external_income'"
          >
            <s26-icon icon="plus"></s26-icon>
          </a>
          <span class="float-end h-5" title="Solo ingresos en caja">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{
              $s26.currency(
                parseFloat(form.report.total_external_incomes_gain) +
                  parseFloat(form.report.total_external_incomes_cost)
              )
            }}
          </span>
        </h3>
      </div>
      <div class="col-6" v-if="cost_access">
        <s26-input-read
          label="Costo"
          :content="form.report.total_external_incomes_cost"
          money
        >
        </s26-input-read>
      </div>
      <div class="col">
        <s26-input-read
          label="Ganancia"
          :content="form.report.total_external_incomes_gain"
          money
        >
        </s26-input-read>
      </div>
      <div class="col-12 mt-3">
        <h3 class="h5 fw-600 s26-text-blue">
          Egresos
          <a
            class="btn btn-sm btn-link text-decoration-none m-0 p-0 ms-1"
            @click="action = 'new_expense'"
          >
            <s26-icon icon="plus"></s26-icon>
          </a>
          <span class="float-end h-5" title="Solo Egresos en caja">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{
              $s26.currency(
                parseFloat(form.report.total_expenses_gain) +
                  parseFloat(form.report.total_expenses_cost)
              )
            }}
          </span>
        </h3>
      </div>
      <div class="col-6" v-if="cost_access">
        <s26-input-read
          label="Costo"
          :content="form.report.total_expenses_cost"
          money
        >
        </s26-input-read>
      </div>
      <div class="col">
        <s26-input-read
          label="Ganancia"
          :content="form.report.total_expenses_gain"
          money
        >
        </s26-input-read>
      </div>
      <div class="col-12 mt-3">
        <h3 class="h5 fw-600 s26-text-blue">
          Depositos
          <a
            class="btn btn-sm btn-link text-decoration-none m-0 p-0 ms-1"
            @click="action = 'new_deposit'"
          >
            <s26-icon icon="plus"></s26-icon>
          </a>
          <span class="float-end h-5" title="Solo Depositos Activos">
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(parseFloat(form.report.total_deposits)) }}
          </span>
        </h3>
      </div>
      <!-- Modal Nuevo Ingreso Externo-->
      <transition name="slide-fade">
        <s26-form-external-income
          v-model="action"
          :id="0"
          v-if="action == 'new_external_income'"
          @update="infoData(id)"
        ></s26-form-external-income>
      </transition>
      <!-- Modal Nuevo Egreso-->
      <transition name="slide-fade">
        <s26-form-expense
          v-model="action"
          :id="0"
          v-if="action == 'new_expense'"
          @update="infoData(id)"
        ></s26-form-expense>
      </transition>
      <!-- Modal Nuevo Deposito-->
      <transition name="slide-fade">
        <s26-form-deposit
          v-model="action"
          :id="0"
          v-if="action == 'new_deposit'"
          @update="infoData(id)"
        ></s26-form-deposit>
      </transition>
    </template>
    <template v-slot:level-1>
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
            <s26-form-input type="tel" v-model="co.amount" money>
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
      <div class="col-12">
        <div class="row">
          <div class="col-4 text-center fw-600">Digital</div>
          <div class="col-4 text-center fw-600">Efectivo</div>
          <div class="col-4 text-center fw-600">Diferencia</div>
          <div class="col-4 coins">
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
          <div class="col-4 coins">
            <s26-input-read :content="form.amount" money> </s26-input-read>
          </div>
          <div class="col-4 coins">
            <s26-input-read
              :content="adjusted_box"
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
      form: {
        report: {},
      },
      levels: ["Información de Caja - Hoy", "Cierre de Caja"],
      cost_access: $cost_access,
      action: null,
      adjusted_amount: "",
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  computed: {
    adjusted_box: function () {
      this.adjusted_amount =
        this.coins.reduce(
          (a, b) => a + (parseInt(b.amount) || 0) * parseFloat(b.coin),
          0
        ) - this.form.amount;
      return this.adjusted_amount;
    },
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/boxes/getBox/" + id)
        .then((res) => {
          this.form = Object.assign(res.data, { report: {} });
          this.reportBox(id);
          console.log(this.form);
        })
        .catch((err) => console.log(err));
    },
    reportBox(id) {
      const params = {
        id,
        today: true,
      };
      this.axios
        .get("/boxes/getReportBox/", { params })
        .then((res) => (this.form.report = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      const form = {
        box_id: this.id,
        coins: this.coins,
        adjusted_amount: this.adjusted_amount,
      };

      this.$alertify.confirm(
        `Desea Cerrar Caja y realizar su ajuste?`,
        () => {
          let formData = $s26.json_to_formData(form);
          $s26.show_loader_points();
          this.axios
            .post("/boxes/closeBox", formData)
            .then((res) => {
              if (res.data.type >= 1) {
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
  margin-bottom: 0.2rem !important;
}
</style>