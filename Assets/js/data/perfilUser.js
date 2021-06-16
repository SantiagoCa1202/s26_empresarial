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
          console.log(res);
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
          console.log(res);
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
          console.log(res);
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
    <div class="s26-modal-dialog s26-modal-dialog-centered">
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
      axios
        .post("/users/setNote", this.form)
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
    <div class="s26-modal-dialog s26-modal-dialog-centered">
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
Vue.component("s26-form-select-user", {
  props: {
    label: String,
    id: String,
    message: {
      type: String,
      default: "",
    },
    size: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    s26_required: Boolean,
  },
  data: function () {
    return {
      isActive: false,
      selected: "",
      options: [],
      search: "",
      perPage: 50,
      rows: 0,
      position: {
        top: "0",
      },
    };
  },
  created() {
    val_inputs();
    setTimeout(() => {
      $(
        `html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-custom-select-${this.id})`
      ).on("click", (e) => {
        this.isActive = false;
      });
      $(`#s26-custom-select-${this.id}`).click(function (e) {
        e.stopPropagation();
      });
    }, 100);
  },
  methods: {
    allRows() {
      const params = {
        name: this.search,
        perPage: this.perPage,
      };
      axios
        .get("/users/getUsers/", {
          params,
        })
        .then((res) => {
          console.log(res);
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect() {
      this.isActive = !this.isActive;

      if (this.isActive) {
        let s26SelectUser = document.getElementById(this.id);
        this.position.top =
          s26SelectUser.getBoundingClientRect().bottom >= 500
            ? "-148px"
            : "55px";

        setTimeout(() => {
          $(".s26-select-container").addClass("active");
        }, 100);

        this.allRows();

        $(".s26-select-container-input-search input").focus();
        $(".s26-select-container").animate(
          {
            scrollTop: 0,
          },
          0
        );
      }
    },
    selectOption(id, value) {
      this.isActive = false;
      this.search = "";
      this.perPage = 50;
      this.$emit("input", id);
      this.selected = value;
    },
    loadMore() {
      this.perPage = this.perPage + 25;
      this.allRows();
    },
  },
  template: `
  <div :id="'s26-custom-select-' + id" class="s26-custom-select s26-popup mb-3">
    <label :for="id" class="form-label" v-if="label"> {{ label }} </label>
    <div 
      :id="id" 
      :class="['form-control form-control-' + size,'s26-select-value', variant ]"
      tabindex="0"
      @click="activeSelect"
      @keyup.13="activeSelect"
    >
    
    <div> {{ value != 0 ? selected : '-- seleccionar --' }} </div>
    <span :class="['icon-sort-down-select', {active: isActive}]">
        <i class="fas fa-sort-down"></i>
      </span>
    </div>
      <transition name="fade">
      <div 
        v-if="isActive"
        class="s26-select-container"  
        :style="position"
      >
        <div class="s26-select-container-input-search">
          <input 
            type="text" 
            text
            class="form-control form-control-sm" 
            placeholder="Buscar..."
            v-model="search"
            @keyup.107="allRows"
          />
          <transition name="fade">
            <button v-if="search != ''" title="buscar (+)" type="button" class="s26-btn-search"   @click="allRows">
              <i class="fas fa-search"></i>
            </button>
          </transition>
        </div>
        <div class="s26-select-container-options">
          <div 
            :class="['s26-select-options', value == 0 ? 'focus' : '']" 
            tabindex="0" 
            @click="$emit('input', 0)"
          >
            -- seleccionar --
          </div>
          <div 
            :class="['s26-select-options', value == option.id ? 'focus' : '']" 
            tabindex="0" 
            v-for="option in options" :key="option.id"
            @click="selectOption(option.id, option.name)"
          >
            {{ option.name }} - {{ option.document }}
          </div>
          <button 
            v-if="perPage < rows"
            type="button" 
            class="btn btn-link" 
            @click="loadMore"
          >
            Cargar Mas..
          </button>
        </div>
      </div>
      </transition>

    <p class="invalid-feedback" v-if="s26_required">{{ message }} </p>
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
      show_loader_points();
      axios
        .post("/users/setNotification", this.form)
        .then((res) => {
          console.log(res);
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
  <div id="formNotes" 
    class="s26-modal" 
    tabindex="-1"
  >
    <div class="s26-modal-dialog s26-modal-dialog-centered">
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
                  cols="30"rows="5" 
                  v-model="form.description"
                  s26-required
                  :message="msg_error"
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
