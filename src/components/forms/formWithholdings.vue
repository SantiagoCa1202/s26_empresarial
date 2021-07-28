<template>
  <s26-modal-multiple
    id="formWithholdings"
    :title="(id == 0 ? 'Nueva ' : 'Editar ') + 'Retención'"
    :levels="levels"
    body_style="min-height: 290px;"
    size="lg"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
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
      <div class="col-sm-3">
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
      <div class="col-8">
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
            label="Ret. Iva"
            size="sm"
            id="form-ret_iva"
            type="text"
            v-model="form.ret_iva"
            money
            placeholder="000.00"
            @keyup="total"
          >
          </s26-form-input>
        </div>
        <div class="col-sm-12">
          <s26-form-input
            label="Ret. Imp. Rent."
            size="sm"
            id="form-ret_imp_rent"
            type="text"
            v-model="form.ret_imp_rent"
            money
            placeholder="000.00"
            @keyup="total"
          >
          </s26-form-input>
        </div>
        <div class="col-12">
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
    document: "",
    business_name: "",
    description: "",
    n_document: "",
    date_issue: "",
    n_authorization: "",
    file_id: "",
    created_at: "",
    ret_iva: "",
    ret_imp_rent: "",
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
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      $("[s26-required]").removeClass("is-invalid");
      this.axios
        .get("/withholdings/getWithholding/" + id)
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
        $("#form-ret_iva, #form-ret_imp_rent").addClass("is-invalid");
        return;
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Retención?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/withholdings/setWithholding", formData)
            .then((res) => {
              if (res.data.type == 1 || res.data.type == 2) {
                this.onReset();
                this.$alertify.success(res.data.msg);
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
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    total() {
      this.form.total = (
        (this.form.ret_iva != "" ? parseFloat(this.form.ret_iva) : 0) +
        (this.form.ret_imp_rent != "" ? parseFloat(this.form.ret_imp_rent) : 0)
      ).toFixed(2);
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>