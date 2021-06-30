<template>
  <s26-modal
    id="readCustomer"
    @hideModal="hideModal"
    footer_none
    body_class="h-auto"
  >
    <template v-slot:header>
      <h5 class="modal-title">Información de Cliente</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-12 col-sm-6" v-if="id !== 0">
          <s26-input-read label="id" :content="form.id"> </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Cédula" :content="form.document">
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Nombres" :content="form.name">
          </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Teléfono" :content="form.phone">
          </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Celular" :content="form.mobile">
          </s26-input-read>
        </div>
        <div class="col-9">
          <s26-input-read label="Dirección" :content="form.address">
          </s26-input-read>
        </div>
        <div class="col-3">
          <s26-input-read label="Plazo" :content="form.time_limit">
          </s26-input-read>
        </div>
        <div class="col-8">
          <s26-input-read label="Email" :content="form.email"> </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Estado"
            :content="form.status == 1 ? 'Activo' : 'Inactivo'"
          >
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Creado el:" :content="form.created_at">
          </s26-input-read>
        </div>
      </div>
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
  data: function () {
    return {
      form: {
        id: "",
        name: "",
        document: "",
        address: "",
        time_limit: "",
        phone: "",
        mobile: "",
        email: "",
        status: 1,
        created_at: "",
      },
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
        .get("/customers/getCustomer/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.full_name;
          this.form.document = res.data.document;
          this.form.address = res.data.address;
          this.form.time_limit = res.data.time_limit;
          this.form.phone = res.data.phone;
          this.form.mobile = res.data.mobile;
          this.form.email = res.data.email;
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
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
