<template>
  <s26-modal-multiple
    id="readProductDamaged"
    size="md"
    title="Información de Averiado"
    :levels="levels"
    body_style="height: 400px"
    @hideModal="hideModal"
    @update="infoData(id)"
    update
    readOnly
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-input-read
          label="Producto"
          :content="form.product"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read
          label="Costo"
          :content="form.cost"
          money
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read label="Cant." :content="form.amount"></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Estado"
          :content="form.product_status"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read label="Estado" :content="form.status"></s26-input-read>
      </div>
      <div class="col-12">
        <s26-textarea-read
          label="Descripción"
          :content="form.description"
          rows="4"
        >
        </s26-textarea-read>
      </div>
      <div class="col-12">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div
        class="col-12 text-center text-danger"
        v-show="form.document_id <= 0"
      >
        <p>No hay registro de documento para este producto.</p>
      </div>
      <div class="col-12" v-if="form.document_id > 0">
        <div class="row">
          <div class="col-sm-3">
            <s26-input-read
              label="Ruc"
              :content="document.document"
            ></s26-input-read>
          </div>
          <div class="col-sm-9">
            <s26-input-read
              label="Razón Social"
              :content="document.business_name"
            ></s26-input-read>
          </div>
          <div class="col-sm-6">
            <s26-input-read-document
              label="N° de Documento"
              :content="document.n_document"
            ></s26-input-read-document>
          </div>
          <div class="col-sm-6">
            <s26-input-read
              label="Tipo de Documento"
              :content="document.type_doc.name"
            ></s26-input-read>
          </div>
        </div>
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
      form: {},
      document: {
        n_document: "",
        type_doc: {},
      },
      levels: ["Información de Producto", "Información de Documento"],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/productsDamageds/getProductDamaged/" + id)
        .then((res) => {
          this.form = res.data;
          if (this.form.document_id > 0) {
            this.getDocument(this.form.document_id);
          }
        })
        .catch((err) => console.log(err));
    },
    getDocument(id) {
      this.axios
        .get("/buys/getBuy/" + id)
        .then((res) => {
          this.document = res.data;
        })
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "productsDamageds");
    },
  },
};
</script>