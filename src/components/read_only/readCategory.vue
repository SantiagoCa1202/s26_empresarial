<template>
  <s26-modal id="readCategory" @hideModal="hideModal" footer_none>
    <template v-slot:header>
      <h5 class="modal-title">Información de Categoría</h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <div class="col-12 col-sm-6">
          <s26-input-read label="id" :content="form.id"> </s26-input-read>
        </div>
        <div class="col-6">
          <s26-input-read label="Nombre" :content="form.name"> </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read
            label="Estado"
            :content="form.status == 1 ? 'Activo' : 'Inactivo'"
          >
          </s26-input-read>
        </div>
        <div class="col-6 p-2 s26-align-center">
          <div
            class="w-100 h-100 rounded s26-align-center"
            :style="'background:' + form.color"
          >
            <s26-icon
              :icon="form.icon ? form.icon : 'project-diagram'"
              class="fs-1 text-white"
            ></s26-icon>
          </div>
        </div>
        <div class="col-6 p-2 s26-align-center">
          <div class="w-100 h-100 rounded s26-align-center">
            <img :src="form.photo" class="rounded w-100" />
          </div>
        </div>
        <div class="col-12">
          <span class="fw-bold"> Creado el: </span>
          {{ $s26.formatDate(form.created_at, "xl") }}
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
        photo: "",
        icon: "",
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/categories/getCategory/" + id)
        .then((res) => {
          this.form = res.data;
          this.form.photo = res.data.photo.href;
          this.form.icon = res.data.icon.class;
        })
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "categories");
    },
  },
};
</script>
