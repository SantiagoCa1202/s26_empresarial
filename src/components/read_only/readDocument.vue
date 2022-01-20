<template>
  <s26-modal-multiple
    id="readDocument"
    size="md"
    title="Información de Documento"
    :levels="levels"
    body_style="height: 275px"
    @hideModal="hideModal"
    @update="infoData(id)"
    update
    readOnly
  >
    <template v-slot:level-0>
      <div class="col-sm-6">
        <s26-input-read
          label="Documento"
          :content="form.document"
        ></s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read
          label="Punto de Emisión"
          :content="form.n_point.padStart(3, '0')"
        ></s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read
          label="Numeración Secuencial"
          :content="form.sequential_numbering.padStart(9, '0')"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Impresión"
          :content="form.print == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Tamaño"
          :content="
            form.size == 1 ? '14cm' : form.size == 2 ? '18.6cm' : '21.6cm'
          "
        ></s26-input-read>
      </div>
      <div class="col-sm-2">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        ></s26-input-read>
      </div>
      <div class="col-sm-7" v-if="form.print == 1">
        <s26-input-read label="Leyenda" :content="form.legend"></s26-input-read>
      </div>
      <div class="col-sm-5" v-if="form.print == 1">
        <s26-input-read
          label="Ubicación de Leyenda"
          :content="location_legend[form.location_legend]"
        ></s26-input-read>
      </div>
      <div class="col-12">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <s26-table
        :rows="authorizations.info.count"
        @get="getAuthorizations"
        :loading_data="loading_data"
        :fields="fields_authorizations"
        relative
        height="auto"
      >
        <template v-slot:body v-if="!loading_data">
          <tr v-for="auth in authorizations.items" :key="auth.id">
            <td class="length-description" :title="auth.authorization">
              {{ auth.authorization }}
            </td>
            <td class="length-int">{{ auth.from_.padStart(9, "0") }}</td>
            <td class="length-int">{{ auth.to_.padStart(9, "0") }}</td>
            <td class="length-date">
              {{ $s26.formatDate(auth.authorization_date) }}
            </td>
            <td class="length-date">
              {{ $s26.formatDate(auth.expiration_date) }}
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
        n_point: "",
        sequential_numbering: "",
      },
      levels: ["Información de Documento", "Autorizaciones"],
      fields_authorizations: [
        {
          name: "Autorización",
          class: "length-description",
        },
        {
          name: "Desde",
          class: "length-int",
        },
        {
          name: "Hasta",
          class: "length-int",
        },
        {
          name: "Fecha Auto.",
          class: "length-date",
        },
        {
          name: "Vencimiento",
          class: "length-date",
        },
      ],
      location_legend: {
        "top-m": "Arriba Centro",
        "top-l": "Arriba Izquierda",
        "top-r": "Arriba Derecha",
        "bottom-m": "Abajo Centro",
        "bottom-l": "Abajo Izquierda",
        "bottom-r": "Abajo Derecha",
      },
      loading_data: false,
      authorizations: { info: {} },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/documents/getdocument/" + id)
        .then((res) => {
          this.form = res.data;
          this.getAuthorizations();
        })
        .catch((err) => console.log(err));
    },
    getAuthorizations() {
      this.loading_data = true;
      const params = {
        emission_point_id: this.id,
      };
      this.axios
        .get("/documents/getAuthorizations/", {
          params,
        })
        .then((res) => {
          this.authorizations = res.data;
          this.loading_data = false;
        })
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "documents");
    },
  },
};
</script>