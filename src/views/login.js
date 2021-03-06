import Vue from "vue";

import "/Assets/css/style_view/login.css";

let element = !!document.getElementById("s26-login");
if (element) {
  new Vue({
    el: "#s26-login",
    data: {
      form: {
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
    created() {},
    methods: {
      onSubmit() {
        this.loading = true;
        if (!this.valForm()) {
          this.loading = false;
          return false;
        } else {
          let formData = $s26.json_to_formData(this.form);
          this.axios
            .post("/login/loginUser", formData)
            .then((res) => {
              if (res.request.readyState != 4) return;
              if (res.request.status == 200) {
                if (res.data.status) {
                  window.location = `${BASE_URL}/dashboard`;
                  this.loading = false;
                } else {
                  this.form.user = "";
                  this.form.password = "";
                  alertify.error(res.data.msg);
                }
              }
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
          let formReset = json_to_formData(this.reset);
          axios
            .post("/login/resetPassword", formReset)
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
}
