let S26CategoriesView = new Vue({
  el: "#s26-categories-view",
  data: function () {
    return {
      fields: [
        {
          name: "Icono",
          class: "length-action",
        },
        {
          name: "Nombre",
          class: "length-description",
        },
        {
          name: "Descripción",
          class: "length-description",
        },
        {
          name: "Estado",
          class: "length-action",
        },
      ],
      filter: {
        id: "",
        name: "",
        description: "",
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
        name: this.filter.name,
        description: this.filter.description,
        date: this.filter.date,
        status: this.filter.status,
        perPage: this.perPage,
      };
      axios
        .get("/categories/getCategories/", {
          params,
        })
        .then((res) => {
          console.log(res)
          this.items = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
      this.url_export = url_get("/categories/exportCategories/", params);
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
Vue.component("s26-form-category", {
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
        description: "",
        photo_id: "",
        icon_id: 1,
        color: "#243a46",
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
        .get("/categories/getCategory/" + id)
        .then((res) => {
          this.form = res.data;
          this.$refs.inputPhoto.getPhoto(res.data.photo_id);
          this.$refs.selectIcon.getIcon(res.data.icon_id);
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
      console.log(this.form);

      let formData = json_to_formData(this.form);
      show_loader_points();
      axios
        .post("/categories/setCategory", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
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
      for (let i in this.form) {
        this.form[i] = "";
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    valForm() {
      $("[s26-required]").removeClass("is-invalid");

      if (this.form.name == "") {
        $("#form-name").addClass("is-invalid").focus();
        this.msg_error = "Nombres requeridos.";
        return false;
      }
      if (this.form.description == "") {
        $("#form-document").addClass("is-invalid").focus();
        this.msg_error = "Es necesario ingresar un número de cédula.";
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
  },
  template: `
<div id="formCategory" 
class="s26-modal" 
tabindex="-1"
>
<div class="modal-dialog modal-dialog-centered">
  <div class="s26-modal-content">
    <div class="modal-header">
      <h5 class="modal-title">
        {{ id !== 0 ? 'Editar Categoria ' + id : 'Nueva Categoria' }}
      </h5>
      <button type="button" class="btn-close" @click="hideModal"></button>
    </div>
    <div class="modal-body">
      <form id="formSetCategory" @submit.prevent="onSubmit">

        <div class="row">
          <div class="col-6">
            <div class="row">
              <div class="col-sm-12">
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
              <div class="col-12 mb-3">
                <label class="form-label">Descripción</label>
                <textarea 
                  id="form-description"
                  class="form-control resize-none" 
                  cols="30" 
                  rows="5" 
                  v-model="form.description"
                  s26-required
                ></textarea>
                <p class="invalid-feedback">{{ msg_error }} </p>
              </div>
              
              <div class="col-9">
                  <s26-select-status 
                    lbl="Estado"
                    id="form-status" 
                    v-model="form.status"
                    s26_required
                  >
                  </s26-select-status>
              </div>
              <div class="col-3 mb-3">
                <label>Color</label>
                <input type="color" class="form-control form-control-color" v-model="form.color" >
              </div>
              <div class="col-12 mb-3">
                <s26-select-icon 
                  label="Seleccionar Icono" 
                  size="sm" 
                  id="form-select_icon" 
                  v-model="form.icon_id" 
                  search="filter.icon"
                  ref="selectIcon"
                >
                </s26-select-icon>
              </div>
            </div>
          </div>
          <div class="col-6 s26-align-center">
            <s26-input-photo ref="inputPhoto" id="form-img" v-model="form.photo_id">
            </s26-input-photo>
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
Vue.component("s26-watch-category", {
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
      form: {},
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
        .get("/categories/getCategory/" + id)
        .then((res) => {
          this.form = res.data;
          this.form.photo = res.data.photo.src;
          this.form.icon = res.data.icon.class;
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
<div id="readCategory" 
  class="s26-modal" 
  tabindex="-1"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="s26-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Información de Categoria
        </h5>
        <button type="button" class="btn-close" @click="hideModal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-8">
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="mb-4">
                  <label class="form-label">Id</label>
                  <div class="form-control form-control-sm">{{ form.id }}</div>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-4">
                  <label class="form-label">Nombre</label>
                  <div class="form-control form-control-sm">
                    {{ form.name }}
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
              <div class="col-12">
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
          <div class="col-4">
            <div class="p-2 h-50 s26-align-center">
              <div class="w-100 h-100 rounded s26-align-center" :style="'background:' + form.color">
                <i :class="['fs-1 text-white', form.icon]"></i>
              </div>
            </div>
            <div class="p-2 h-50 s26-align-center">
              <div class="w-100 h-100 rounded s26-align-center" :style="'background:' + form.color">
                <img :src="form.photo" class="rounded w-100" />
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
