let S26RolesView = new Vue({
  el: "#s26-roles-view",
  data: function () {
    return {
      fields: [
        {
          name: "nombre",
          class: "length-description",
        },
        {
          name: "descripción",
          class: "length-description",
        },
        {
          name: "estado",
          class: "length-status",
        },
      ],
      filter: {
        id: "",
        name: "",
        description: "",
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
        name: this.filter.name,
        description: this.filter.description,
        status: this.filter.status,
        date: this.filter.date,
        perPage: this.perPage,
      };
      axios
        .get("/roles/getRoles/", {
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
Vue.component("s26-form-role", {
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
  data() {
    return {
      form: {
        id: "",
        name: "",
        description: "",
        status: 1,
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
        .get("/roles/getRol/" + id)
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
      this.form.id = this.id;
      if (!this.valForm()) {
        return false;
      }
      show_loader_points();
      axios
        .post("/roles/setRol", this.form)
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
      $("[s26-required]").removeClass("is-invalid");
    },
    valForm() {
      $("#name, #description").removeClass("is-invalid");

      if (this.form.name == "") {
        $("#name").addClass("is-invalid").focus();
        this.msg_error = "Nombre requerido.";
        return false;
      }
      if (this.form.description == "") {
        $("#description").addClass("is-invalid").focus();
        this.msg_error = "Descripción requerido.";
        return false;
      }
      return true;
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
    <div id="formRoles" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ id !== 0 ? 'Editar Rol' : 'Nuevo Rol' }}
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col-12 col-sm-6" v-if="id !== 0">
                  <div class="mb-4">
                    <label class="form-label">Id</label>
                    <div class="form-control form-control-sm">{{ form.id }}</div>
                  </div>
                </div>
                <div class="col">
                  <s26-form-input 
                    label="Nombre" 
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
                <div class="col-12">
                  <s26-form-input 
                    label="Descripción" 
                    size="sm" 
                    id="form-description" 
                    type="text" 
                    v-model="form.description" 
                    maxlength="100" 
                    text 
                    s26_required 
                    :message="msg_error">
                  </s26-form-input>
                </div>
                <div class="col-12">
                  <s26-select-status 
                    id="form-status" 
                    v-model="form.status"
                    s26_required
                  >
                  </s26-select-status>
                </div>
                <div class="col-12 mb-4"v-if="id !== 0"> 
                  {{form.created_at}} 
                </div>
              </div>
              <button 
                type="button" 
                class="btn btn-outline-danger" 
                v-if="id == 0" 
                @click="onReset"
              >
                Resetear
              </button>
              <button 
                type="button" 
                class="btn btn-outline-danger" 
                v-if="id !== 0" 
                @click="infoData(id)"
              >
                Deshacer
              </button>
              <button 
                type="button" 
                class="btn btn-s26-success" 
                @click="onSubmit"
              >
                {{ id == 0 ? "Añadir" : "Guardar" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  `,
});
Vue.component("s26-roles-permits", {
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
  data() {
    return {
      fields: [
        {
          name: "Modulo",
          class: "length-description",
        },
        {
          name: "Leer",
          class: "length-price text-center",
        },
        {
          name: "Escribir",
          class: "length-price text-center",
        },
        {
          name: "Actualizar",
          class: "length-price text-center",
        },
        {
          name: "Eliminar",
          class: "length-price text-center",
        },
      ],
      items: [],
      form: [],
      name: "",
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
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
    infoRol(id) {
      axios
        .get("/roles/getRol/" + id)
        .then((res) => {
          this.name = res.data.name;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    infoData(id) {
      this.infoRol(this.id);
      axios
        .get("/permits/getPermitsRol/" + id)
        .then((res) => {
          this.items = res.data.modules;
          this.form = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSubmit() {
      $(".btn").attr("disabled", true);
      alertify.warning("Asignando permisos");
      show_loader_points();
      axios
        .post("/permits/setPermits", this.form)
        .then((res) => {
          if (res.data.status) {
            alertify.success(res.data.msg);
          } else {
            alertify.error(res.data.msg);
          }
          hide_loader_points();
          $(".btn").removeAttr("disabled");
        })
        .catch((e) => {
          console.log(e);
        });
    },
    onReset() {
      for (let i in this.items) {
        this.form.modules[i]["permits"]["r"] = 0;
        this.form.modules[i]["permits"]["w"] = 0;
        this.form.modules[i]["permits"]["u"] = 0;
        this.form.modules[i]["permits"]["d"] = 0;
      }
    },
    selectAll() {
      for (let i in this.items) {
        this.form.modules[i]["permits"]["r"] = 1;
        this.form.modules[i]["permits"]["w"] = 1;
        this.form.modules[i]["permits"]["u"] = 1;
        this.form.modules[i]["permits"]["d"] = 1;
      }
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
    <div id="roles-permits" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="s26-modal-dialog s26-modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Permisos roles de usuario - {{ name }}
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form>
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th class="length-int">id</th>
                    <th
                      v-for="field in fields"
                      :key="field.id"
                      :class="[field.class]"
                    >
                      {{ field.name }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in items" :key="index">
                    <td class="length-int">{{ item.id }}</td>
                    <td class="length-description">{{ item.name }}</td>
                    <td class="length-price text-center">
                      <div class="s26-checkbox-switch">
                        <input
                          type="checkbox"
                          :id="'switch_r' + item.id"
                          v-model="form.modules[index]['permits']['r']"
                        />
                        <label :for="'switch_r' + item.id"></label>
                      </div>
                    </td>
                    <td class="length-price text-center">
                      <div class="s26-checkbox-switch">
                        <input
                          type="checkbox"
                          :id="'switch_w' + item.id"
                          v-model="form.modules[index]['permits']['w']"
                        />
                        <label :for="'switch_w' + item.id"></label>
                      </div>
                    </td>
                    <td class="length-price text-center">
                      <div class="s26-checkbox-switch">
                        <input
                          type="checkbox"
                          :id="'switch_u' + item.id"
                          v-model="form.modules[index]['permits']['u']"
                        />
                        <label :for="'switch_u' + item.id"></label>
                      </div>
                    </td>
                    <td class="length-price text-center">
                      <div class="s26-checkbox-switch">
                        <input
                          type="checkbox"
                          :id="'switch_d' + item.id"
                          v-model="form.modules[index]['permits']['d']"
                        />
                        <label :for="'switch_d' + item.id"></label>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>

            </form>
          </div>
          <div class="modal-footer">
          <button @click="onReset" class="btn btn-outline-danger" @click="onReset">
                Resetear
              </button>
              <button class="btn btn-outline-primary" @click="infoData(id)">
                Deshacer
              </button>
              <button class="btn btn-outline-secondary" @click="selectAll">
                Permitir Todo
              </button>
              <button type="submit" class="btn btn-s26-success" @click="onSubmit">
                Guardar
              </button>
          </div>
        </div>
      </div>
    </div>
  `,
});
