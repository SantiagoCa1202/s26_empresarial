<template>
  <s26-modal-multiple
    id="formTransfer"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Transferencia'"
    :levels="levels"
    body_style="min-height: 400px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
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
      <div class="col-sm-5">
        <s26-select-bank-account
          label="Cuenta Origen"
          id="form-source_account_id"
          v-model="form.source_account_id"
          s26_required
        >
        </s26-select-bank-account>
      </div>
      <div class="col-sm-2 s26-align-center fs-5 text-primary">
        <s26-icon icon="exchange-alt"></s26-icon>
      </div>
      <div class="col-sm-5">
        <s26-select-bank-account
          label="Cuenta Destino"
          id="form-destination_account_id"
          v-model="form.destination_account_id"
          s26_required
        >
        </s26-select-bank-account>
      </div>
      <div class="col-12">
        <s26-textarea
          id="form-description"
          label="Descripción"
          rows="4"
          v-model="form.description"
        >
        </s26-textarea>
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
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    source_account_id: "",
    destination_account_id: "",
    description: "",
    amount: "",
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
      levels: ["Información de Transferencia"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/transfers/getTransfer/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Transferencia?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/transfers/setTransfer", formData)
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