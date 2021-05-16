let S26Login = new Vue({
  el: "#s26-login",
  data: {
    form: {
      //Restablecer
      user: "0979244746",
      password: "Camila120219",
    },
    reset: {
      email: "",
    },
    password: true,
    reset_password: false,
    loading: false,
  },
  created() {
    //Eliminar 
    this.onSubmit()
  },
  methods: {
    onSubmit() {
      this.loading = true;
      if (!this.valForm()) {
        this.loading = false;
        return false;
      } else {
        axios
          .post("/login/loginUser", this.form)
          .then((res) => {
            if (res.request.readyState != 4) return;
            if (res.request.status == 200) {
              if (res.data.status) {
                window.location = `${BASE_URL}/dashboard`;
              } else {
                this.form.user = "";
                this.form.password = "";
                alertify.error(res.data.msg);
              }
            }
            this.loading = false;
            return false;
          })
          .catch((e) => {
            console.log(e);
          });
      }
    },
    onResetPassword() {
      this.loading = true;
      $("#email").removeClass("is-invalid");
      if (this.reset.email == "") {
        $("#email").addClass("is-invalid");
        alertify.error("Escriba un Correo Válido");
        return false;
      } else {
        axios
          .post("/login/resetPassword", this.reset)
          .then((res) => {
            if (res.request.readyState != 4) return;
            if (res.request.status == 200) {
              if (res.data.status) {
                alertify.success(res.data.msg);
                this.reset_password = false;
              } else {
                alertify.error(res.data.msg);
              }
            } else {
              alertify.error("Error, intentalo mas tarde.");
            }
            this.loading = false;
            return false;
          })
          .catch((e) => {
            console.log(e);
          });
      }
    },
    valForm() {
      $("#user, #password").removeClass("is-invalid");
      if (this.form.user == "" || this.form.password == "") {
        $("#user, #password").addClass("is-invalid");
        alertify.error("Escriba un usuario y una Contraseña");
        return false;
      } else {
        return true;
      }
    },
  },
});
