<template>
  <s26-modal id="calculator" @hideModal="hideModal" size="sm">
    <template v-slot:header>
      <h5 class="modal-title">Calculadora</h5>
    </template>
    <template v-slot:body>
      <div class="row mb-2 menu">
        <div class="col-3"></div>
        <div class="col-3 s26-align-center options">
          <button
            type="button"
            :class="['s26-align-center', type == 'calculator' ? 'focus' : '']"
            @click="type = 'calculator'"
          >
            <s26-icon icon="calculator"></s26-icon>
          </button>
        </div>
        <div class="col-3 s26-align-center options">
          <button
            type="button"
            :class="['s26-align-center', type != 'calculator' ? 'focus' : '']"
            @click="type = 'options'"
          >
            <s26-icon icon="th-large"></s26-icon>
          </button>
        </div>
        <div class="col-3"></div>
      </div>
      <template v-if="type == 'options'">
        <div class="row p-1">
          <div class="col-6 mb-3">
            <button
              type="button"
              class="btn s26-btn-outline-primary w-100"
              @click="type = 'discount'"
            >
              <span><s26-icon icon="tag"></s26-icon></span>
              <span>Descuento</span>
            </button>
          </div>
          <div class="col-6 mb-3">
            <button
              type="button"
              class="btn s26-btn-outline-primary w-100"
              @click="type = 'loan'"
            >
              <span><s26-icon icon="hand-holding-usd"></s26-icon></span>
              <span>Préstamo</span>
            </button>
          </div>
          <div class="col-6 mb-3">
            <button
              type="button"
              class="btn s26-btn-outline-primary w-100"
              @click="type = 'commissions'"
            >
              <span><s26-icon icon="credit-card"></s26-icon></span>
              <span>Comisiones</span>
            </button>
          </div>
          <div class="col-6 mb-3">
            <button
              type="button"
              class="btn s26-btn-outline-primary w-100"
              @click="type = 'freight'"
              disabled
            >
              <span><s26-icon icon="truck-loading"></s26-icon></span>
              <span>Flete</span>
            </button>
          </div>
        </div>
      </template>
      <template v-if="type == 'calculator'">
        <div class="row p-1">
          <div class="col-12 history">
            {{ history }}
          </div>
          <div class="col-1 operation s26-align-center">
            <span v-show="total >= 0 && total != ''">
              <s26-icon icon="equals"></s26-icon>
            </span>
          </div>
          <div class="col-11 operation">
            {{ value }}
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="allClear">
              AC
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="backspace">
              <s26-icon icon="backspace"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="calc('%')">
              <s26-icon icon="percentage"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="calc('/')">
              <s26-icon icon="divide"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc('7')">
              7
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(8)">
              8
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(9)">
              9
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="calc('*')">
              <s26-icon icon="times"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(4)">
              4
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(5)">
              5
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(6)">
              6
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="calc('-')">
              <s26-icon icon="minus"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(1)">
              1
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(2)">
              2
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(3)">
              3
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button type="button" class="s26-align-center" @click="calc('+')">
              <s26-icon icon="plus"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center options">
            <button
              type="button"
              class="s26-align-center"
              @click="type = 'history'"
            >
              <s26-icon icon="history"></s26-icon>
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc(0)">
              0
            </button>
          </div>
          <div class="col-3 s26-align-center numbers">
            <button type="button" class="s26-align-center" @click="calc('.')">
              ,
            </button>
          </div>
          <div class="col-3 s26-align-center options equal">
            <button type="button" class="s26-align-center" @click="equal">
              <s26-icon icon="equals"></s26-icon>
            </button>
          </div>
        </div>
      </template>
      <template v-if="type == 'history'">
        <div class="row p-1">
          <div
            class="col-12 text-end mb-3"
            v-for="(h, index) in historial"
            :key="index"
          >
            {{ h }}
          </div>
        </div>
        <button
          type="button"
          class="
            s26-align-center
            btn-return-history
            position-absolute
            bottom-0
            start-0
            m-3
            focus
          "
          @click="type = 'calculator'"
        >
          <s26-icon icon="history"></s26-icon>
        </button>
      </template>
      <template v-if="type == 'discount'">
        <div class="row p-1">
          <div class="col-12">
            <h2 class="h6 fw-bold text-center">Descuento</h2>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Precio Original"
              type="number"
              v-model="discount.original_pvp"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Descuento"
              type="number"
              v-model="discount.discount"
              percentage
            >
            </s26-form-input>
          </div>
          <div class="col-12 mb-3 s26-align-center">
            <span class="fw-600 me-2">Precio Final</span>
            <span class="float-end fs-4">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{
                $s26.currency(
                  discount.original_pvp -
                    (discount.original_pvp * discount.discount) / 100
                )
              }}
            </span>
          </div>
          <div class="col-12 text-center">
            Ahorro
            <s26-icon icon="dollar-sign"></s26-icon>
            {{
              $s26.currency((discount.original_pvp * discount.discount) / 100)
            }}
          </div>
        </div>
      </template>
      <template v-if="type == 'loan'">
        <div class="row p-1">
          <div class="col-12">
            <h2 class="h6 fw-bold text-center">Préstamo</h2>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Capital"
              type="number"
              v-model="loan.capital"
              money
              @keyup="calcLoan"
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Interes Anual (%)"
              type="number"
              v-model="loan.interest"
              percentage
              @keyup="calcLoan"
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Duraciòn del Prestamo (meses)"
              type="number"
              v-model="loan.months"
              number
              int
              @keyup="calcLoan"
            >
            </s26-form-input>
          </div>
          <div class="col-12 s26-align-center">
            <span class="fw-600 me-2">Pago Total</span>
            <span class="float-end fs-4">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(loan.total) }}
            </span>
          </div>
          <div class="col-12 text-center">
            <span class="me-2"> Interes Total</span>
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(loan.total_interest) }}
          </div>
          <div class="col-12 text-center">
            <span class="me-2"> Cuota Mensual </span>
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(loan.share) }}
          </div>
        </div>
      </template>
      <template v-if="type == 'commissions'">
        <div class="row p-1">
          <div class="col-12">
            <h2 class="h6 fw-bold text-center">Comisiones</h2>
          </div>
          <div class="col-6">
            <s26-form-input
              label="Importe 12%"
              type="number"
              v-model="commission.import_iva12"
              money
              @keyup="calcCommissions"
            >
            </s26-form-input>
          </div>
          <div class="col-6">
            <s26-form-input
              label="Importe 0%"
              type="number"
              v-model="commission.import_iva0"
              money
              @keyup="calcCommissions"
            >
            </s26-form-input>
          </div>
          <div class="col-12">
            <s26-form-input
              label="Comisión Tarjeta (%)"
              type="number"
              v-model="commission.interest"
              percentage
              @keyup="calcCommissions"
            >
            </s26-form-input>
          </div>
          <div class="col-12 mb-3 s26-align-center">
            <span class="fw-600 me-2">Desembolso</span>
            <span class="float-end fs-4">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(commission.total) }}
            </span>
          </div>
          <div class="col-12 text-center">
            <span class="me-2"> Comisión Banco</span>
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(commission.commission_bank) }}
          </div>
          <div class="col-12 text-center">
            <span class="me-2">Imp. Renta</span>
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(commission.imp_rent) }}
          </div>
          <div class="col-12 text-center">
            <span class="me-2">Iva</span>
            <s26-icon icon="dollar-sign"></s26-icon>
            {{ $s26.currency(commission.iva) }}
          </div>
        </div>
      </template>
    </template>
  </s26-modal>
</template>

<script>
const exp = /[+\-\*\/]/;

export default {
  data: function () {
    return {
      history: "",
      operation: "",
      type: "calculator",
      total: "",
      historial: [],
      discount: {
        original_pvp: 0,
        discount: 0,
      },
      loan: {
        capital: 0,
        interest: 0,
        months: 0,
        total: 0,
        total_interest: 0,
        share: 0,
      },
      commission: {
        import_iva12: 0,
        import_iva0: 0,
        interest: 0,
        total: 0,
        iva: 0,
        imp_rent: 0,
        commission_bank: 0,
      },
    };
  },
  created() {
    window.addEventListener("keydown", (e) => {
      // console.log(e);
      if (e.key == "Delete") this.allClear();

      if (e.key == "Backspace") this.backspace();

      if (e.key == "Enter") this.equal();

      if (
        (e.key >= 0 && e.key <= 9) ||
        e.key == "." ||
        exp.test(e.key) ||
        e.key == "%"
      ) {
        this.calc(e.key);
      }
    });
  },
  computed: {
    value: function () {
      const history = this.history.replace(/ /g, "");
      let point = history.substr(-2);
      let operator = history.substr(-1);
      let val = "";

      if (exp.test(point) && point.indexOf(".") > -1) {
        val = history.substring(0, history.length - 2);
      } else if (exp.test(operator) || operator.indexOf(".") > -1) {
        val = history.substring(0, history.length - 1);
      } else {
        val = history;
      }
      return history != ""
        ? eval(val)
        : this.total >= 0 && this.total != ""
        ? this.total
        : 0;
    },
  },
  methods: {
    hideModal() {
      this.$emit("input", null);
    },
    // calculadora
    calc(val) {
      this.total = "";
      let operator = this.history.substr(-2);
      let arr = this.history.split(exp);
      let point = arr[arr.length - 1].lastIndexOf(".");
      let cero = arr[arr.length - 1].replace(/ /g, "");

      if (
        (point > -1 && val == ".") ||
        (operator.endsWith(".") && exp.test(val)) ||
        (cero == "0" && val == "0")
      ) {
        return;
      }
      if (val != "%") {
        if (exp.test(val) && exp.test(operator)) {
          this.history = this.history.substring(0, this.history.length - 2);
        }
        this.history += exp.test(val) ? " " : "";
        this.history += val;
        this.history += exp.test(val) ? " " : "";
      } else {
        let plus = this.history.lastIndexOf("+");
        let minus = this.history.lastIndexOf("-");
        let multiply = this.history.lastIndexOf("*");
        let slash = this.history.lastIndexOf("/");
        let sum = 0;
        let percentage = 0;
        let calc = 0;
        if (plus > minus && plus > multiply && plus > slash) {
          sum = this.history.substring(0, plus);
          percentage = this.history.substring(plus + 1);
          calc = (eval(sum) * percentage) / 100;
          this.history = this.history.substring(0, sum.length + 1) + " " + calc;
        } else if (minus > plus && minus > multiply && minus > slash) {
          sum = this.history.substring(0, minus);
          percentage = this.history.substring(minus + 1);
          calc = (eval(sum) * percentage) / 100;
          this.history = this.history.substring(0, sum.length + 1) + " " + calc;
        } else if (multiply > plus && multiply > minus && multiply > slash) {
          sum = this.history.substring(0, multiply);
          percentage = this.history.substring(multiply + 1);
          calc = percentage / 100;
          this.history = this.history.substring(0, sum.length + 1) + " " + calc;
        } else if (slash > plus && slash > minus && slash > multiply) {
          sum = this.history.substring(0, slash);
          percentage = this.history.substring(slash + 1);
          calc = percentage / 100;
          this.history = this.history.substring(0, sum.length + 1) + " " + calc;
        } else if (plus == -1 && minus == -1 && multiply == -1 && slash == -1) {
          calc = this.history / 100;
          this.history = calc.toString();
        }
      }
    },
    equal() {
      if (this.history != "") {
        const history = this.history.replace(/ /g, "");
        let point = history.substr(-2);
        let operator = history.substr(-1);
        let val = "";

        if (exp.test(point) && point.indexOf(".") > -1) {
          val = history.substring(0, history.length - 2);
        } else if (exp.test(operator) || operator.indexOf(".") > -1) {
          val = history.substring(0, history.length - 1);
        } else {
          val = history;
        }
        this.total = val != "" ? eval(val) : "";
        if (val != "") this.historial.push(this.history + " = " + this.total);
        this.history = "";
        console.log(this.historial);
      }
    },
    backspace() {
      this.history = this.history.substring(0, this.history.length - 1);
      this.total = "";
    },
    allClear() {
      this.history = "";
      this.total = "";
    },
    // simulador de prestamos
    calcLoan() {
      let loan = this.loan;
      if (loan.months > 0 && loan.interest > 0 && loan.capital > 0) {
        //meses
        let n = loan.months;
        //INTERES MENSUAL
        let i = loan.interest / 1200;

        //hacemos unos cálculos intermedios
        let factor = Math.pow(i + 1, n);
        //cuota
        loan.share = (loan.capital * i * factor) / (factor - 1);
        loan.total_interest = loan.share * loan.months - loan.capital;
        loan.total = parseFloat(loan.capital) + parseFloat(loan.total_interest);
      }
    },
    //comisiones por compra con tarjeta de credito
    calcCommissions() {
      const commission = this.commission;
      let total_import =
        parseFloat(commission.import_iva12) +
        parseFloat(commission.import_iva0);
      let subtotal =
        parseFloat(commission.import_iva12 / _iva__) +
        parseFloat(commission.import_iva0);
      let total_iva =
        commission.import_iva12 - commission.import_iva12 / _iva__;

      commission.commission_bank = (total_import * commission.interest) / 100;
      commission.imp_rent = subtotal * 0.02;
      commission.iva = total_iva * 0.3;

      commission.total =
        total_import -
        (commission.commission_bank + commission.imp_rent + commission.iva);
    },
  },
};
</script>

<style>
.modal-body {
  height: 435px;
}
.options,
.number {
  margin-bottom: 0.5rem;
}
.options button,
.numbers button,
.btn-return-history {
  border: none;
  text-decoration: none;
  background: transparent;
  font-size: 1.3rem;
  font-weight: bold;
  color: #336079;
  cursor: pointer;
  width: 2rem;
  height: 2rem;
  padding: 1.5rem;
  border-radius: 50%;
  transition: 0.3s;
}
.numbers button {
  color: #243a46;
}
.options button:hover,
.btn-return-history:hover,
.numbers button:hover,
.numbers button:focus,
.numbers button:focus-visible {
  background: rgba(0, 0, 0, 0.1);
  outline: none;
}

.equal button {
  background: #336079;
  color: #fff;
}
.equal button:hover {
  background: #33617983;
}
.menu .options button:focus,
.btn-return-history:focus,
.btn-return-history.focus,
.menu .options button.focus {
  background: #336079;
  color: #fff;
}
.history {
  color: #243a46;
  text-align: right;
  height: 22px;
}
.operation {
  color: #243a46;
  font-weight: bold;
  font-size: 2rem;
  height: 45px;
  text-align: right;
}
</style>