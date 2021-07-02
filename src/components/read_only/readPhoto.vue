<template>
  <s26-modal-multiple
    id="readPhoto"
    title="Información de Foto"
    :levels="levels"
    body_style="min-height: 345px"
    @hideModal="hideModal"
    readOnly
    footer_none
  >
    <template v-slot:level-0>
      <div class="col-6">
        <div class="row">
          <div class="col-12">
            <s26-input-read label="Id" :content="form.id"></s26-input-read>
          </div>
          <div class="col-12">
            <s26-input-read
              label="Nombre"
              :content="form.name"
            ></s26-input-read>
          </div>
          <div class="col-12">
            <s26-input-read
              label="Descripción"
              :content="form.description"
            ></s26-input-read>
          </div>
          <div class="col-12">
            <s26-input-read
              label="Estado"
              :content="form.status ? 'activo' : 'inactivo'"
            ></s26-input-read>
          </div>
        </div>
      </div>
      <div class="col-6 h-100 s26-align-center">
        <img :src="form.href" class="rounded shadow-sm w-100" />
      </div>
      <div class="col-12">
        <span class="fw-bold">Creado el:</span>
        {{ form.created_at }}
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
      levels: ["información"],
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
        .get("/photos/getPhoto/" + id)
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
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>