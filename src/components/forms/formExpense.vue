<template>
  <s26-modal-multiple
    id="formExpense"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Egreso'"
    :levels="levels"
    body_style="min-height: 400px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-form-input
          label="Razón Social"
          id="form-tradename"
          v-model="form.tradename"
          maxlength="100"
          text
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-editor
          id="form-description"
          label="Descripción"
          v-model="form.description"
          s26_required
          :height="value == 0 ? 275 : 235"
        ></s26-editor>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, 'xl') }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6">
        <s26-form-input
          label="Importe"
          id="form-amount"
          type="tel"
          v-model="form.amount"
          money
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-6">
        <s26-select-status
          label="Cuenta"
          id="form-account"
          v-model="form.account"
          :options="['Costo', 'Ganancia']"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-sm-6">
        <s26-date-picker
          id="form-date"
          enable="unique"
          size="sm"
          v-model="form.date"
          label="Fecha"
          s26_required
          select_all_dates
        ></s26-date-picker>
      </div>
      <div class="col-6">
        <s26-form-input
          label="N° de Documento"
          id="form.n_document"
          type="number"
          v-model="form.n_document"
          number
        >
        </s26-form-input>
      </div>
      <div class="col-6">
        <s26-select-payment-method
          id="form-payment_method_id"
          v-model="form.payment_method_id"
          s26_required
        ></s26-select-payment-method>
      </div>
      <div class="col-6">
        <s26-select-bank-account
          id="form-bank_account_id"
          v-model="form.bank_account_id"
          s26_required
          v-if="form.payment_method_id == 2 || form.payment_method_id > 3"
        >
        </s26-select-bank-account>
      </div>
      <div class="col-6" v-if="permit_establishment">
        <s26-select-establishment
          id="form-establishment"
          v-model="form.establishment_id"
          s26_required
        >
        </s26-select-establishment>
      </div>
      <div class="col-6">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    n_document: "",
    tradename: "",
    description: "",
    amount: "",
    account: "",
    date: "",
    bank_account_id: "",
    payment_method_id: "",
    establishment_id: "",
    status: "",
    created_at: "",
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
      permit_establishment: $permit_establishment,
      levels: ["Información de Egreso", "Información de Importe"],
    };
  },
  created() {
    const date = new Date();
    this.form.date =
      date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/expenses/getExpense/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Egreso?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/expenses/setExpense", formData)
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
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>