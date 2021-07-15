<template>
  <s26-modal-multiple
    id="formCheckBook"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Cheque'"
    :levels="levels"
    body_style="min-height: 310px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-6">
        <s26-select-bank-account
          size="sm"
          id="form-bank_account_id"
          v-model="form.bank_account_id"
          s26_required
          @change="id == 0 ? totalChecks() : ''"
          checkbook
        >
        </s26-select-bank-account>
      </div>
      <div class="col-6">
        <s26-input-read label="N° de Cheque" :content="form.n_check">
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Beneficiario"
          size="sm"
          id="form-beneficiary"
          type="text"
          v-model="form.beneficiary"
          maxlength="100"
          minlength="5"
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-textarea
          id="form-reason"
          label="Motivo / Descripción"
          rows="2"
          v-model="form.reason"
          s26_required
        >
        </s26-textarea>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6">
        <s26-date-picker
          id="form-date_issue"
          enable="unique"
          size="sm"
          v-model="form.date_issue"
          label="Fecha de Emisión"
          s26_required
          select_all_dates
        ></s26-date-picker>
      </div>
      <div class="col-6">
        <s26-date-picker
          id="form-date_payment"
          enable="unique"
          size="sm"
          v-model="form.date_payment"
          label="Fecha de Pago"
          s26_required
          select_all_dates
        ></s26-date-picker>
      </div>
      <div class="col-6">
        <s26-form-input
          label="Monto"
          size="sm"
          id="form-amount"
          type="text"
          v-model="form.amount"
          money
          placeholder="000.00"
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-6">
        <s26-form-input
          label="Saldo"
          size="sm"
          id="form-balance"
          type="text"
          v-model="form.balance"
          money
          placeholder="000.00"
        >
        </s26-form-input>
      </div>
      <div class="col-6 mb-3">
        <label class="form-label">
          Tipo de Cheque
          <span class="text-danger">
            <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
          </span>
        </label>
        <select
          id="form-type"
          class="form-select form-select-sm"
          v-model="form.type"
          s26-required="true"
        >
          <option value="emitido">Emitido</option>
          <option value="recibido">Recibido</option>
        </select>
        <p class="invalid-feedback">Seleccione un tipo</p>
      </div>
      <div class="col-6 mb-3">
        <label class="form-label">
          Estado de Cheque
          <span class="text-danger">
            <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
          </span>
        </label>
        <select
          id="form-payment_status"
          class="form-select form-select-sm"
          v-model="form.payment_status"
          s26-required="true"
        >
          <option value="por pagar">Por Pagar</option>
          <option value="pagado">Pagado</option>
          <option value="anulado">Anulado</option>
        </select>
        <p class="invalid-feedback">Seleccione un estado de cheque</p>
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
      form: {
        id: "",
        bank_account_id: "",
        n_check: "",
        beneficiary: "",
        reason: "",
        date_issue: [],
        date_payment: [],
        amount: "",
        balance: "",
        type: "",
        payment_status: "",
        created_at: "",
      },
      levels: ["Información de Cheque", "Información de Pago"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    totalChecks() {
      const params = {
        bank_account_id: this.form.bank_account_id,
        perPage: 25,
      };
      this.axios
        .get("/checkBooks/getCheckBooks/", {
          params,
        })
        .then((res) => {
          this.form.n_check =
            this.form.bank_account_id > 0
              ? parseInt(res.data.info.count) + 1
              : "";
        })
        .catch((err) => {
          console.log(err);
        });
    },
    infoData(id) {
      this.axios
        .get("/checkBooks/getCheckBook/" + id)
        .then((res) => {
          this.form = res.data;
          this.form.date_issue = [res.data.date_issue];
          this.form.date_payment = [res.data.date_payment];
          let date = new Date(res.data.created_at);
          this.form.created_at = new Intl.DateTimeFormat("es-ES", {
            dateStyle: "full",
            timeStyle: "short",
            calendar: "ecuador",
          }).format(date);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Cheque?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/checkBooks/setCheckBook", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => {
              console.log(e);
            });
        },
        () => {
          this.$alertify.error("Acción Cancelada");
        }
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        for (let i in this.form) {
          this.form[i] = "";
        }
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>