<template>
  <s26-modal id="formDocument" @hideModal="hideModal" body_class="h-auto">
    <template v-slot:header>
      <h1 class="h4 modal-title">
        {{ id !== 0 ? "Editar  " : "Nuevo " }} Documento
      </h1>
    </template>
    <template v-slot:body>
      <form id="formDocument" @submit.prevent>
        <div class="row">
          <div class="col-4">
            <s26-select-type-document
              id="form-document_id"
              v-model="form.document_id"
              s26_required
              type="declarable"
            ></s26-select-type-document>
          </div>
          <div class="col-4">
            <s26-form-input
              label="Punto de Emisión"
              id="form-n_point"
              type="text"
              v-model="form.n_point"
              maxlength="3"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-4">
            <s26-form-input
              label="N° Secuencial"
              id="form-sequential_numbering"
              type="text"
              v-model="form.sequential_numbering"
              maxlength="9"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-6 col-sm-4">
            <s26-select-status
              label="Impreción"
              id="form-print"
              v-model="form.print"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-6 col-sm-4">
            <label class="form-label"> Tamaño </label>
            <select
              class="form-select form-select-sm"
              v-model="form.size"
              s26-required
            >
              <option value="1">14cm</option>
              <option value="2">18.6cm</option>
              <option value="3">21.6cm</option>
            </select>
          </div>
          <div class="col-6 col-sm-4">
            <s26-select-status
              label="Estado"
              id="form-status"
              v-model="form.status"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-7" v-if="form.print == 1">
            <s26-form-input
              label="Leyenda"
              id="form-legend"
              type="text"
              v-model="form.legend"
              maxlength="40"
              length
            >
            </s26-form-input>
          </div>
          <div class="col-sm-5" v-if="form.print == 1">
            <label class="form-label"> Ubicación de Leyenda </label>
            <select
              class="form-select form-select-sm"
              v-model="form.location_legend"
            >
              <option value="top-m">Arriba Centro</option>
              <option value="top-l">Arriba Izquierda</option>
              <option value="top-r">Arriba Derecha</option>
              <option value="bottom-m">Abajo Centro</option>
              <option value="bottom-l">Abajo Izquierda</option>
              <option value="bottom-r">Abajo Derecha</option>
            </select>
          </div>
          <template v-if="id == 0">
            <div class="col-12">
              <h2 class="h5 fw-600">Autorización</h2>
            </div>
            <div class="col-6">
              <s26-form-input
                label="N° de Autorización"
                id="form-n_authorization"
                type="text"
                v-model="form.n_authorization"
                strictlength="10,49"
                number
                s26_required
                length
              >
              </s26-form-input>
            </div>
            <div class="col-3">
              <s26-form-input
                label="Desde"
                id="form-from"
                type="text"
                v-model="form.from"
                maxlength="9"
                number
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-3">
              <s26-form-input
                label="Hasta"
                id="form-to"
                type="text"
                v-model="form.to"
                maxlength="9"
                number
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-sm-6">
              <s26-date-picker
                id="form-authorization_date"
                enable="unique"
                size="sm"
                v-model="form.authorization_date"
                label="Fecha de Autorización"
                s26_required
                select_all_dates
                today
              ></s26-date-picker>
            </div>
            <div class="col-sm-6">
              <s26-date-picker
                id="form-expiration_date"
                enable="unique"
                size="sm"
                v-model="form.expiration_date"
                label="Fecha de Vencimiento"
                s26_required
                select_all_dates
                today
              ></s26-date-picker>
            </div>
          </template>
          <div class="col-12" v-if="id !== 0">
            <span class="fw-bold">Creado el:</span>
            {{ $s26.formatDate(form.created_at, "xl") }}
          </div>
        </div>
      </form>
    </template>
    <template v-slot:footer>
      <button
        type="button"
        class="btn btn-outline-danger"
        @click="id !== 0 ? infoData(id) : onReset()"
      >
        {{ id !== 0 ? "Deshacer" : "Resetear" }}
      </button>
      <button type="button" class="btn btn-info" @click="onSubmit">
        {{ id !== 0 ? "Guardar" : "Añadir" }}
      </button>
    </template>
  </s26-modal>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    document_id: "",
    n_point: "",
    sequential_numbering: "",
    print: "",
    legend: "",
    location_legend: "top-m",
    size: "",
    n_authorization: "",
    from: "",
    to: "",
    authorization_date: "",
    expiration_date: "",
    status: 1,
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
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/documents/getDocument/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      if (!$s26.val_form("formDocument")) {
        this.$alertify.error(
          "Es Necesario Llenar todos los campos requeridos."
        );
        return false;
      }
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Documento?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/documents/setDocument", formData)
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
      this.form = def_form();
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
