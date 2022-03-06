<template>
  <s26-modal-multiple
    id="formExternalIncomer"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Ingreso Externo'"
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
          :height="value == 0 ? 200 : 180"
        ></s26-editor>
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
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div
        class="col-12"
        v-for="(item, index) in form.external_incomes_amount"
        :key="index"
      >
        <div class="row variants">
          <div class="col-12" v-if="index > 0">
            <button
              type="button"
              class="btn-icon text-danger float-end"
              @click="form.external_incomes_amount.splice(index, 1)"
            >
              <s26-icon icon="minus"></s26-icon>
            </button>
          </div>
          <div class="col-6">
            <s26-form-input
              label="Monto"
              type="tel"
              v-model="item.amount"
              number
              money
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-6">
            <s26-select-status
              label="Cuenta"
              id="form-account"
              v-model="item.account"
              :options="['Costo', 'Ganancia']"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-6">
            <s26-select-bank-account
              id="form-bank_account_id"
              v-model="item.bank_account_id"
            >
            </s26-select-bank-account>
          </div>
          <div class="col-6">
            <s26-select-status
              label="Estado"
              id="form-status"
              v-model="item.status"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-12" v-if="id !== 0">
            <span class="fw-bold">Creado el:</span>
            {{ $s26.formatDate(item.created_at, "xl") }}
          </div>
        </div>
      </div>
      <div class="col-12">
        <button type="button" class="btn btn-primary w-100" @click="add_import">
          Añadir Importe
        </button>
      </div>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_external_amount = () => {
  return {
    id: "",
    amount: "",
    account: "",
    bank_account_id: "",
    status: "",
  };
};
const def_form = () => {
  return {
    id: "",
    tradename: "",
    description: "",
    establishment_id: "",
    status: "",
    external_incomes_amount: [def_external_amount()],
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
      levels: ["Información de Ingreso Externo", "Información de Importes"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/externalIncomes/getExternalIncome/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Ingreso Externo?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/externalIncomes/setExternalIncome", formData)
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
    add_import() {
      this.form.external_incomes_amount.push(def_external_amount());
    },
  },
};
</script>
<style scoped>
.variants {
  overflow: hidden;
  box-shadow: 0 10px 5px -6px rgb(93 130 170 / 21%) !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.25rem !important;
  margin-bottom: 1rem !important;
  padding-top: 0.5rem !important;
  padding-bottom: 0.5rem !important;
}
</style>