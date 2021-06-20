let S26UsersInfoView = new Vue({
  el: "#s26-users-info-view",
  data: function () {
    return {
      fields: [
        {
          name: "fecha",
          class: "length-description",
        },
        {
          name: "descripción",
          class: "length-description",
        },

        {
          name: "importe",
          class: "length-int",
        },
        {
          name: "forma de pago",
          class: "length-description",
        },
      ],
      filter: {
        date: "",
      },
      icon: {
        building: {
          background: "#3A86FA",
          color: "#fff",
        },
        user: {
          background: "#20c997",
          color: "#fff",
        },
      },
      payRoll_arr: {},
      modal: "info_user",
      items: [],
      rows: 0,
      idRow: null,
      idNote: null,
      idNotification: null,
      del_note: null,
      del_notification: null,
      perPage: 25,
      idUser: idUser,
    };
  },
  created() {},
  methods: {
    payRoll() {
      this.modal = "payroll";

      axios
        .get("/users/getPayroll/0")
        .then((res) => {
          this.payRoll_arr = res.data;
          this.payments_records();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    payments_records() {
      const params = {
        date: this.filter.date,
        perPage: this.perPage,
      };
      axios
        .get("/users/getPayRecords/", {
          params,
        })
        .then((res) => {
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    my_notes() {
      this.modal = "my_notes";
      axios
        .get("/users/getMyNotes/")
        .then((res) => {
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    notifications() {
      this.modal = "notifications";
      axios
        .get("/users/getNotifications/")
        .then((res) => {
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
});
Vue.component("s26-info-payroll", {
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
        payment_method: {
          name: "",
        },
      },
    };
  },
  created() {
    if (this.value !== 0 && this.value !== null) {
      this.infoData(this.value);
    }
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
        .get("/users/getPayRecord/" + id)
        .then((res) => {
          this.form = res.data;
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
  <div id="formPyroll" 
    class="s26-modal" 
    tabindex="-1"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="s26-modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Información de pago
          </h5>
          <button type="button" class="btn-close" @click="hideModal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-sm-6">
              <div class="mb-4">
                <label class="form-label">Id</label>
                <div class="form-control form-control-sm">{{ form.id }}</div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="mb-4">
                <label class="form-label">Fecha</label>
                <div class="form-control form-control-sm">
                  {{ form.amount_date }}
                </div>
              </div>
            </div>
            
            <div class="col-12 col-sm-6">
              <label class="form-label">Importe / Valor</label>
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">$</span>
                <div class="form-control">
                  {{ form.amount }}
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="mb-4">
                <label class="form-label">Forma de pago</label>
                <div class="form-control form-control-sm">
                  {{ form.payment_method.name }}
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="mb-4">
                <label class="form-label">Descripción</label>
                <div class="form-control form-control-sm">
                  {{ form.description }}
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
Vue.component("s26-form-notes", {
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
        note: "",
        color: "#ffc107",
        created_at: "",
      },
    };
  },
  created() {
    if (this.value !== 0 && this.value !== null) {
      this.infoData(this.value);
    }
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
        .get("/users/getMyNote/" + id)
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
    onSubmit() {
      this.form.id = this.value;
      show_loader_points();

      let formData = json_to_formData(this.form);
      axios
        .post("/users/setNote", formData)
        .then((res) => {
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
      this.form.name = "";
      this.form.note = "";
      this.form.color = "#ffc107";
    },

    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
  <div id="formNotes" 
    class="s26-modal" 
    tabindex="-1"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="s26-modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ value !== 0 ? 'Editar Nota' : 'Nueva Nota' }}
          </h5>
          <button type="button" class="btn-close" @click="hideModal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="onSubmit">
            <div class="row">
              <div class="col-12">
                <s26-form-input 
                  label="Título" 
                  size="sm" 
                  id="form-name" 
                  type="text" 
                  v-model="form.name" 
                  maxlength="100" 
                  text 
                >
                </s26-form-input>
              </div>
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label">Descripción</label>
                  <textarea class="form-control resize-none" cols="30" rows="10" v-model="form.note"></textarea>
                </div>
              </div>
              <div class="col-12">
                <div class="row">
                  <label class="col-sm-6 col-form-label">Seleccionar Color:</label>
                  <div class="col-sm-6">
                    <input type="color" class="form-control form-control-color float-end" v-model="form.color" title="Choose your color">
                  </div>
                </div> 
              </div>
              <div class="col-12 mb-4"v-if="id !== 0"> {{form.created_at}} </div>
            </div>
            <button type="button" class="btn btn-outline-danger" v-if="value == 0" @click="onReset" >Resetear</button>
            <button type="button" class="btn btn-outline-danger" v-if="value !== 0" @click="infoData(value)" >Deshacer</button>
            <button type="submit" class="btn btn-s26-success" > {{ value == 0 ? 'Añadir' : 'Guardar' }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
`,
});

Vue.component("s26-form-notifications", {
  props: {
    value: {
      type: String,
      required: true,
    },
  },
  data: function () {
    return {
      form: {
        idUser: 0,
        name: "",
        description: "",
        url: "",
        expiration_date: "",
      },
      filter: {
        name: "",
      },
      msg_error: "",
      create_notifications_users: create_notifications_users,
    };
  },
  created() {
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
    onSubmit() {
      $("[s26-required]").removeClass("is-invalid");
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un nombre.";
        return false;
      }
      if (this.form.description == "") {
        $("#form-description").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar una descripción.";
        return false;
      }
      if (this.form.expiration_date == "") {
        $("#form-expiration_date").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar una fecha.";
        return false;
      }

      let formData = json_to_formData(this.form);

      show_loader_points();
      axios
        .post("/users/setNotification", formData)
        .then((res) => {
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
      for (let form in this.form) {
        this.form[form] = "";
      }
    },

    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
  <div id="formNotifications" 
    class="s26-modal" 
    tabindex="-1"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="s26-modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            Nueva Notificación
          </h5>
          <button type="button" class="btn-close" @click="hideModal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="onSubmit">
            <div class="row">
              <div class="col-12" v-if="create_notifications_users == 1">
                <s26-form-select-user 
                  label="Seleccionar Usuario" 
                  size="sm" 
                  id="form-select_user" 
                  v-model="form.idUser" 
                  search="filter.name"
                >
                </s26-form-select-user>
              </div>
              <div class="col-12">
                <s26-form-input 
                  label="Nombre" 
                  size="sm" 
                  id="form-name" 
                  type="text" 
                  v-model="form.name" 
                  maxlength="100" 
                  text 
                  s26_required
                  :message="msg_error"
                >
                </s26-form-input>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label">Descripción</label>
                <textarea 
                  id="form-description"
                  class="form-control 
                  resize-none" 
                  cols="30"rows="3" 
                  v-model="form.description"
                  s26-required
                ></textarea>
                <p class="invalid-feedback">{{ msg_error }} </p>
              </div>
              <div class="col-12">
                <s26-form-input 
                  label="Url" 
                  size="sm" 
                  id="form-url" 
                  type="text" 
                  v-model="form.url" 
                  maxlength="1000" 
                  text
                >
                </s26-form-input>
              </div>
              <div class="col-12 mb-3">
                <s26-date-picker 
                  id="form-expiration_date"
                  enable="unique" 
                  size="sm" 
                  v-model="form.expiration_date" 
                  label="Fecha de Vencimiento" 
                  s26_required
                  :message="msg_error"
                  select_all_dates
                ></s26-date-picker>
              </div>
            </div>
            <button type="button" class="btn btn-outline-danger" v-if="value == 0" @click="onReset" >Resetear</button>
            <button type="button" class="btn btn-outline-danger" v-if="value !== 0" @click="infoData(value)" >Deshacer</button>
            <button type="submit" class="btn btn-s26-success" > {{ value == 0 ? 'Añadir' : 'Guardar' }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
`,
});
