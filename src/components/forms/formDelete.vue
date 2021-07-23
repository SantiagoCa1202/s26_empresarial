<template>
  <s26-modal>
    <template v-slot:header
      ><h5 class="modal-title">¿Realmente desea eliminar este registro?</h5>
    </template>
    <template v-slot:body>
      <p>Una vez eliminado el registro no podra ser recuperado.</p>
    </template>
    <template v-slot:footer>
      <button class="btn btn-outline-danger" @click="$emit('input', null)">
        Cancelar
      </button>
      <button class="btn btn-danger" @click="deleteRow">Eliminar</button>
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
    post_delete: String,
  },
  methods: {
    deleteRow() {
      $s26.show_loader_points();
      this.axios
        .post("/" + this.post_delete)
        .then((res) => {
          if (res.data.type == 2) {
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
  },
};
</script>