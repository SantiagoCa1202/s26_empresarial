<template>
  <s26-modal-multiple
    id="formBankAccount"
    :title="(id == 0 ? 'Nueva ' : 'Editar ') + 'Cuenta Bancaria'"
    :levels="levels"
    body_style="min-height: 280px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-6">
        <s26-select-bank
          label="Entidad Bancaria"
          size="sm"
          id="form-bank_entity_id"
          v-model="form.bank_entity_id"
          s26_required
        >
        </s26-select-bank>
      </div>
      <div class="col-sm-6">
        <s26-form-input
          label="Número de Cuenta"
          size="sm"
          id="form-n_account"
          type="text"
          v-model="form.n_account"
          maxlength="50"
          minlength="5"
          number
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12 mb-3">
        <label class="form-label">
          Tipo de Cuenta
          <span class="text-danger">
            <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
          </span>
        </label>
        <select
          id="form-account_type"
          class="form-select form-select-sm"
          v-model="form.account_type"
          s26-required="true"
        >
          <option value="ahorros">Ahorros</option>
          <option value="corriente">Corriente</option>
        </select>
        <p class="invalid-feedback">Seleccione un tipo de cuenta</p>
      </div>
      <div class="col-4">
        <s26-select-status
          label="Chequera"
          id="form-checkbook"
          v-model="form.checkbook"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-4">
        <s26-select-status
          label="Predeterminado"
          id="form-predetermined"
          v-model="form.predetermined"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-4">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
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
        bank_entity_id: "",
        n_account: "",
        account_type: "",
        checkbook: 1,
        predetermined: 2,
        status: 1,
        created_at: "",
      },
      levels: ["Información de Cuenta Bancaria"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/bankAccounts/getBankAccount/" + id)
        .then((res) => {
          this.form = res.data;
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
        `Desea ${
          this.id == 0 ? "Ingresar " : "Actualizar"
        } la Cuenta Bancaria?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/bankAccounts/setBankAccount", formData)
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
