<template>
  <s26-modal-multiple
    id="readBuys"
    title="Información de Compra a Proveedor"
    :levels="levels"
    body_style="min-height: 375px;"
    size="lg"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <template v-slot:level-0>
      <div class="col-sm-3">
        <s26-input-read
          label="Proveedor"
          :content="form.provider.alias"
        ></s26-input-read>
      </div>
      <div class="col-sm-3">
        <s26-input-read label="Ruc" :content="form.document"></s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read
          label="Razón Social"
          :content="form.business_name"
        ></s26-input-read>
      </div>
      <div class="col-sm-7">
        <s26-input-read
          label="Descripción"
          :content="form.description"
        ></s26-input-read>
      </div>
      <div class="col-sm-5">
        <s26-input-read-document
          label="N° de Documento"
          :content="form.n_document"
        ></s26-input-read-document>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Tipo de Documento"
          :content="form.type_doc.name"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Forma de Pago"
          :content="form.payment_method.name"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Fecha de Emisión"
          :content="$s26.formatDate(form.date_issue)"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Establecimiento"
          :content="form.establishment.tradename"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="N° de Autorización"
          :content="form.n_authorization"
        ></s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read
          label="Archivo"
          :content="form.file.name"
        ></s26-input-read>
      </div>
      <div class="col-12">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-6 row mx-auto">
        <div class="col-sm-12">
          <s26-input-read
            label="Rise"
            :content="form.rise"
            money
          ></s26-input-read>
        </div>
        <div class="col-sm-12">
          <s26-input-read
            label="Subtotal 0%"
            :content="form.bi_0"
            money
          ></s26-input-read>
        </div>
        <div class="col-sm-12">
          <s26-input-read
            label="Subtotal 12%"
            :content="form.bi_"
            money
          ></s26-input-read>
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
      <s26-table
        :rows="s26_data.info.count"
        @get="allRows"
        :sidebar="false"
        :loading_data="loading_data"
        :fields="fields"
        relative
        height="auto"
      >
        <template v-slot:body v-if="!loading_data">
          <tr v-for="entry in s26_data.items" :key="entry.id">
            <td class="length-int">{{ entry.ean_code }}</td>
            <td class="length-description">{{ entry.name }}</td>
            <td class="length-int text-center">{{ entry.amount }}</td>
            <td class="length-int text-center">
              <span><s26-icon icon="dollar-sign"></s26-icon></span>
              {{ $s26.currency(entry.cost) }}
            </td>
            <td class="length-int text-center">
              <span><s26-icon icon="dollar-sign"></s26-icon></span>
              {{ $s26.currency(entry.amount * entry.cost) }}
            </td>
          </tr>
        </template>
      </s26-table>
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
        provider: {},
        type_doc: {},
        payment_method: {},
        establishment: {},
        file: {},
        n_document: "",
      },
      fields: [
        {
          name: "Código",
          class: "length-int",
        },
        {
          name: "Producto",
          class: "length-description",
        },
        {
          name: "Cant.",
          class: "length-int text-center",
        },
        {
          name: "Costo",
          class: "length-int text-center",
        },
        {
          name: "Total",
          class: "length-int text-center",
        },
      ],
      levels: ["Información de Compra", "Totales", "Productos"],
      s26_data: { info: {} },
      loading_data: false,
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/buys/getBuy/" + id)
        .then((res) => {
          this.form = res.data;
          this.allRows(id);
        })
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "buys");
    },
    allRows() {
      this.loading_data = true;
      const params = {
        document_id: this.id,
      };
      this.axios
        .get("/productsEntries/getEntries/", {
          params,
        })
        .then((res) => {
          this.s26_data = res.data;
          this.loading_data = false;
        })
        .catch((err) => console.log(err));
    },
  },
};
</script>