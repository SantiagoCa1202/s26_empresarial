<template>
  <s26-modal-multiple
    id="readSaleCredit"
    title="Información de Crédito"
    :levels="levels"
    body_style="min-height: 260px"
    @hideModal="hideModal"
    readOnly
    size="lg"
    update
    @update="infoData(id)"
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
          :link="'customers,' + form.customer_billing.id"
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
        <s26-textarea-read label="Observaciones" :content="form.note" rows="3">
        </s26-textarea-read>
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
            <td
              colspan="2"
              class="length-description"
              :title="
                pro.name +
                ' - ' +
                pro.model +
                ' - ' +
                pro.trademark +
                ' - ' +
                pro.sku
              "
            >
              <a
                href="#"
                class="btn btn-link p-0"
                @click.prevent="$s26.getInfoRow(pro.product_id, 'products')"
              >
                ( {{ pro.id }} ) {{ pro.name }} - {{ pro.model }} -
                {{ pro.trademark }} -
                {{ pro.sku }}
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
      <!-- INFORMACIÒN DE PAGO -->
      <transition name="slide-fade">
        <s26-modal
          id="readPayment"
          @hideModal="payment = { id: null }"
          footer_none
          body_class="h-auto"
          v-if="payment.id > 0"
        >
          <template v-slot:header>
            <h5 class="modal-title">{{ payment.payment_method }}</h5>
          </template>
          <template v-slot:body>
            <div class="col-12">
              <s26-input-read :content="payment.amount" money> </s26-input-read>
            </div>
            <template v-if="payment.payment_method_id > 1">
              <div class="row">
                <div class="col-6">
                  <s26-input-read
                    label="Cuenta Bancaria"
                    :content="payment.bank_account"
                  >
                  </s26-input-read>
                </div>
                <div class="col-6" v-if="payment.payment_method_id == 5">
                  <s26-input-read
                    label="Transacción"
                    :content="payment.transaction"
                  >
                  </s26-input-read>
                </div>
                <div
                  class="col-6"
                  v-if="
                    payment.payment_method_id == 5 &&
                    payment.transaction == 'diferido'
                  "
                >
                  <s26-input-read label="Cuotas" :content="payment.share">
                  </s26-input-read>
                </div>
                <div class="col-6" v-if="payment.payment_method_id == 7">
                  <s26-input-read label="N° Cheque" :content="payment.n_check">
                  </s26-input-read>
                </div>
                <div class="col-4" v-if="payment.payment_method_id == 7">
                  <s26-input-read
                    label="Entidad Bancaria"
                    :content="payment.bank_entity"
                  >
                  </s26-input-read>
                </div>
                <div class="col-4" v-if="payment.payment_method_id == 7">
                  <s26-input-read
                    label="Fecha"
                    :content="$s26.formatDate(payment.date)"
                  >
                  </s26-input-read>
                </div>
                <div class="col">
                  <s26-input-read
                    label="Estado"
                    :content="payment.status == 1 ? 'Pagado' : 'Pendiente'"
                    :variant_input="payment.status == 1 ? '' : 'text-danger'"
                  >
                  </s26-input-read>
                </div>
              </div>
            </template>
          </template>
        </s26-modal>
      </transition>
    </template>
    <template v-slot:aditional-btns>
      <button
        type="button"
        class="btn btn-outline-success float-end mx-1"
        @click="printReceipt"
      >
        Imp. Recibo
      </button>
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
        customer: {},
        customer_billing: {},
        document: {},
        products: [],
        products_series: [],
        payments: [],
        products_totals: 0,
        payments_totals: 0,
        balance: 0,
      },
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
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/salesCredits/getSaleCredit/" + id)
        .then((res) => {
          this.form = res.data;
          this.form.products_totals = this.form.products.reduce(
            (a, b) => a + (parseFloat(b.amount * b.pvp - b.discount) || 0),
            0
          );
          this.form.payments_totals = this.form.payments.reduce(
            (a, b) => a + (parseFloat(b.amount) || 0),
            0
          );

          this.form.balance =
            this.form.products_totals - this.form.payments_totals;
        })
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "salesCredits");
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
  },
};
</script>