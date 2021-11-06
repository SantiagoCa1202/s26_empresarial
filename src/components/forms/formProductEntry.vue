<template>
  <s26-modal>
    <template v-slot:header>
      <h1 class="modal-title h4">Editar Entrada</h1>
    </template>
    <template v-slot:body>
      <!-- INFO. DOC. DE COMPRA -->
      <div class="row">
        <div class="col-12 mb-3">
          <h2 class="text-center h6">
            {{ form.ean_code }} /
            {{ form.name }}
          </h2>
        </div>
        <div class="col-12">
          <s26-select-buys
            label="N° de Doc."
            id="form-document_id"
            v-model="form.document_id"
            @change="getBuy(form.document_id)"
            is_null
            assign
            s26_required
          ></s26-select-buys>
        </div>
        <div class="col-4">
          <s26-input-read label="Ruc" :content="buy.document"> </s26-input-read>
        </div>
        <div class="col-8">
          <s26-input-read label="Razón Social" :content="buy.business_name">
          </s26-input-read>
        </div>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:footer>
      <button class="btn btn-outline-danger" @click="$emit('input', null)">
        Cancelar
      </button>
      <button class="btn btn-primary" @click="onSubmit">Guardar</button>
    </template>
  </s26-modal>
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
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  data: function () {
    return {
      buy: {},
      form: {
        document_id: "",
        id: "",
      },
    };
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/productsEntries/getEntry/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      if (this.form.document_id >= -1) {
        this.$alertify.confirm(
          `Desea Asignar Documento a Entrada?`,
          () => {
            let formData = $s26.json_to_formData(this.form);
            $s26.show_loader_points();
            this.axios
              .post("/productsEntries/setEntry", formData)
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
                this.$emit("input", null);
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Acción Cancelada")
        );
      } else {
        this.$alertify.error(
          "Es Necesario Llenar todos los campos requeridos."
        );
        return false;
      }
    },
    getBuy(id) {
      if (id > 0) {
        this.axios
          .get("/buys/getBuy/" + id)
          .then((res) => (this.buy = res.data))
          .catch((err) => console.log(err));
      } else {
        this.buy = {};
      }
    },
  },
};
</script>