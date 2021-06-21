let S26PhotosView = new Vue({
  el: "#s26-photos-view",
  data: function () {
    return {
      filter: {
        name: "",
        favorites: "",
        date: "",
        status: "",
      },
      rows: 0,
      items: [],
      perPage: 8,
      idRow: null,
      activeSidebar: true,
      activeUploadPhoto: false,
      action: "",
    };
  },
  created() {
    this.allRows();
  },
  methods: {
    allRows() {
      const params = {
        name: this.filter.name,
        date: this.filter.date,
        favorites: this.filter.favorites,
        status: this.filter.status,
        perPage: this.perPage,
      };
      axios
        .get("/photos/getPhotos/", {
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
    addToFavorites(id) {
      show_loader_points();
      axios
        .post("/photos/addToFavorites/" + id)
        .then((res) => {
          if (res.data.type == 1) {
            alertify.success(res.data.msg);
          } else {
            alertify.error(res.data.msg);
          }
          hide_loader_points();
          this.allRows();
        })
        .catch((e) => {
          console.log(e);
        });
    },
    filterFavorites() {
      this.filter.favorites = this.filter.favorites == 1 ? "" : 1;
      this.allRows();
    },
    onReset() {
      this.perPage = 8;
      for (fil in this.filter) {
        this.filter[fil] = "";
      }
      this.allRows();
    },
    setIdRow(id, type) {
      this.idRow = id;
      this.action = type;
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 8 ? this.perPage + 8 : this.rows;
      this.allRows();
    },
  },
});
Vue.component("s26-update-photo", {
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
        .get("/photos/getPhoto/" + id)
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

      let formData = json_to_formData(this.form);
      show_loader_points();
      axios
        .post("/photos/updatePhoto", formData)
        .then((res) => {
          if (res.data.type == 1) {
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
    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
    <div id="formPhoto" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Editar Foto
              {{ form.id }}
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <form id="formSetPhoto" @submit.prevent="onSubmit">
              <div class="row">
                <div class="col-6">
                  <div class="row">
                    <div class="col-12">
                      <s26-form-input 
                        label="Nombre" 
                        size="sm" 
                        id="form-name" 
                        type="text" 
                        v-model="form.name"
                        maxlength="100" 
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
                    <div class="col-12">
                        <s26-select-status 
                          lbl="Estado"
                          id="form-status" 
                          v-model="form.status"
                          s26_required
                        >
                        </s26-select-status>
                    </div>
                    <div class="col-12 mb-4"> 
                    <span class="fw-bold">Creado el:</span>
                    {{form.created_at}} 
                    </div>
                  </div>
                </div>
                <div class="col-6 h-100 s26-align-center">
                  <img :src="form.href" class="rounded shadow-sm w-100"/>
                </div>
              </div>
              <button type="button" class="btn btn-outline-danger" @click="infoData(id)" >
                Deshacer
              </button>
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
Vue.component("s26-watch-photo", {
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
        .get("/photos/getPhoto/" + id)
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
    hideModal() {
      this.$emit("input", null);
    },
  },
  template: `
    <div id="readPhoto" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Información de Foto
            </h5>
            <button type="button" class="btn-close" @click="hideModal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-6">
                <div class="row">
                  <div class="col-12">
                    <div class="mb-4">
                      <label class="form-label">Id</label>
                      <div class="form-control form-control-sm">{{ form.id }}</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-4">
                      <label class="form-label">Nombre</label>
                      <div class="form-control form-control-sm">{{ form.name }}</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-4">
                      <label class="form-label">Descripción</label>
                      <div class="form-control form-control-sm">{{ form.description }}</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-4">
                      <label class="form-label">Estado</label>
                      <div class="form-control form-control-sm">{{ form.status ? 'activo' : 'inactivo' }}</div>
                    </div>
                  </div>
                  <div class="col-12 mb-4"> 
                    <span class="fw-bold">Creado el:</span>
                    {{form.created_at}} 
                  </div>
                </div>
              </div>
              <div class="col-6 h-100 s26-align-center">
                <img :src="form.href" class="rounded shadow-sm w-100"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
});
