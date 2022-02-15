<template>
  <s26-modal id="process_sale" @hideModal="hideModal">
    <template v-slot:header>
      <h5 class="modal-title">Procesar Venta</h5>
    </template>
    <template v-slot:body>
      <div class="row mb-2 menu">
        <div class="col-4 mb-3">
          <label class="form-label">
            Tipo de Venta
            <span class="text-danger">
              <s26-icon
                icon="asterisk"
                class="icon_asterisk_required"
              ></s26-icon>
            </span>
          </label>
          <select
            class="form-select form-select-sm"
            v-model="form.type"
            @change="valSale"
          >
            <option value="contado">Contado</option>
            <option value="diferido">Diferido</option>
          </select>
        </div>
        <div class="col-8" v-show="form.type == 'contado'">
          <s26-select-emission-point
            id="form-emission_point"
            v-model="form.emission_point"
            s26_required
            type="buy"
            all_info
          ></s26-select-emission-point>
        </div>
        <div
          class="col-12 mb-3"
          v-show="form.type == 'contado' && form.emission_point == 0"
        >
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="form.print"
              id="form-print"
            />
            <label class="form-check-label" for="form-print"> Imprimir. </label>
          </div>
        </div>
        <template v-if="form.type == 'contado'">
          <div class="col-12">
            <h2 class="fw-600 fs-6">Registro de Pago</h2>
          </div>
          <div class="col-12 row mx-0">
            <div
              :class="[
                'col-12 variants row mx-0 user-select pointer',
                active_variants.cash ? 'h-auto' : '',
              ]"
              @dblclick="selectCash"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Efectivo
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.cash ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.cash = !active_variants.cash"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.cash ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-4">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.cash.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-4">
                <s26-form-input
                  label="Efectivo"
                  type="number"
                  v-model="form.payments.cash.cash"
                  money
                >
                </s26-form-input>
              </div>
              <div class="col-4">
                <s26-input-read
                  label="Cambio"
                  :content="form.payments.cash.cash - info_sale.total_sale"
                  money
                >
                </s26-input-read>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.transfer ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Transferencia
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.transfer ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.transfer = !active_variants.transfer"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.transfer ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.transfer.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-transfer"
                  v-model="form.payments.transfer.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.deposit ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Deposito
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.deposit ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.deposit = !active_variants.deposit"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.deposit ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.deposit.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-deposit"
                  v-model="form.payments.deposit.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.debit ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Tarjeta de Debito
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.debit ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.debit = !active_variants.debit"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.debit ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.debit.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-debit"
                  v-model="form.payments.debit.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.credit ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Tarjeta de Crédito
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.credit ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.credit = !active_variants.credit"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.credit ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.credit.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-credit"
                  v-model="form.payments.credit.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
              <div class="col mb-3">
                <label class="form-label"> Transacción </label>
                <select
                  class="form-select form-select-sm"
                  v-model="form.payments.credit.transaction"
                >
                  <option value="corriente">Corriente</option>
                  <option value="diferido">Diferido</option>
                </select>
              </div>
              <div
                class="col-sm-6 mb-3"
                v-show="form.payments.credit.transaction == 'diferido'"
              >
                <label class="form-label"> Cuotas </label>
                <select
                  class="form-select form-select-sm"
                  v-model="form.payments.credit.share"
                >
                  <optgroup label="Sin Intereses">
                    <option value="3 sin intereses">
                      3 meses sin intereses
                    </option>
                    <option value="6 sin intereses">
                      6 meses sin intereses
                    </option>
                    <option value="9 sin intereses">
                      9 meses sin intereses
                    </option>
                    <option value="12 sin intereses">
                      12 meses sin intereses
                    </option>
                    <option value="15 sin intereses">
                      15 meses sin intereses
                    </option>
                    <option value="18 sin intereses">
                      18 meses sin intereses
                    </option>
                    <option value="21 sin intereses">
                      21 meses sin intereses
                    </option>
                    <option value="24 sin intereses">
                      24 meses sin intereses
                    </option>
                  </optgroup>
                  <optgroup label="Con Intereses">
                    <option value="3 con intereses">
                      3 meses con intereses
                    </option>
                    <option value="6 con intereses">
                      6 meses con intereses
                    </option>
                    <option value="9 con intereses">
                      9 meses con intereses
                    </option>
                    <option value="12 con intereses">
                      12 meses con intereses
                    </option>
                    <option value="15 con intereses">
                      15 meses con intereses
                    </option>
                    <option value="18 con intereses">
                      18 meses con intereses
                    </option>
                    <option value="21 con intereses">
                      21 meses con intereses
                    </option>
                    <option value="24 con intereses">
                      24 meses con intereses
                    </option>
                  </optgroup>
                </select>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.gift ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Tarjeta de Regalo
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.gift ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.gift = !active_variants.gift"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.gift ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.gift.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-gift"
                  v-model="form.payments.gift.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
            </div>
            <div
              :class="[
                'col-12 variants row mx-0',
                active_variants.check ? 'h-auto' : '',
              ]"
            >
              <h2 class="h6 fw-600 mb-3 s26-text-blue">
                Cheque
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.check ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.check = !active_variants.check"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.check ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-6">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="form.payments.check.amount"
                  money
                  @keyup="valSale"
                >
                </s26-form-input>
              </div>
              <div class="col-sm-6">
                <s26-select-bank-account
                  size="sm"
                  id="form-bank_account_id-check"
                  v-model="form.payments.check.bank_account_id"
                >
                </s26-select-bank-account>
              </div>
              <div class="col-2">
                <s26-form-input
                  label="N°"
                  type="number"
                  v-model="form.payments.check.n_check"
                  number
                >
                </s26-form-input>
              </div>
              <div class="col-5">
                <s26-select-bank
                  size="sm"
                  id="form-bank_entity_id-check"
                  v-model="form.payments.check.bank_entity_id"
                >
                </s26-select-bank>
              </div>
              <div class="col-5">
                <s26-date-picker
                  id="form-date"
                  enable="unique"
                  size="sm"
                  v-model="form.payments.check.date"
                  label="Fecha"
                  s26_required
                  select_all_dates
                  today
                ></s26-date-picker>
              </div>
            </div>
          </div>
        </template>
        <template v-if="form.type == 'diferido'">
          <div class="col-12 row mx-0 px-0">
            <div class="col-12">
              <h2 class="fw-600 fs-6">Registro de Abono</h2>
            </div>
            <div class="col-sm-6">
              <s26-select-payment-method
                id="form-payment_method_id"
                v-model="form.credit[0].payment_method_id"
                s26_required
              ></s26-select-payment-method>
            </div>
            <div class="col-6">
              <s26-form-input
                label="Monto"
                type="number"
                v-model="form.credit[0].amount"
                money
                s26-required
                @keyup="valSale"
              >
              </s26-form-input>
            </div>
            <div class="col-sm-6" v-if="form.credit[0].payment_method_id > 1">
              <s26-select-bank-account
                label="Cuenta Bancaria"
                size="sm"
                id="form-bank_account_id"
                v-model="form.credit[0].bank_account_id"
                s26_required
              >
              </s26-select-bank-account>
            </div>
            <template v-if="form.credit[0].payment_method_id == 5">
              <div class="col-6 mb-3">
                <label class="form-label"> Transacción </label>
                <select
                  class="form-select form-select-sm"
                  v-model="form.credit[0].transaction"
                >
                  <option value="corriente">Corriente</option>
                  <option value="diferido">Diferido</option>
                </select>
              </div>
              <div
                class="col-sm-6 mb-3"
                v-show="form.credit[0].transaction == 'diferido'"
              >
                <label class="form-label"> Cuotas </label>
                <select
                  class="form-select form-select-sm"
                  v-model="form.credit[0].share"
                >
                  <optgroup label="Sin Intereses">
                    <option value="3 sin intereses">
                      3 meses sin intereses
                    </option>
                    <option value="6 sin intereses">
                      6 meses sin intereses
                    </option>
                    <option value="9 sin intereses">
                      9 meses sin intereses
                    </option>
                    <option value="12 sin intereses">
                      12 meses sin intereses
                    </option>
                    <option value="15 sin intereses">
                      15 meses sin intereses
                    </option>
                    <option value="18 sin intereses">
                      18 meses sin intereses
                    </option>
                    <option value="21 sin intereses">
                      21 meses sin intereses
                    </option>
                    <option value="24 sin intereses">
                      24 meses sin intereses
                    </option>
                  </optgroup>
                  <optgroup label="Con Intereses">
                    <option value="3 con intereses">
                      3 meses con intereses
                    </option>
                    <option value="6 con intereses">
                      6 meses con intereses
                    </option>
                    <option value="9 con intereses">
                      9 meses con intereses
                    </option>
                    <option value="12 con intereses">
                      12 meses con intereses
                    </option>
                    <option value="15 con intereses">
                      15 meses con intereses
                    </option>
                    <option value="18 con intereses">
                      18 meses con intereses
                    </option>
                    <option value="21 con intereses">
                      21 meses con intereses
                    </option>
                    <option value="24 con intereses">
                      24 meses con intereses
                    </option>
                  </optgroup>
                </select>
              </div>
            </template>
            <template v-if="form.credit[0].payment_method_id == 7">
              <div class="col-6">
                <s26-form-input
                  label="N° de Cheque"
                  type="number"
                  v-model="form.credit[0].n_check"
                  number
                >
                </s26-form-input>
              </div>
              <div class="col-6">
                <s26-select-bank
                  size="sm"
                  id="form-credit-bank_entity_id-check"
                  v-model="form.credit[0].bank_entity_id"
                >
                </s26-select-bank>
              </div>
              <div class="col-6">
                <s26-date-picker
                  id="form-credit-date_check"
                  enable="unique"
                  size="sm"
                  v-model="form.credit[0].date_check"
                  label="Fecha"
                  s26_required
                  select_all_dates
                  today
                ></s26-date-picker>
              </div>
            </template>
            <div class="col-12 mb-3" v-show="form.type == 'diferido'">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  v-model="form.print"
                  id="form-print"
                />
                <label class="form-check-label" for="form-print">
                  Imprimir Recibo
                </label>
              </div>
            </div>
          </div>
        </template>
      </div>
    </template>
    <template v-slot:footer>
      <div class="col-12 mb-3">
        <button
          type="button"
          class="btn btn btn-primary w-100"
          :disabled="process"
          @click="processSale"
        >
          Procesar
        </button>
      </div>
    </template>
  </s26-modal>
</template>

<script>
const def_form = () => {
  return {
    type: "contado",
    emission_point: 0,
    print: 0,
    payments: {
      cash: {
        amount: 0,
        cash: 0,
        payment_method_id: 1,
      },
      transfer: {
        amount: 0,
        payment_method_id: 2,
        bank_account_id: "",
      },
      deposit: {
        amount: 0,
        payment_method_id: 3,
        bank_account_id: "",
      },
      debit: {
        amount: 0,
        payment_method_id: 4,
        bank_account_id: "",
      },
      credit: {
        amount: 0,
        payment_method_id: 5,
        bank_account_id: "",
        transaction: "corriente",
        share: "",
      },
      gift: {
        amount: 0,
        payment_method_id: 6,
        bank_account_id: "",
      },
      check: {
        amount: 0,
        payment_method_id: 7,
        bank_account_id: "",
        bank_entity_id: "",
        n_check: "",
        date: "",
      },
    },
    credit: [
      {
        payment_method_id: 1,
        amount: 0,
        bank_account_id: 0,
        transaction: "corriente",
        share: "",
        n_check: "",
        bank_entity_id: 0,
        date_check: "",
      },
    ],
  };
};

export default {
  props: {
    info_sale: {
      type: Object,
      default: () => {
        return {
          total_sale: 50,
        };
      },
    },
  },
  data: function () {
    return {
      process: true,
      form: def_form(),
      active_variants: {
        cash: true,
        transfer: false,
        deposit: false,
        debit: false,
        credit: false,
        gift: false,
        check: false,
      },
    };
  },
  created() {
    console.log(this.info_sale);
  },
  methods: {
    hideModal() {
      this.$emit("input", null);
    },
    valSale() {
      let sum = 0;
      if (this.form.type == "contado") {
        for (const key in this.form.payments) {
          const element = this.form.payments[key];
          sum += element.amount != "" ? parseFloat(element.amount) : 0;
        }
        this.process = sum == this.info_sale.total_sale ? false : true;
      } else {
        this.process = false;
      }
    },
    selectCash() {
      this.form.payments.cash.amount = this.info_sale.total_sale;
      this.valSale();
    },
    processSale() {
      const self = this;
      this.form = Object.assign(this.form, this.info_sale);
      const i_remove = this.form.products.length - 1;
      this.form.products.splice(i_remove, 1);

      let i = 3;
      let doc = "";
      setTimeout(() => {
        $(".ajs-primary.ajs-buttons .btn-info")
          .attr("disabled", true)
          .html("Procesar en (" + i + ")");
        let interval = setInterval(() => {
          $(".ajs-primary.ajs-buttons .btn-info").html(
            "Procesar en (" + --i + ")"
          );
        }, 1000);
        setTimeout(() => {
          clearInterval(interval);
          $(".ajs-primary.ajs-buttons .btn-info")
            .html("Procesar")
            .attr("disabled", false)
            .focus();
        }, 3000);
      }, 100);
      if (this.form.emission_point.print == 1) {
        doc =
          this.form.emission_point.alias +
          " - " +
          this.form.emission_point.n_point.padStart(3, "0") +
          " - " +
          this.form.emission_point.sequential_numbering.padStart(9, "0");
      }

      let msg =
        this.form.emission_point.print == 1
          ? `Documento A Imprimirse: <span class="fw-600">${doc}</span>.
            </br>
            Verifique que el papel en la impresora sea el correcto.
          `
          : this.form.print == 1
          ? "Verifique que el papel en la impresora sea el correcto."
          : "Desea Procesar Venta?.";
      this.$alertify.confirm(
        msg,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/newSale/processSale", formData)
            .then((res) => {
              if (res.data.type == 1) {
                if (
                  this.form.print == 1 ||
                  this.form.emission_point.print == 1
                ) {
                  const type_print =
                    this.form.type == "contado"
                      ? "process-sale"
                      : "receipt-credit";
                  this.axios.defaults.baseURL = `http://${IP_ADRESS}/s26-printer`;
                  const printer = {
                    type_print,
                    print: this.form.print,
                    emission_point: this.form.emission_point,
                    info: this.info_sale,
                    info_estab,
                    credit: this.form.credit,
                  };
                  let formData = $s26.json_to_formData(printer);

                  this.axios
                    .post("/ticket.php", formData)
                    .then((res) => {
                      this.axios.defaults.baseURL = BASE_URL;
                      setTimeout(() => {
                        self.onReset();
                        this.$emit("input", null);
                        this.$emit("success");
                      }, 100);
                      if (res.data.type == 1) {
                        this.$alertify.success(res.data.msg);
                      } else {
                        this.$alertify.error(res.data.msg);
                      }
                    })
                    .catch((e) => console.log(e));
                } else {
                  self.onReset();
                  this.$emit("input", null);
                  this.$emit("success");
                }

                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      this.form = def_form();
    },
  },
};
</script>
<style scoped>
.variants {
  height: 38px;
  overflow: hidden;
  box-shadow: 0 10px 5px -6px rgb(93 130 170 / 21%) !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.25rem !important;
  margin-bottom: 1rem !important;
  padding-top: 0.5rem !important;
  padding-bottom: 0.5rem !important;
}
</style>