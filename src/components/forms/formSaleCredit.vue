<template>
  <s26-modal-multiple
    id="formSaleCredit"
    title="Editar de Crédito"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    size="lg"
    @onReset="onReset"
    @onSubmit="onSubmit"
  >
    <template v-slot:level-0>
      <div class="col-sm-2">
        <s26-input-read
          label="N° de Crédito"
          :content="form.id"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Fecha de Emi."
          title="Fecha de Emisión"
          :content="$s26.formatDate(form.date, 'sm2')"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Fecha de Fac."
          title="Fecha de Facturación"
          :content="$s26.formatDate(form.billing_date, 'sm2')"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="CI / RUC"
          :content="form.customer.document"
          :link="'customers,' + form.customer.id"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Cliente"
          :content="form.customer.short_name"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="CI / RUC FAC."
          :content="form.customer_billing.document"
          :link="'customers,' + form.customer.id"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Cliente FAC."
          :content="form.customer_billing.short_name"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Documento"
          :content="form.document.name"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Nº de Documento"
          :content="form.n_document"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Productos"
          :content="form.total_products"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Subtotal"
          :content="form.total_pvp"
          money
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Descuento"
          :content="form.total_discount"
          money
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Total"
          :content="form.total_sale"
          money
          :variant_input="form.balance == 0 ? '' : 'is-invalid'"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Saldo"
          :content="form.balance"
          money
          :variant_input="form.balance == 0 ? '' : 'is-invalid'"
        ></s26-input-read>
      </div>
      <div class="col-sm-12">
        <s26-textarea
          id="form-note"
          label="Observaciones"
          rows="3"
          v-model="form.note"
        >
        </s26-textarea>
      </div>
      <div class="col-sm-12">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
        <span
          :class="[
            'float-end fw-bold',
            form.status == 1 && form.balance == 0 && form.payments_status == 1
              ? 'text-success'
              : 'text-danger',
          ]"
        >
          {{
            form.payments_status == 0
              ? "Existen Pagos Pendientes"
              : form.products_totals !== form.payments_totals
              ? "Crédito Incompleto"
              : form.status != 1
              ? "Crédito Anulado"
              : "Crédito Procesado"
          }}
        </span>
      </div>
    </template>
    <template v-slot:level-1>
      <s26-table
        :rows="form.products.length"
        :fields="fields_products"
        relative
        height="auto"
      >
        <template v-slot:body>
          <tr v-for="pro in form.products" :key="pro.id">
            <td colspan="2" class="length-description" :title="pro.product">
              <a
                href="#"
                class="btn btn-link p-0"
                @click.prevent="$s26.getInfoRow(pro.product_id, 'products')"
              >
                ( {{ pro.id }} ) {{ pro.product }}
              </a>
            </td>
            <td class="length-status text-center">{{ pro.amount }}</td>
            <td class="length-status text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(pro.pvp) }}
            </td>

            <td class="length-status text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(pro.discount) }}
            </td>
            <td class="length-status text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(pro.amount * pro.pvp - pro.discount) }}
            </td>
          </tr>
        </template>
        <template v-slot:foot>
          <tr :class="form.balance == 0 ? '' : 'text-danger'">
            <td colspan="2" class="fw-bold">Totales:</td>
            <td class="length-status text-center fw-bold">
              {{
                form.products.reduce((a, b) => a + (parseInt(b.amount) || 0), 0)
              }}
            </td>
            <td class="length-status text-center fw-bold">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{
                $s26.currency(
                  form.products.reduce(
                    (a, b) => a + (parseFloat(b.pvp) || 0),
                    0
                  )
                )
              }}
            </td>
            <td class="length-status text-center fw-bold">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{
                $s26.currency(
                  form.products.reduce(
                    (a, b) => a + (parseFloat(b.discount) || 0),
                    0
                  )
                )
              }}
            </td>
            <td class="length-status text-center fw-bold">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(form.products_totals) }}
            </td>
          </tr>
        </template>
      </s26-table>
    </template>
    <template v-slot:level-2>
      <div class="col-12">
        <div class="row" v-if="form.products_series.length > 0">
          <div
            class="col-6 px-0"
            v-for="serie in form.products_series"
            :key="serie.id"
          >
            <div
              class="
                s26-align-center
                btn btn-outline-primary btn-sm
                m-2
                text-break
              "
            >
              {{ serie.serie }}
            </div>
          </div>
        </div>
        <div v-else class="s26-align-center text-secondary fw-bold">
          No existen series en este Crédito.
        </div>
      </div>
    </template>
    <template v-slot:level-3>
      <s26-table
        :rows="form.payments.length"
        :fields="fields_payments"
        relative
        height="auto"
      >
        <template v-slot:body>
          <tr
            v-for="pay in form.payments"
            :key="pay.id"
            :class="[
              pay.payment_method_id > 1 &&
              (pay.bank_account_id == 0 || pay.bank_account_id == null)
                ? 'tr-warning'
                : '',
            ]"
            @dblclick="payment = pay"
          >
            <td class="length-status">
              {{ $s26.formatDate(pay.date, "sm2") }}
            </td>
            <td class="length-int">
              {{ pay.payment_method }}
            </td>
            <td class="length-status text-center">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(pay.amount) }}
            </td>
            <td class="length-description text-center">
              <a
                href="#"
                class="btn btn-link p-0"
                @click.prevent="$s26.getInfoRow(pay.bank_account_id, 'bankAccounts')"
              >
                {{ pay.bank_account }}
              </a>
            </td>
            <td
              :class="[
                'length-status text-center',
                pay.status == 1 ? 'text-success' : 'text-danger',
              ]"
            >
              {{ pay.status == 1 ? "Pagado" : "Pendiente" }}
            </td>
          </tr>
        </template>
        <template v-slot:foot>
          <tr :class="form.balance == 0 ? '' : 'text-danger'">
            <td class="fw-bold">Totales:</td>
            <td class="length-status text-center fw-bold">
              <s26-icon icon="dollar-sign"></s26-icon>
              {{ $s26.currency(form.payments_totals) }}
            </td>
          </tr>
        </template>
      </s26-table>
    </template>
    <template v-slot:aditional-btns>
      <button
        type="button"
        class="btn btn-outline-primary float-end mx-1"
        @click="action = 'pay_credit'"
        v-if="form.balance > 0"
      >
        Abonar
      </button>
      <button
        type="button"
        class="btn btn-outline-success float-end mx-1"
        @click="printReceipt"
      >
        Imp. Recibo
      </button>
      <button
        type="button"
        class="btn btn-danger float-end mx-1"
        @click="$emit('cancel_sale', form)"
      >
        Anular
      </button>
      <button
        type="button"
        class="btn btn-outline-primary float-end mx-1"
        @click="action = 'process_credit'"
        v-if="form.balance == 0"
      >
        Facturar
      </button>
    </template>
    <template v-slot:subModal>
      <!-- INFORMACIÒN DE PAGO -->
      <transition name="slide-fade">
        <s26-modal
          id="readPayment"
          @hideModal="hideModalPayment"
          body_class="h-auto"
          v-if="payment.id > 0"
        >
          <template v-slot:header>
            <h5 class="modal-title">{{ payment.payment_method }}</h5>
          </template>
          <template v-slot:body>
            <div class="row">
              <div class="col-sm-4">
                <s26-select-payment-method
                  id="form-payment_method_id"
                  v-model="payment.payment_method_id"
                  s26_required
                ></s26-select-payment-method>
              </div>
              <div class="col-4">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="payment.amount"
                  money
                  s26-required
                >
                </s26-form-input>
              </div>
              <div class="col-4" v-if="payment.payment_method_id >= 4">
                <s26-select-status
                  label="Estado"
                  v-model="payment.status"
                  :options="['pagado', 'pendiente']"
                ></s26-select-status>
              </div>
              <div class="col-sm-6" v-if="payment.payment_method_id > 1">
                <s26-select-bank-account
                  label="Cuenta Bancaria"
                  size="sm"
                  id="form-bank_account_id"
                  v-model="payment.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
              <template v-if="payment.payment_method_id == 5">
                <div class="col-6 mb-3">
                  <label class="form-label"> Transacción </label>
                  <select
                    class="form-select form-select-sm"
                    v-model="payment.transaction"
                  >
                    <option value="corriente">Corriente</option>
                    <option value="diferido">Diferido</option>
                  </select>
                </div>
                <div
                  class="col-sm-6 mb-3"
                  v-show="payment.transaction == 'diferido'"
                >
                  <label class="form-label"> Cuotas </label>
                  <select
                    class="form-select form-select-sm"
                    v-model="payment.share"
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
              <template v-if="payment.payment_method_id == 7">
                <div class="col-6">
                  <s26-form-input
                    label="N° de Cheque"
                    type="number"
                    v-model="payment.n_check"
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-select-bank
                    size="sm"
                    id="form-payment-bank_entity_id-check"
                    v-model="payment.bank_entity_id"
                  >
                  </s26-select-bank>
                </div>
                <div class="col-6">
                  <s26-date-picker
                    id="form-payment-date_check"
                    enable="unique"
                    size="sm"
                    v-model="payment.date_check"
                    label="Fecha"
                    s26_required
                    select_all_dates
                    today
                  ></s26-date-picker>
                </div>
              </template>
            </div>
          </template>
          <template v-slot:footer>
            <button
              type="button"
              class="btn btn-primary"
              @click="updatePayment"
            >
              Guardar
            </button>
          </template>
        </s26-modal>
      </transition>
      <!-- ABONAR CRÉDITO -->
      <transition name="slide-fade">
        <s26-modal
          id="pay_credit"
          @hideModal="action = null"
          body_class="h-auto"
          v-if="action == 'pay_credit' && form.balance > 0"
        >
          <template v-slot:header>
            <h5 class="modal-title">Registro de Abono</h5>
          </template>
          <template v-slot:body>
            <div class="row">
              <div class="col-sm-4">
                <s26-select-payment-method
                  id="form-payment_method_id"
                  v-model="credit.payment_method_id"
                  s26_required
                ></s26-select-payment-method>
              </div>
              <div class="col-4">
                <s26-form-input
                  label="Monto"
                  type="number"
                  v-model="credit.amount"
                  money
                  s26-required
                >
                </s26-form-input>
              </div>
              <div class="col-4">
                <s26-input-read
                  label="Saldo"
                  :content="$s26.currency(form.balance - credit.amount)"
                >
                </s26-input-read>
              </div>
              <div class="col-sm-6" v-if="credit.payment_method_id > 1">
                <s26-select-bank-account
                  label="Cuenta Bancaria"
                  size="sm"
                  id="form-bank_account_id"
                  v-model="credit.bank_account_id"
                  s26_required
                >
                </s26-select-bank-account>
              </div>
              <template v-if="credit.payment_method_id == 5">
                <div class="col-6 mb-3">
                  <label class="form-label"> Transacción </label>
                  <select
                    class="form-select form-select-sm"
                    v-model="credit.transaction"
                  >
                    <option value="corriente">Corriente</option>
                    <option value="diferido">Diferido</option>
                  </select>
                </div>
                <div
                  class="col-sm-6 mb-3"
                  v-show="credit.transaction == 'diferido'"
                >
                  <label class="form-label"> Cuotas </label>
                  <select
                    class="form-select form-select-sm"
                    v-model="credit.share"
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
              <template v-if="credit.payment_method_id == 7">
                <div class="col-6">
                  <s26-form-input
                    label="N° de Cheque"
                    type="number"
                    v-model="credit.n_check"
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-select-bank
                    size="sm"
                    id="form-credit-bank_entity_id-check"
                    v-model="credit.bank_entity_id"
                  >
                  </s26-select-bank>
                </div>
                <div class="col-6">
                  <s26-date-picker
                    id="form-credit-date_check"
                    enable="unique"
                    size="sm"
                    v-model="credit.date_check"
                    label="Fecha"
                    s26_required
                    select_all_dates
                    today
                  ></s26-date-picker>
                </div>
              </template>
            </div>
          </template>
          <template v-slot:footer>
            <button
              type="button"
              class="btn btn-primary w-100"
              @click="pay_credit"
            >
              Abonar
            </button>
          </template>
        </s26-modal>
      </transition>
      <!-- FACTURAR CRÉDITO -->
      <transition name="slide-fade">
        <s26-modal
          id="process_credit"
          @hideModal="action = null"
          body_class="h-auto"
          v-if="action == 'process_credit' && form.balance == 0"
        >
          <template v-slot:header>
            <h5 class="modal-title">Facturar Crédito</h5>
          </template>
          <template v-slot:body>
            <div class="row">
              <div class="col-12">
                <s26-input-read
                  label="Venta Total"
                  :content="form.payments_totals"
                  money
                >
                </s26-input-read>
              </div>
              <div class="col-12">
                <s26-select-customer
                  id="form-customer_billing"
                  v-model="form.customer_billing"
                  s26_required
                  all_info
                  label="Cliente a Facturar"
                ></s26-select-customer>
              </div>
              <div class="col-12">
                <s26-select-emission-point
                  id="form-emission_point"
                  v-model="form.emission_point"
                  s26_required
                  type="buy"
                  all_info
                ></s26-select-emission-point>
              </div>
              <div class="col-12 mb-3" v-show="form.emission_point == 0">
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="form.print"
                    id="form-print"
                  />
                  <label class="form-check-label" for="form-print">
                    Imprimir.
                  </label>
                </div>
              </div>
            </div>
          </template>
          <template v-slot:footer>
            <button
              type="button"
              class="btn btn-primary w-100"
              @click="process_credit"
            >
              Facturar
            </button>
          </template>
        </s26-modal>
      </transition>
    </template>
    {{ calcBalance }}
  </s26-modal-multiple>
</template>
<script>
const def_credit = () => {
  return {
    payment_method_id: 1,
    amount: 0,
    bank_account_id: 0,
    transaction: "corriente",
    share: "",
    n_check: "",
    bank_entity_id: 0,
    date_check: "",
  };
};
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
        customer: {},
        customer_billing: {},
        document: {},
        products: [],
        products_series: [],
        payments: [],
        products_totals: 0,
        payments_totals: 0,
        balance: 0,
        emission_point: 0,
        print: 0,
      },
      credit: def_credit(),

      levels: [
        "Información de Crédito",
        "Productos",
        "Series",
        "Informacíon de Pago",
      ],
      fields_products: [
        {
          name: "Producto",
          class: "length-description",
        },
        {
          name: "",
          class: "length-description",
        },
        {
          name: "Cant.",
          class: "length-status text-center",
        },
        {
          name: "Pvp.",
          class: "length-status text-center",
        },

        {
          name: "Desc.",
          class: "length-status text-center",
        },
        {
          name: "Total",
          class: "length-status text-center",
        },
      ],
      fields_payments: [
        {
          name: "Fecha",
          class: "length-status",
        },
        {
          name: "Forma de Pago",
          class: "length-int",
        },
        {
          name: "Importe",
          class: "length-status text-center",
        },
        {
          name: "Cuenta Bancaria",
          class: "length-description text-center",
        },
        {
          name: "Estado",
          class: "length-status text-center",
        },
      ],
      payment: { id: 0 },

      action: null,
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  computed: {
    calcBalance: function () {
      this.form.products_totals = this.form.products.reduce(
        (a, b) => a + (parseFloat(b.amount * b.pvp - b.discount) || 0),
        0
      );

      this.form.payments_totals = this.form.payments.reduce(
        (a, b) => a + (parseFloat(b.amount) || 0),
        0
      );

      this.form.balance = this.form.products_totals - this.form.payments_totals;
    },
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/salesCredits/getSaleCredit/" + id)
        .then((res) => {
          this.form = Object.assign({}, this.form, res.data);
          this.form.document_id > 1 ? this.hideModal() : "";
        })
        .catch((err) => console.log(err));
    },

    onReset() {
      this.infoData(this.id);
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },

    onSubmit() {
      this.$alertify.confirm(
        `Desea Actualizar Crédito?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/salesCredits/setSale", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
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

    pay_credit() {
      this.credit.sale_id = this.id;

      if (this.credit.amount <= this.form.balance && this.credit.amount > 0) {
        this.$alertify.confirm(
          `Desea Realizar Abono?.`,
          () => {
            let formData = $s26.json_to_formData(this.credit);
            $s26.show_loader_points();
            this.axios
              .post("/salesCredits/payCredit", formData)
              .then((res) => {
                if (res.data.type >= 1) {
                  this.onReset();
                  this.$emit("update");
                  this.action = null;
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Acción Cancelada")
        );
      } else {
        this.$alertify.error(
          "El abono debe ser mayor a 0.00 y no puede ser mayor al saldo actual"
        );
      }
    },

    printReceipt() {
      this.$alertify.confirm(
        `Desea Imprimir Recibo?.`,
        () => {
          this.axios.defaults.baseURL = `http://${IP_ADRESS}/s26-printer`;

          const printer = {
            type_print: "receipt-credit",
            info: this.form,
            info_estab,
            credit: this.form.payments,
          };
          let formData = $s26.json_to_formData(printer);

          this.axios
            .post("/ticket.php", formData)
            .then((res) => {
              this.axios.defaults.baseURL = BASE_URL;
              if (res.data.type == 1) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
            })
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Impresión Cancelada")
      );
    },

    updatePayment() {
      if (this.form.balance >= 0 && this.payment.amount > 0) {
        this.$alertify.confirm(
          `Desea Actualizar Pago?.`,
          () => {
            let formData = $s26.json_to_formData(this.payment);
            $s26.show_loader_points();
            this.axios
              .post("/salesCredits/setPayment", formData)
              .then((res) => {
                if (res.data.type >= 1) {
                  this.onReset();
                  this.$emit("update");
                  this.payment.id = null;
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Acción Cancelada")
        );
      } else {
        this.$alertify.error(
          "El pago debe ser mayor a 0.00 y no puede ser mayor al saldo actual"
        );
      }
    },

    process_credit() {
      const self = this;
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
            .post("/salesCredits/processCredit", formData)
            .then((res) => {
              if (res.data.type == 1) {
                if (
                  this.form.print == 1 ||
                  this.form.emission_point.print == 1
                ) {
                  this.axios.defaults.baseURL = `http://${IP_ADRESS}/s26-printer`;
                  const printer = {
                    type_print: "process-sale",
                    print: this.form.print,
                    emission_point: this.form.emission_point,
                    info: this.form,
                    info_estab,
                  };
                  let formData = $s26.json_to_formData(printer);
                  this.axios
                    .post("/ticket.php", formData)
                    .then((res) => {
                      this.axios.defaults.baseURL = BASE_URL;
                      setTimeout(() => {
                        self.onReset();
                        this.$emit("input", null);
                        this.action = null;
                        this.$emit("update");
                      }, 200);
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
                  this.action = null;
                  this.$emit("update");
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

    hideModal() {
      this.$emit("input", null);
    },

    hideModalPayment() {
      this.payment = { id: null };
      this.onReset();
    },
  },
};
</script>