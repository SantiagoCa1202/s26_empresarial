<template>
  <s26-modal-multiple
    id="formBuys"
    :title="(id == 0 ? 'Nueva ' : 'Editar ') + 'Compra'"
    :levels="levels"
    body_style="min-height: 290px;"
    size="lg"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-4">
        <s26-select-buys
          label="N° de Doc. Modificado"
          id="form-buy_id"
          v-model="form.buy_id"
          @change="getBuy(form.buy_id)"
          s26_required
          type_doc="3"
        ></s26-select-buys>
      </div>
      <div class="col-sm-3">
        <s26-input-read label="Ruc" :content="buy.document"> </s26-input-read>
      </div>
      <div class="col-sm-5">
        <s26-input-read label="Razón Social" :content="buy.business_name">
        </s26-input-read>
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
      <div class="col-sm-3">
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
      <div class="col-5">
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
      <div class="col-4">
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
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    buy_id: "",
    buy: {},
    description: "",
    n_document: "",
    date_issue: "",
    n_authorization: "",
    file_id: "",
    created_at: "",
    bi_0: "",
    bi_: "",
    total: "",
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
      levels: ["Información de Comprobante", "Totales"],
      form: def_form(),
      buy: {},
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      this.axios
        .get("/creditNotes/getCreditNote/" + id)
        .then((res) => {
          this.form = res.data;
          this.getBuy(res.data.buy_id);
        })
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
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Nota de Crédito?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/creditNotes/setCreditNote", formData)
            .then((res) => {
              if (res.data.type == 1 || res.data.type == 2) {
                this.onReset();
                this.$alertify.success(res.data.msg);
                this.$alertify.warning(
                  `Recuerda Aztualizar los datos en 
                  <a href="${BASE_URL}/debtsToPay" target="_BLANK" class="btn btn-link"> Cuentas Por Pagar </a>`
                );
              } else if (res.data.type == 3) {
                this.$alertify.warning(res.data.msg);
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
    getBuy(id) {
      if (id > 0) {
        this.axios
          .get("/buys/getBuy/" + id)
          .then((res) => {
            this.buy = res.data;
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
        (this.form.bi_0 != "" ? parseFloat(this.form.bi_0) : 0) +
        (this.form.bi_ != "" ? parseFloat(this.form.bi_) : 0) +
        parseFloat(iva)
      ).toFixed(2);
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>