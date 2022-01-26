<template>
  <s26-modal-multiple
    id="formSale"
    title="Editar Venta"
    :levels="levels"
    body_style="min-height: 375px;"
    size="lg"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-2">
        <s26-input-read label="N° de Venta" :content="form.id"></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Fecha de Facturación"
          :content="$s26.formatDate(form.date, 'sm2')"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="CI / RUC"
          :content="form.customer.document"
          :link="'customers,' + form.customer.id"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Cliente"
          :content="form.customer.short_name"
        ></s26-input-read>
      </div>
      <div class="col-sm-5">
        <s26-input-read
          label="Documento"
          :content="form.document.name"
        ></s26-input-read>
      </div>
      <div class="col-sm-5">
        <s26-input-read
          label="Nº de Documento"
          :content="form.n_document"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read label="Caja" :content="form.box.name"></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Productos"
          :content="form.total_products"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Subtotal"
          :content="form.total_pvp"
          money
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
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
          :variant_input="
            form.products_totals == form.payments_totals ? '' : 'is-invalid'
          "
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
            form.status == 1 &&
            form.products_totals == form.payments_totals &&
            form.payments_status == 1
              ? 'text-success'
              : 'text-danger',
          ]"
        >
          {{
            form.payments_status == 0
              ? "Existen Pagos Pendientes"
              : form.products_totals !== form.payments_totals
              ? "Venta Descuadrada"
              : form.status != 1
              ? "Venta Anulada"
              : "Venta Procesada"
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
                class="btn btn-link px-0"
                @click.prevent="getInfo(pro.product_id, 'products')"
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
          <tr
            :class="
              form.products_totals == form.payments_totals ? '' : 'text-danger'
            "
          >
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
          No existen series en esta venta.
        </div>
      </div>
    </template>
    <template v-slot:level-3>
      <div class="col-12 row mx-0">
        <div
          :class="[
            'col-12 variants row mx-0 user-select pointer',
            active_variants.cash ? 'h-auto' : '',
          ]"
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
          <div class="col-12">
            <s26-form-input
              label="Monto"
              type="number"
              v-model="form.payments[0].amount"
              money
            >
            </s26-form-input>
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
              v-model="form.payments[1].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-6">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-transfer"
              v-model="form.payments[1].bank_account_id"
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
              v-model="form.payments[2].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-6">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-deposit"
              v-model="form.payments[2].bank_account_id"
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
          <div class="col-4">
            <s26-form-input
              label="Monto"
              type="number"
              v-model="form.payments[3].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-4">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-debit"
              v-model="form.payments[3].bank_account_id"
            >
            </s26-select-bank-account>
          </div>
          <div class="col-4">
            <s26-select-status
              label="Estado"
              v-model="form.payments[3].status"
              :options="['pagado', 'pendiente']"
            ></s26-select-status>
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
          <div class="col-4">
            <s26-form-input
              label="Monto"
              type="number"
              v-model="form.payments[4].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-4">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-credit"
              v-model="form.payments[4].bank_account_id"
            >
            </s26-select-bank-account>
          </div>
          <div class="col-4">
            <s26-select-status
              label="Estado"
              v-model="form.payments[4].status"
              :options="['pagado', 'pendiente']"
            ></s26-select-status>
          </div>
          <div class="col mb-3">
            <label class="form-label"> Transacción </label>
            <select
              class="form-select form-select-sm"
              v-model="form.payments[4].transaction"
            >
              <option value="corriente">Corriente</option>
              <option value="diferido">Diferido</option>
            </select>
          </div>
          <div
            class="col-sm-6 mb-3"
            v-show="form.payments[4].transaction == 'diferido'"
          >
            <label class="form-label"> Cuotas </label>
            <select
              class="form-select form-select-sm"
              v-model="form.payments[4].share"
            >
              <optgroup label="Sin Intereses">
                <option value="3 sin intereses">3 meses sin intereses</option>
                <option value="6 sin intereses">6 meses sin intereses</option>
                <option value="9 sin intereses">9 meses sin intereses</option>
                <option value="12 sin intereses">12 meses sin intereses</option>
                <option value="15 sin intereses">15 meses sin intereses</option>
                <option value="18 sin intereses">18 meses sin intereses</option>
                <option value="21 sin intereses">21 meses sin intereses</option>
                <option value="24 sin intereses">24 meses sin intereses</option>
              </optgroup>
              <optgroup label="Con Intereses">
                <option value="3 con intereses">3 meses con intereses</option>
                <option value="6 con intereses">6 meses con intereses</option>
                <option value="9 con intereses">9 meses con intereses</option>
                <option value="12 con intereses">12 meses con intereses</option>
                <option value="15 con intereses">15 meses con intereses</option>
                <option value="18 con intereses">18 meses con intereses</option>
                <option value="21 con intereses">21 meses con intereses</option>
                <option value="24 con intereses">24 meses con intereses</option>
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
          <div class="col-4">
            <s26-form-input
              label="Monto"
              type="number"
              v-model="form.payments[5].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-4">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-gift"
              v-model="form.payments[5].bank_account_id"
            >
            </s26-select-bank-account>
          </div>
          <div class="col-4">
            <s26-select-status
              label="Estado"
              v-model="form.payments[5].status"
              :options="['pagado', 'pendiente']"
            ></s26-select-status>
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
          <div class="col-4">
            <s26-form-input
              label="Monto"
              type="number"
              v-model="form.payments[6].amount"
              money
            >
            </s26-form-input>
          </div>
          <div class="col-sm-4">
            <s26-select-bank-account
              size="sm"
              id="form-bank_account_id-check"
              v-model="form.payments[6].bank_account_id"
            >
            </s26-select-bank-account>
          </div>
          <div class="col-4">
            <s26-select-status
              label="Estado"
              v-model="form.payments[6].status"
              :options="['pagado', 'pendiente']"
            ></s26-select-status>
          </div>
          <div class="col-2">
            <s26-form-input
              label="N°"
              type="number"
              v-model="form.payments[6].n_check"
              number
            >
            </s26-form-input>
          </div>
          <div class="col-5">
            <s26-select-bank
              size="sm"
              id="form-bank_entity_id-check"
              v-model="form.payments[6].bank_entity_id"
            >
            </s26-select-bank>
          </div>
          <div class="col-5">
            <s26-date-picker
              id="form-date"
              enable="unique"
              size="sm"
              v-model="form.payments[6].date"
              label="Fecha"
              select_all_dates
              today
            ></s26-date-picker>
          </div>
        </div>
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_payments = () => {
  return [
    {
      amount: 0,
      payment_method_id: 1,
    },
    {
      amount: 0,
      payment_method_id: 2,
      bank_account_id: "",
    },
    {
      amount: 0,
      payment_method_id: 3,
      bank_account_id: "",
    },
    {
      amount: 0,
      payment_method_id: 4,
      bank_account_id: "",
    },
    {
      amount: 0,
      payment_method_id: 5,
      bank_account_id: "",
      transaction: "corriente",
      share: "",
    },
    {
      amount: 0,
      payment_method_id: 6,
      bank_account_id: "",
    },
    {
      amount: 0,
      payment_method_id: 7,
      bank_account_id: "",
      bank_entity_id: "",
      n_check: "",
      date: "",
    },
  ];
};
const def_form = () => {
  return {
    customer: {},
    box: {},
    document: {},
    products: [],
    products_series: [],
    payments: def_payments(),
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
      form: def_form(),
      levels: [
        "Información de Venta",
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
          name: "Forma de Pago",
          class: "length-int",
        },
        {
          name: "Importe",
          class: "length-status text-center",
        },
        {
          name: "Estado",
          class: "length-status text-center",
        },
      ],
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
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      this.axios
        .get("/sales/getSale/" + id)
        .then((res) => {
          this.form = JSON.parse(JSON.stringify(res.data));

          this.form.products_totals = this.form.products.reduce(
            (a, b) => a + (parseFloat(b.amount * b.pvp - b.discount) || 0),
            0
          );
          this.form.payments_totals = this.form.payments.reduce(
            (a, b) => a + (parseFloat(b.amount) || 0),
            0
          );

          const payments = def_payments();
          this.form.payments = payments;
          for (let i = 0; i < payments.length; i++) {
            const payment = payments[i];
            let obj_payment = res.data.payments.find(
              (payment_back) =>
                payment_back.payment_method_id == payment.payment_method_id
            );

            if (obj_payment != undefined) {
              this.form.payments[i] = Object.assign(payment, obj_payment);
            }
          }
        })
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.products_totals = this.form.products.reduce(
        (a, b) => a + (parseFloat(b.amount * b.pvp - b.discount) || 0),
        0
      );
      this.form.payments_totals = this.form.payments.reduce(
        (a, b) => a + (parseFloat(b.amount) || 0),
        0
      );

      // if (products_totals == payments_totals) {
      this.$alertify.confirm(
        `Desea Actualizar Venta?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/sales/setSale", formData)
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
      // } else {
      //   this.$alertify.error(
      //     "Verifique que el total de la venta sea igual al importe de pago total."
      //   );
      //   return;
      // }
    },
    onReset() {
      this.infoData(this.id);
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
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