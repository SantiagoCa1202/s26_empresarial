Vue.component("s26-security-code", {
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
    setTimeout(() => {
      $(".s26-modal").on("click", (e) => {
        return false;
      });
    }, 100);
    this.newToken();
  },
  methods: {
    newToken() {
      axios.post("/token/newToken");
    },
    onSubmit() {
      axios
        .post("/token/valToken", this.form)
        .then((res) => {
          console.log(res);
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
        .catch((e) => {
          console.log(e);
        });
    },
    hideCode() {
      axios.post("/token/disabledToken");
      this.form.code = "";
      this.$emit("input", false);
    },
  },
  template: `
  <div id="s26-security-code" 
    class="s26-modal" 
    tabindex="-1"
  >
  <div class="s26-modal-dialog s26-modal-sm s26-modal-dialog-centered">
      <div class="s26-modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Código de seguridad
          </h5>
        </div>
        <div class="modal-body">
          <form>
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
            <button type="submit" @click="onSubmit" class="btn btn-success" >
              Aceptar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
`,
});
