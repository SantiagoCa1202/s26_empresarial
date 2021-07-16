<template>
  <s26-modal
    id="formSecurityCode"
    @hideModal="hideCode"
    size="sm"
    footer_none
    header_class="border-0"
    prevent_global_close
  >
    <template v-slot:header>
      <h5 class="modal-title fw-normal">Código de Seguridad</h5>
    </template>
    <template v-slot:body>
      <form @submit.prevent="onSubmit">
        <s26-form-input
          size="sm"
          variant="text-center"
          id="security-code"
          type="password"
          v-model="form.code"
          maxlength="6"
          number
          s26_required
          autocomplete="off"
          :message="msg_error"
        >
        </s26-form-input>
        <button type="button" class="btn btn-outline-danger" @click="hideCode">
          Cancelar
        </button>
        <button type="submit" class="btn btn-info">Aceptar</button>
      </form>
    </template>
  </s26-modal>
</template>
<script>
export default {
  props: {
    value: {
      type: Boolean,
      required: true,
    },
    func: {
      type: Function,
      required: true,
    },
  },
  data: function () {
    return {
      form: {
        code: "",
      },
      msg_error: "",
    };
  },
  created() {
    this.newToken();
    setTimeout(() => $("#security-code").focus(), 50);
  },
  methods: {
    newToken() {
      this.axios.post("/token/newToken");
    },
    onSubmit() {
      let formData = $s26.json_to_formData(this.form);
      this.axios
        .post("/token/valToken", formData)
        .then((res) => {
          if (res.data.status) {
            this.func();
            this.$emit("input", false);
          } else {
            $("#security-code").addClass("is-invalid").focus();
            this.msg_error = res.data.msg;
            this.newToken();
          }
          this.form.code = "";
        })
        .catch((e) => console.log(e));
    },
    hideCode() {
      this.axios.post("/token/disabledToken");
      this.form.code = "";
      this.$emit("input", false);
    },
  },
};
</script>