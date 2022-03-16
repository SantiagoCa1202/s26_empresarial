<template>
  <s26-modal-multiple
    id="formBuys"
    :title="(id == 0 ? 'Nueva ' : 'Editar ') + 'Compra'"
    :levels="levels"
    body_style="min-height: 375px;"
    size="lg"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-3">
        <s26-select-provider
          id="form-provider"
          v-model="form.provider_id"
          @change="getProvider(form.provider_id)"
        ></s26-select-provider>
      </div>
      <div class="col-12 col-sm-3">
        <s26-form-input
          label="Ruc"
          size="sm"
          id="form-document"
          type="text"
          v-model="form.document"
          strictlength="13"
          number
          length
          s26_required
          autofocus
          :disabled="form.provider_id > 0 ? true : false"
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Razón Social"
          size="sm"
          id="form-business_name"
          type="text"
          v-model="form.business_name"
          maxlength="100"
          minlength="5"
          s26_required
          :disabled="form.provider_id > 0 ? true : false"
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-7">
        <s26-form-input
          label="Descripción"
          size="sm"
          id="form-description"
          type="text"
          v-model="form.description"
          maxlength="100"
          minlength="5"
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-5">
        <s26-input-document
          label="N° de Documento"
          size="sm"
          v-model="form.n_document"
          s26_required
          length
        >
        </s26-input-document>
      </div>
      <div class="col-sm-4">
        <s26-select-type-document
          id="form-type_doc_id"
          v-model="form.type_doc_id"
          s26_required
          type="buy"
        ></s26-select-type-document>
      </div>
      <div class="col-sm-4">
        <s26-select-payment-method
          id="form-payment_method_id"
          v-model="form.payment_method_id"
          s26_required
        ></s26-select-payment-method>
      </div>
      <div class="col-sm-4">
        <s26-date-picker
          id="form-date_issue"
          enable="unique"
          size="sm"
          v-model="form.date_issue"
          @change="form.counted_date = form.date_issue"
          label="Fecha"
          s26_required
          select_all_dates
          today
        ></s26-date-picker>
      </div>
      <div class="col-4" v-if="permit_establishment">
        <s26-select-establishment
          id="form-establishment"
          v-model="form.establishment_id"
          s26_required
        >
        </s26-select-establishment>
      </div>
      <div class="col">
        <s26-form-input
          label="N° de Autorización"
          size="sm"
          id="form-n_authorization"
          type="text"
          v-model="form.n_authorization"
          number
        >
        </s26-form-input>
      </div>
      <div class="col">
        <s26-select-file
          id="form-file"
          v-model="form.file_id"
        ></s26-select-file>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6 row mx-auto">
        <div class="col-sm-12">
          <s26-form-input
            label="Rise"
            size="sm"
            id="form-rise"
            type="text"
            v-model="form.rise"
            money
            placeholder="000.00"
            @keyup="total"
          >
          </s26-form-input>
        </div>
        <div class="col-sm-12">
          <s26-form-input
            label="SubTotal 0%"
            size="sm"
            id="form-bi_0"
            type="text"
            v-model="form.bi_0"
            money
            placeholder="000.00"
            @keyup="total"
          >
          </s26-form-input>
        </div>
        <div class="col-sm-12">
          <s26-form-input
            label="SubTotal 12%"
            size="sm"
            id="form-bi_"
            type="text"
            v-model="form.bi_"
            money
            placeholder="000.00"
            @keyup="total"
          >
          </s26-form-input>
        </div>
        <div class="col-sm-6">
          <s26-input-read
            label="Iva"
            :content="form.iva"
            money
          ></s26-input-read>
        </div>
        <div class="col-sm-6">
          <s26-input-read label="Total" :content="form.total" money>
          </s26-input-read>
        </div>
      </div>
    </template>
    <template v-slot:level-2>
      <div class="col-12 s26-align-center mb-3">
        <button
          type="button"
          :class="[
            'btn btn-sm mx-1',
            form.payment_type == 1 ? 'btn-primary' : 'btn-outline-primary',
          ]"
          @click="form.payment_type = 1"
        >
          Contado
        </button>
        <button
          type="button"
          :class="[
            'btn btn-sm mx-1',
            form.payment_type == 2 ? 'btn-primary' : 'btn-outline-primary',
          ]"
          @click="form.payment_type = 2"
        >
          Crédito
        </button>
      </div>
      <transition-group name="fade" mode="out-in">
        <div
          class="col-12 position-absolute start-0"
          v-if="form.payment_type == 1"
          key="counted"
        >
          <div class="row col-6 mx-auto">
            <div class="col-sm-12">
              <s26-date-picker
                id="form-date_payment"
                enable="unique"
                size="sm"
                v-model="form.counted_date"
                label="Fecha"
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-sm-6">
              <s26-form-input
                label="Total / Importe"
                size="sm"
                id="form-total_import"
                type="text"
                v-model="form.total"
                money
              >
              </s26-form-input>
            </div>
            <div class="col-sm-6">
              <s26-form-input
                label="Nota de Crédito"
                size="sm"
                id="form-credit_note"
                type="text"
                v-model="form.credit_note"
                money
              >
              </s26-form-input>
            </div>
            <div class="col-sm-12">
              <s26-select-payment-method
                id="form-payment_method_counted"
                v-model="form.payment_method_counted"
              ></s26-select-payment-method>
            </div>

            <transition name="fade">
              <div class="col-sm-6">
                <s26-form-input
                  label="N° de Transacción"
                  size="sm"
                  id="form-n_transaction"
                  type="text"
                  v-model="form.n_transaction"
                  v-if="
                    form.payment_method_counted > 1 &&
                    form.payment_method_counted < 6
                  "
                  number
                >
                </s26-form-input>
              </div>
            </transition>
            <transition name="fade">
              <div
                class="col-sm-6"
                v-if="
                  form.payment_method_counted > 1 &&
                  form.payment_method_counted < 6
                "
              >
                <s26-select-bank
                  label="Entidad Bancaria"
                  size="sm"
                  id="form-bank_entity_id"
                  v-model="form.bank_entity_id"
                >
                </s26-select-bank>
              </div>
            </transition>
            <transition name="fade">
              <div class="col-12" v-if="form.payment_method_counted == 7">
                <s26-select-check
                  label="Cheque"
                  size="sm"
                  id="form-check_id"
                  v-model="form.check_id"
                ></s26-select-check>
              </div>
            </transition>
          </div>
        </div>
        <div
          class="col-12 position-absolute start-0"
          v-if="form.payment_type == 2"
          key="credit"
        >
          <div class="row col-6 mx-auto">
            <div class="col-sm-6">
              <s26-form-input
                label="Total / Importe"
                size="sm"
                id="form-total_import"
                type="text"
                v-model="form.total"
                money
              >
              </s26-form-input>
            </div>
            <div class="col-sm-6">
              <s26-form-input
                label="Nota de Crédito"
                size="sm"
                id="form-credit_note"
                type="text"
                v-model="form.credit_note"
                money
              >
              </s26-form-input>
            </div>
            <div class="col-12">
              <s26-date-picker
                id="form-credit_date"
                enable="multiple"
                size="sm"
                v-model="form.credit_date"
                label="Fechas de Pago"
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-12">
              <table class="w-100">
                <thead>
                  <tr>
                    <th>Fecha De Pago</th>
                    <th class="text-center">Importe Aprox.</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="date in form.credit_date" :key="date">
                    <td>{{ $s26.formatDate(date) }}</td>
                    <td class="text-center">
                      <s26-icon icon="dollar-sign"></s26-icon>
                      {{
                        $s26.currency(
                          (form.total_import - form.credit_note) /
                            form.credit_date.length
                        )
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </transition-group>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    provider_id: "",
    document: "",
    business_name: "",
    description: "",
    n_document: "",
    type_doc_id: "",
    payment_method_id: "",
    date_issue: "",
    establishment_id: "",
    n_authorization: "",
    file_id: "",
    created_at: "",
    rise: "",
    bi_0: "",
    bi_: "",
    iva: "",
    total: "",
    total_import: "",
    payment_type: 1,
    credit_note: "",
    payment_method_counted: "",
    bank_entity_id: "",
    check_id: "",
    n_transaction: "",
    counted_date: "",
    credit_date: [],
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
      permit_establishment: $permit_establishment,
      form: def_form(),
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  computed: {
    levels: function () {
      return this.id !== 0 && this.id !== null
        ? ["Información de Comprobante", "Totales"]
        : ["Información de Comprobante", "Totales", "Plazo"];
    },
  },
  methods: {
    infoData(id) {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      this.axios
        .get("/buys/getBuy/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      if (this.form.total == "" || this.form.total == "0.00") {
        this.$alertify.error("Es necesario ingresar un total correcto");
        this.$alertify.error(
          "Total no puede estar vacio o no puede ser igual a 0.00 "
        );
        $("#form-rise, #form-bi_0, #form-bi_").addClass("is-invalid");
        return;
      } else if (this.form.payment_type < 1 || this.form.payment_type > 2) {
        this.$alertify.error("Es necesario elegir un tipo de pago");
        this.$alertify.error("Contado o Crédito");
        return;
      } else if (
        this.form.total_import == "" ||
        this.form.total_import == "0.00"
      ) {
        this.$alertify.error("Es necesario ingresar un Importe correcto");
        this.$alertify.error(
          "Importe no puede estar vacio o no puede ser igual a 0.00 "
        );
        $("#form-total_import").addClass("is-invalid");
        return;
      } else if (
        this.form.payment_type == 1 &&
        this.form.payment_method_counted == 0
      ) {
        this.$alertify.error("Es necesario elegir una Forma de Pago");
        $("#form-payment_method_counted").addClass("is-invalid").focus();
        return;
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Compra?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/buys/setBuy", formData)
            .then((res) => {
              for (let i in res.data) {
                if (res.data[i].type == 1 || res.data[i].type == 2) {
                  this.onReset();
                  this.$alertify.success(res.data[i].msg);
                } else if (res.data[i].type == 3) {
                  this.$alertify.warning(res.data[i].msg);
                } else {
                  this.$alertify.error(res.data[i].msg);
                }
              }
              if (this.id > 0) {
                this.$alertify.warning(
                  `Recuerda Aztualizar los datos en 
                  <a href="${BASE_URL}/debtsToPay" target="_BLANK" class="btn btn-link"> Cuentas Por Pagar </a>`
                );
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    getProvider(id) {
      if (id > 0) {
        this.axios
          .get("/providers/getProvider/" + id)
          .then((res) => {
            this.form.document = res.data.document;
            this.form.business_name = res.data.business_name;
          })
          .catch((err) => console.log(err));
      }
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      this.form.payment_type = 1;
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    total() {
      const _iva__ = 1.12;

      let iva =
        this.form.bi_ != "" ? this.form.bi_ * _iva__ - this.form.bi_ : 0;

      this.form.iva = parseFloat(iva).toFixed(2);
      this.form.total = (
        (this.form.rise != "" ? parseFloat(this.form.rise) : 0) +
        (this.form.bi_0 != "" ? parseFloat(this.form.bi_0) : 0) +
        (this.form.bi_ != "" ? parseFloat(this.form.bi_) : 0) +
        parseFloat(iva)
      ).toFixed(2);
      this.form.total_import = this.form.total;
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>