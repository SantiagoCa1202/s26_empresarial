<template>
  <s26-modal-multiple
    id="formBuyToProviders"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Compra'"
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
        ></s26-select-type-document>
      </div>
      <div class="col-sm-4">
        <s26-select-payment-method
          id="form-payment_method"
          v-model="form.payment_method"
        ></s26-select-payment-method>
      </div>
      <div class="col-sm-4">
        <s26-date-picker
          id="form-date_issue"
          enable="unique"
          size="sm"
          v-model="form.date_issue"
          label="Fecha"
          s26_required
          select_all_dates
        ></s26-date-picker>
      </div>
      <div class="col-6">
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
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Subir Ride / Documento Pdf"
          size="sm"
          id="form-file_ride"
          type="file"
          v-model="form.file_ride"
          file
          accept="application/pdf"
        >
        </s26-form-input>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
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
            id="form-subtotal_0"
            type="text"
            v-model="form.subtotal_0"
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
            id="form-subtotal_12"
            type="text"
            v-model="form.subtotal_12"
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
                v-model="form.date_payment"
                label="Fecha"
                s26_required
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
            <div class="col-sm-12">
              <s26-form-input
                label="N° de Transacción"
                size="sm"
                id="form-n_transaction"
                type="text"
                v-model="form.n_transaction"
                number
              >
              </s26-form-input>
            </div>
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
            <div class="col-sm-6">
              <s26-date-picker
                id="form-credit_one"
                enable="unique"
                size="sm"
                v-model="form.credit_one"
                label="Fecha"
                s26_required
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-sm-6">
              <s26-date-picker
                id="form-credit_two"
                enable="unique"
                size="sm"
                v-model="form.credit_two"
                label="Fecha"
                s26_required
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-sm-6">
              <s26-date-picker
                id="form-credit_three"
                enable="unique"
                size="sm"
                v-model="form.credit_three"
                label="Fecha"
                s26_required
                select_all_dates
              ></s26-date-picker>
            </div>
            <div class="col-sm-6">
              <s26-date-picker
                id="form-credit_for"
                enable="unique"
                size="sm"
                v-model="form.credit_for"
                label="Fecha"
                s26_required
                select_all_dates
              ></s26-date-picker>
            </div>
          </div>
        </div>
      </transition-group>
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
        provider_id: "",
        document: "",
        business_name: "",
        description: "",
        n_document: "",
        type_doc_id: "",
        payment_method: "",
        date_issue: "",
        n_authorization: "",
        ride: "",
        created_at: "",
        rise: "",
        subtotal_0: "",
        subtotal_12: "",
        iva: "",
        total: "",
        payment_type: 1,
        date_payment: "",
        credit_note: "",
        payment_method_counted: "",
        n_transaction: "",
        credit_note: "",
        credit_one: "",
        credit_two: "",
        credit_three: "",
        credit_for: "",
      },
      levels: ["Información de Comprobante", "Totales", "Plazo"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoData(id) {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      this.axios
        .get("/users/getUser/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.name;
          this.form.last_name = res.data.last_name;
          this.form.document = res.data.document;
          this.form.email = res.data.email;
          this.form.phone = res.data.phone;
          this.form.gender_id = res.data.gender_id;
          this.form.date_of_birth = [res.data.date_of_birth];
          this.form.role_id = res.data.role_id;
          this.form.insurance = res.data.insurance;
          this.form.establishment_id = res.data.establishment_id;
          this.form.user_access = res.data.user_access;
          this.form.create_notifications_users =
            res.data.create_notifications_users;
          this.form.status = res.data.status;
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
      if (
        (this.form.new_password != "" && this.form.new_password.length < 12) ||
        (this.form.confirm_password != "" &&
          this.form.confirm_password.length < 12) ||
        this.form.new_password !== this.form.confirm_password
      ) {
        this.$alertify.message("Las contraseñas no son iguales");
        this.$alertify.message("La Contraseña debe tener mínimo 12 dígitos");
        return;
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Usuario?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/users/setUser", formData)
            .then((res) => {
              console.log(res);
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
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    total() {
      const _iva__ = 1.12;

      let iva =
        this.form.subtotal_12 != ""
          ? this.form.subtotal_12 * _iva__ - this.form.subtotal_12
          : 0;

      this.form.iva = parseFloat(iva).toFixed(2);
      this.form.total = (
        (this.form.rise != "" ? parseFloat(this.form.rise) : 0) +
        (this.form.subtotal_0 != "" ? parseFloat(this.form.subtotal_0) : 0) +
        (this.form.subtotal_12 != "" ? parseFloat(this.form.subtotal_12) : 0) +
        parseFloat(iva)
      ).toFixed(2);
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>