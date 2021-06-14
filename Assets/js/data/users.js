let S26UsersView = new Vue({
  el: "#s26-users-view",
  data: function () {
    return {
      fields: [
        {
          name: "nombre",
          class: "length-description",
        },
        {
          name: "email",
          class: "length-description",
        },
        {
          name: "telefono",
          class: "length-int",
        },
        {
          name: "rol",
          class: "length-action",
        },
        {
          name: "establ.",
          class: "length-action text-center",
        },
        {
          name: "estado",
          class: "length-action",
        },
      ],
      filter: {
        id: "",
        document: "",
        name: "",
        role: "",
        establishment: "",
        status: "",
        date: "",
      },
      rows: 0,
      items: [],
      perPage: 25,
      idRow: null,
      activeSidebar: true,
      action: "",
    };
  },
  created() {
    this.allRows();
  },
  methods: {
    allRows() {
      const params = {
        id: this.filter.id,
        document: this.filter.document,
        name: this.filter.name,
        role_id: this.filter.role,
        establishment_id: this.filter.establishment,
        status: this.filter.status,
        date: this.filter.date,
        perPage: this.perPage,
      };
      axios
        .get("/users/getUsers/", {
          params,
        })
        .then((res) => {
          console.log(res);
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onReset() {
      for (fil in this.filter) {
        this.filter[fil] = "";
      }
      this.allRows();
    },
    setIdRow(id, type) {
      this.idRow = id;
      this.action = type;
    },
  },
});
Vue.component("s26-form-user", {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: String,
      required: true,
    },
  },
  data: function () {
    return {
      form: {
        id: "",
        name: "",
        last_name: "",
        document: "",
        email: "",
        new_password: "",
        confirm_password: "",
        phone: "",
        role_id: "",
        establishment_id: "",
        create_notifications_users: 2,
        status: 1,
        created_at: "",
      },
      msg_error: "",
      code: false,
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
    val_inputs();
    setTimeout(() => {
      $(".s26-modal").on("click", (e) => {
        this.hideModal();
      });
      $(".s26-modal-content").click(function (e) {
        e.stopPropagation();
      });
    }, 100);
  },
  methods: {
    infoData(id) {
      axios
        .get("/users/getUser/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.name;
          this.form.last_name = res.data.last_name;
          this.form.document = res.data.document;
          this.form.email = res.data.email;
          this.form.phone = res.data.phone;
          this.form.role_id = res.data.role_id;
          this.form.establishment_id = res.data.establishment_id;
          this.form.create_notifications_users = res.data.create_notifications_users;
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
    onSubmit() {
      this.form.id = this.id;
      if (!this.valForm()) {
        return false;
      }
      show_loader_points();
      axios
        .post("/users/setUser", this.form)
        .then((res) => {
          console.log(res)
          if (res.data.type == 1) {
            this.onReset();
            alertify.success(res.data.msg);
          } else if (res.data.type == 2) {
            alertify.success(res.data.msg);
          } else {
            alertify.error(res.data.msg);
          }
          hide_loader_points();
          this.$emit("update");
        })
        .catch((e) => {
          console.log(e);
        });
    },
    onReset() {
      for (let i in this.form) {
        this.form[i] = "";
      }
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    valForm() {
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
      if (this.form.document == "") {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un número de cédula.";
        return false;
      }
      if (this.form.document.length !== 10) {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "El número de cédula debe contener 10 dígitos.";
        return false;
      }
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (this.form.last_name == "") {
        $("#form-last_name").addClass("is-invalid").focus();
        this.msg_error = "Apellidos requeridos.";
        return false;
      }
      if (this.form.email == "") {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email requerido.";
        return false;
      }
      if (!this.validEmail(this.form.email)) {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email incorrecto.";
        return false;
      }
      if (this.form.phone == "") {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono requerido.";
        return false;
      }
      if (this.form.phone.length !== 10) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono debe contener 10 dígitos.";
        return false;
      }

      if (this.form.establishment_id == "") {
        $("#form-establishment").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.role_id == "") {
        $("#form-role").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.create_notifications_users == "") {
        $("#form-create_notifications_users").addClass("is-invalid").focus();
        return false;
      }

      if (this.form.status == "") {
        $("#form-status").addClass("is-invalid").focus();
        return false;
      }
      
      if (
        (this.form.new_password == "" || this.form.confirm_password == "") &&
        this.id == 0
      ) {
        $("#form-new_password, #form-confirm_password")
          .addClass("is-invalid")
          .focus();
        this.msg_error = "Contraseña requerida.";
        return false;
      }
      if (this.form.new_password !== this.form.confirm_password) {
        $("#form-new_password, #form-confirm_password")
          .addClass("is-invalid")
          .focus();
        this.msg_error = "Las contraseñas deben ser iguales.";
        return false;
      }
      return true;
    },
    hideModal() {
      this.$emit("input", null);
    },
    validEmail(email) {
      var re =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
  },
  template: `
    <div id="formUser" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ id !== 0 ? 'Editar Usuario ' + id : 'Nuevo Usuario' }}
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="onSubmit">
              <div class="row">
                <div class="col-6">
                  <s26-form-input 
                    label="Cédula" 
                    size="sm" 
                    id="form-document" 
                    type="text" 
                    v-model="form.document" 
                    maxlength="10" 
                    number 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-form-input 
                    label="Nombres" 
                    size="sm" 
                    id="form-name" 
                    type="text" 
                    v-model="form.name" 
                    maxlength="100" 
                    text 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-form-input 
                    label="Apellidos" 
                    size="sm" 
                    id="form-last_name" 
                    type="text" 
                    v-model="form.last_name" 
                    maxlength="100" 
                    text 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-form-input 
                    label="Email" 
                    size="sm" 
                    id="form-email" 
                    type="text" 
                    v-model="form.email" 
                    maxlength="100" 
                    email 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-4">
                  <s26-form-input 
                    label="Teléfono" 
                    size="sm" 
                    id="form-phone" 
                    type="text" 
                    v-model="form.phone" 
                    maxlength="10" 
                    number 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-8">
                    <s26-select-establishment 
                      id="form-establishment" 
                      v-model="form.establishment_id"
                      s26_required
                    >
                    </s26-select-establishment>
                </div>
                <div class="col-6">
                  <s26-select-role 
                    id="form-role" 
                    v-model="form.role_id"
                    s26_required
                  >
                  </s26-select-role>
                </div>
                <div class="col-3">
                    <s26-select-status 
                      lbl="Estado"
                      id="form-status" 
                      v-model="form.status"
                      s26_required
                    >
                    </s26-select-status>
                </div>
                <div class="col-3">
                    <s26-select-status 
                      lbl="Crear Notif."
                      id="form-create_notifications_users" 
                      v-model="form.create_notifications_users"
                      s26_required
                    >
                    </s26-select-status>
                </div>
                <div class="col-6">
                  <s26-form-input 
                    label="Contraseña" 
                    size="sm" 
                    id="form-new_password" 
                    type="password" 
                    v-model="form.new_password" 
                    maxlength="100" 
                    number 
                    s26_required 
                    :message="msg_error"
                    autocomplete="off"
                  >
                  </s26-form-input>
                </div>
                <div class="col-6">
                  <s26-form-input 
                    label="Contraseña" 
                    size="sm" 
                    id="form-confirm_password" 
                    type="password" 
                    v-model="form.confirm_password" 
                    maxlength="100" 
                    number 
                    s26_required 
                    :message="msg_error"
                    autocomplete="off"
                  >
                  </s26-form-input>
                </div>
                <div class="col-12 mb-4"v-if="id !== 0"> {{form.created_at}} </div>
              </div>
              <button type="button" class="btn btn-outline-danger" v-if="id == 0" @click="onReset" >Resetear</button>
              <button type="button" class="btn btn-outline-danger" v-if="id !== 0" @click="infoData(id)" >Deshacer</button>
              <button type="submit" class="btn btn-s26-success" v-if="id == 0" >Añadir</button>
              <button type="button" class="btn btn-s26-success" v-if="id !== 0" @click="code = true">Guardar</button>
            </form>
          </div>
        </div>
      </div>
      <transition name="slide-fade">
        <s26-security-code :func="onSubmit" v-if="code" v-model="code"></s26-security-code>
      </transition>
    </div>
  `,
});
Vue.component("s26-watch-user", {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: String,
      required: true,
    },
  },
  data: function () {
    return {
      form: {
        id: "",
        name: "",
        last_name: "",
        document: "",
        email: "",
        new_password: "",
        confirm_password: "",
        phone: "",
        role: "",
        establishment: "",
        status: "",
        created_at: "",
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
    val_inputs();
    setTimeout(() => {
      $(".s26-modal").on("click", (e) => {
        this.hideModal();
      });
      $(".s26-modal-content").click(function (e) {
        e.stopPropagation();
      });
    }, 100);
  },
  methods: {
    infoData(id) {
      axios
        .get("/users/getUser/" + id)
        .then((res) => {
          console.log(res);
          this.form.id = res.data.id;
          this.form.name = res.data.name;
          this.form.last_name = res.data.last_name;
          this.form.document = res.data.document;
          this.form.email = res.data.email;
          this.form.phone = res.data.phone;
          this.form.role = res.data.role.name;
          this.form.establishment = res.data.establishment.tradename;
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
  template: `
    <div id="readUser" 
    class="s26-modal" 
    tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
      <div class="s26-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Información de Usuario
        </h5>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-sm-6" v-if="id !== 0">
            <div class="mb-4">
              <label class="form-label">Id</label>
              <div class="form-control form-control-sm">{{ form.id }}</div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-4">
              <label class="form-label">Cédula</label>
              <div class="form-control form-control-sm">
                {{ form.document }}
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-4">
              <label class="form-label">Nombres</label>
              <div class="form-control form-control-sm">
                {{ form.name }}
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-4">
              <label class="form-label">Apellidos</label>
              <div class="form-control form-control-sm">
                {{ form.last_name }}
              </div>
            </div>
          </div>
          <div class="col-8">
            <div class="mb-4">
              <label class="form-label">Email</label>
              <div class="form-control form-control-sm">
                {{ form.email }}
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="mb-4">
              <label class="form-label">Teléfono</label>
              <div class="form-control form-control-sm">
                {{ form.phone }}
              </div>
            </div>
          </div>
          <div class="col-5">
            <div class="mb-4">
              <label class="form-label">Establecimiento</label>
              <div class="form-control form-control-sm">
                {{ form.establishment }}
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="mb-4">
              <label class="form-label">Rol</label>
              <div class="form-control form-control-sm">
                {{ form.role }}
              </div>
            </div>
          </div>
          <div class="col-3">
            <div class="mb-4">
              <label class="form-label">Estado</label>
              <div class="form-control form-control-sm">
                {{ form.status }}
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="mb-4">
              <label class="form-label">Creado el</label>
              <div class="form-control form-control-sm">
                {{form.created_at}} 
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
      </div>
    </div>
  `,
});
