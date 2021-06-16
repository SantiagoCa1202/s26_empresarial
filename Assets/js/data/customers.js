let S26CustomersView = new Vue({
  el: "#s26-customers-view",
  data: function () {
    return {
      fields: [
        {
          name: "C.I / Ruc",
          class: "length-int",
        },
        {
          name: "Razón Social",
          class: "length-description",
        },
        {
          name: "Teléfono",
          class: "length-int",
        },
        {
          name: "Celular",
          class: "length-int",
        },
        {
          name: "Dirección",
          class: "length-description",
        },
        {
          name: "Estado",
          class: "length-action",
        },
      ],
      filter: {
        id: "",
        document: "",
        name: "",
        email: "",
        date: "",
        status: "",
      },
      rows: 0,
      items: [],
      perPage: 25,
      idRow: null,
      activeSidebar: true,
      action: "",
      url_export: "",
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
        email: this.filter.email,
        date: this.filter.date,
        status: this.filter.status,
        perPage: this.perPage,
      };
      axios
        .get("/customers/getCustomers/", {
          params,
        })
        .then((res) => {
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
      this.url_export = url_get("/customers/exportCustomers/", params);
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
Vue.component("s26-form-customer", {
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
        document: "",
        address: "",
        phone: "",
        mobile: "",
        email: "",
        time_limit: "",
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
        .get("/customers/getCustomer/" + id)
        .then((res) => {
          this.form.id = res.data.id;
          this.form.name = res.data.full_name;
          this.form.document = res.data.document;
          this.form.address = res.data.address;
          this.form.phone = res.data.phone;
          this.form.mobile = res.data.mobile;
          this.form.email = res.data.email;
          this.form.time_limit = res.data.time_limit;
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
        .post("/customers/setCustomer", this.form)
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
      if (this.form.document.length < 10 || this.form.document.length > 13) {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error =
          "El número de documento debe contener (10 a 13) dígitos.";
        return false;
      }
      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (!this.validEmail(this.form.email)) {
        $("#form-email").addClass("is-invalid").focus();
        this.msg_error = "Email incorrecto.";
        return false;
      }
      if (this.form.phone.length !== 9) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Teléfono debe contener 9 dígitos.";
        return false;
      }

      if (this.form.mobile.length !== 10) {
        $("#form-phone").addClass("is-invalid").focus();
        this.msg_error = "Celular debe contener 10 dígitos.";
        return false;
      }

      if (this.form.status == "") {
        $("#form-status").addClass("is-invalid").focus();
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
<div id="formCustomer" 
class="s26-modal" 
tabindex="-1"
>
<div class="s26-modal-dialog s26-modal-dialog-centered">
  <div class="s26-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        {{ id !== 0 ? 'Editar Cliente ' + id : 'Nuevo Cliente' }}
      </h5>
      <button type="button" class="btn-close" @click="hideModal"></button>
    </div>
    <div class="modal-body">
      <form @submit.prevent="onSubmit">
        <div class="row">
          <div class="col-sm-4">
            <s26-form-input 
              label="Cédula / RUC " 
              length
              size="sm" 
              id="form-document" 
              type="text" 
              v-model="form.document" 
              maxlength="13" 
              minlength="10" 
              number 
              s26_required 
              :message="msg_error">
            </s26-form-input>
          </div>
          <div class="col-sm-8">
            <s26-form-input 
              label="Nombres / Razón Social" 
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
              label="Teléfono" 
              size="sm" 
              id="form-phone" 
              type="text" 
              v-model="form.phone" 
              maxlength="9" 
              number 
              length
              s26_required 
              :message="msg_error">
            </s26-form-input>
          </div>
          <div class="col-6">
            <s26-form-input 
              label="Celular" 
              size="sm" 
              id="form-mobile" 
              type="text" 
              v-model="form.mobile" 
              maxlength="10" 
              number 
              length
              s26_required 
              :message="msg_error">
            </s26-form-input>
          </div>
          <div class="col-sm-9">
            <s26-form-input 
              label="Dirección" 
              size="sm" 
              id="form-address" 
              type="text" 
              v-model="form.address" 
              maxlength="100" 
              text 
              s26_required 
              :message="msg_error">
            </s26-form-input>
          </div>
          <div class="col-sm-3">
            <s26-form-input 
              label="Plazo" 
              size="sm" 
              id="form-time_limit" 
              type="text" 
              v-model="form.time_limit" 
              maxlength="10" 
              placeholder="0 dias"
            >
            </s26-form-input>
          </div>
          <div class="col-7">
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
          <div class="col-5">
              <s26-select-status 
                lbl="Estado"
                id="form-status" 
                v-model="form.status"
                s26_required
              >
              </s26-select-status>
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
Vue.component("s26-watch-customer", {
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
  template: `
<div id="readCustomer" 
class="s26-modal" 
tabindex="-1"
>
<div class="s26-modal-dialog s26-modal-dialog-centered">
<div class="s26-modal-content">
<div class="modal-header">
  <h5 class="modal-title">
    Información de Cliente
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
    <div class="col-12">
      <div class="mb-4">
        <label class="form-label">Nombres</label>
        <div class="form-control form-control-sm">
          {{ form.name }}
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="mb-4">
        <label class="form-label">Teléfono</label>
        <div class="form-control form-control-sm">
          {{ form.phone }}
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="mb-4">
        <label class="form-label">Celular</label>
        <div class="form-control form-control-sm">
          {{ form.mobile }}
        </div>
      </div>
    </div>
    <div class="col-9">
      <div class="mb-4">
        <label class="form-label">Dirección</label>
        <div class="form-control form-control-sm">
          {{ form.address }}
        </div>
      </div>
    </div>
    <div class="col-3">
      <div class="mb-4">
        <label class="form-label">Plazo</label>
        <div class="form-control form-control-sm">
          {{ form.time_limit }}
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
        <label class="form-label">Estado</label>
        <div class="form-control form-control-sm">
          {{ form.status == 1 ? "Activo" : "Inactivo" }}
        </div>
      </div>
    </div>
    <div class="col-12">
      <span class="fw-bold">
        Creado el:  
      </span>
      {{form.created_at}}
    </div>
  </div>
</div>
</div>
</div>
</div>
`,
});
