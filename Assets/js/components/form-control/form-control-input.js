Vue.component("s26-form-input", {
  props: {
    label: String,
    id: String,
    message: {
      type: String,
      default: "",
    },
    type: String,
    size: String,
    maxlength: String,
    minlength: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    name: String,
    text: Boolean,
    number: Boolean,
    email: Boolean,
    s26_required: Boolean,
    autofocus: Boolean,
    autocomplete: String,
    length: Boolean,
  },
  data: function () {
    return {};
  },
  created() {},
  methods: {},
  template: `
    <div class="mb-3">
      <label :for="id" class="form-label" v-if="label"> 
        {{ label }} 
        
      </label>
      <span v-if="length && value.length > 0" :class="['fw-bold float-end', value.length == maxlength || value.length == minlength ? 'text-success' : 'text-danger']">
          {{ length ? value.length : '' }}
        </span>
      <input 
        :type="type" 
        :class="['form-control form-control-' + size, variant ]"
        :id="id" 
        :maxlength="maxlength" 
        :minlength="minlength" 
        :placeholder="placeholder"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.value)"  
        @keyup="$emit('keyup')"
        :text="text"
        :number="number"
        :email="email"
        :s26-required="s26_required"
        :autofocus="autofocus"
        :autocomplete="autocomplete"
        :name="name"
      >
      <p class="invalid-feedback" v-if="s26_required">{{ message }} </p>
    </div>
  `,
});

Vue.component("s26-input-search", {
  props: {
    label: String,
    id: String,
    message: {
      type: String,
      default: "",
    },
    type: String,
    size: String,
    maxlength: String,
    minlength: String,
    placeholder: String,
    variant: {
      type: String,
      default: "",
    },
    value: {},
    name: String,
    text: Boolean,
    number: Boolean,
    email: Boolean,
    s26_required: Boolean,
    autofocus: Boolean,
    autocomplete: String,
    length: Boolean,
  },
  data: function () {
    return {
      focus: false,
    };
  },
  created() {},
  methods: {
    update() {
      this.$emit("update");
      this.$emit("search");
    },
    cancel() {
      this.$emit("input", "");
      this.$emit("update");
      this.$emit("search");
    },
  },
  template: `
    <div class="mb-3">
      <label :for="id" class="form-label" v-if="label"> 
        {{ label }} 
      </label>
      <div :class="['s26-container-search', focus ? 'focus' : '']" >
        <div class="s26-btn s26-btn-search">
          <button 
            type="button" 
            @click="update"
            @focus="focus = true" 
            @blur="focus = false" 
          >
            <i class="fas fa-search"></i>
          </button>
        </div>
        <div class="s26-input-search">
          <input type="text" 
            :id="id" 
            :maxlength="maxlength" 
            :minlength="minlength" 
            :placeholder="placeholder ? placeholder : 'Buscar...'"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"  
            @keyup="$emit('keyup')"
            @keyup.13="update"
            @keyup.27="cancel"
            :text="text"
            :number="number"
            :email="email"
            :s26-required="s26_required"
            :autofocus="autofocus"
            :autocomplete="autocomplete"
            :name="name"
            @focus="focus = true" 
            @blur="focus = false" 
          />
        </div>
        <div class="s26-btn s26-btn-cancel">
          <transition name="fade">  
            <button 
              type="button" 
              @click="cancel"
              @focus="focus = true" 
              @blur="focus = false"
              v-if="value !== ''" 
            >
              <i class="fas fa-times"></i>
            </button>
          </transition>
        </div>
      </div>
      <p class="invalid-feedback" v-if="s26_required">{{ message }} </p>
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
    setTimeout(() => {
      $(
        `html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-custom-select-${this.id})`
      ).on("click", (e) => {
        this.activeSelect(false);
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
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect(active = true) {
      this.isActive = active;

      if (this.isActive) {
        let s26SelectUser = document.getElementById(this.id);
        this.position.top =
          s26SelectUser.getBoundingClientRect().bottom >= 500
            ? "-148px"
            : "55px";

        setTimeout(() => {
          $(".s26-select-container-input-search input").focus();
          val_inputs();
        }, 100);

        this.allRows();

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
        class="s26-select-container active"  
        :style="position"
      >
        <div class="w-100 p-1">
          <s26-input-search 
            v-model="search" 
            @search="allRows" 
          />
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

Vue.component("s26-select-icon", {
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
      options: [],
      info_icon: {
        selected: "",
        icon: "",
      },
      search: "",
      perPage: 50,
      rows: 0,
      position: {
        top: "0",
      },
    };
  },
  created() {
    setTimeout(() => {
      $(
        `html, .s26-modal, .s26-modal-content, .s26-popup:not(#s26-custom-select-${this.id})`
      ).on("click", (e) => {
        this.activeSelect(false);
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
        .get("/users/getIcons/", {
          params,
        })
        .then((res) => {
          this.options = res.data.items;
          this.rows = res.data.info.count;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getIcon(id) {
      axios
        .get("/users/getIcon/" + id)
        .then((res) => {
          console.log(res);
          console.log(this.value);
          this.info_icon.icon = res.data.class;
          this.info_icon.name = res.data.name;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    activeSelect(active = true) {
      this.isActive = active;

      if (this.isActive) {
        let s26SelectIcon = document.getElementById(this.id);
        this.position.top =
          s26SelectIcon.getBoundingClientRect().bottom >= 500
            ? "-148px"
            : "55px";

        setTimeout(() => {
          $(".s26-select-container-input-search input").focus();
          val_inputs();
        }, 100);

        this.allRows();
      } else {
        this.search = "";
      }
    },
    selectOption(id, value, icon) {
      this.isActive = false;
      this.search = "";
      this.perPage = 50;
      this.$emit("input", id);
      this.info_icon.icon = icon;
      this.info_icon.name = value;
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
    <div class="row mx-0">
      <div class="col-4 s26-align-y-center">
        <i :class="['option-icon text-secondary', value == 1 || value == '' ? 'fas fa-project-diagram' : info_icon.icon]"></i>
      </div>
      <div :class="['s26-align-y-center fw-bold', 'col-8']">
        {{ value == 1 || value == '' ? 'default' : info_icon.name }}
      </div>
    </div>
      <span :class="['icon-sort-down-select', {active: isActive}]">
        <i class="fas fa-sort-down"></i>
      </span>
    </div>
      <transition name="fade">
        <div 
          v-if="isActive"
          class="s26-select-container active"  
          :style="position"
        >
          <div class="w-100 p-1">
            <s26-input-search 
              v-model="search" 
              @search="allRows" 
            />
          </div>
          <div class="s26-select-container-options">
            <div 
              :class="['s26-select-options row mx-0', value == option.id ?  'focus' : '']" 
              tabindex="0" 
              v-for="option in options" :key="option.id"
              @click="selectOption(option.id, option.name, option.class)"
            >
              <div class="col-3">
                <i :class="['option-icon text-secondary', option.class]"></i>
              </div>
              <div class="col-9 s26-align-y-center fw-bold">
                {{ option.name }}
              </div>
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

Vue.component("s26-input-photo", {
  props: {
    label: String,
    id: String,
    value: {},
    multiple: Boolean,
  },
  data: function () {
    return {
      search: "",
      perPage: 8,

      activeSelectPhoto: false,
      activeUploadPhoto: false,
      selected_photos: [],
      items: [],
      rows: 0,
      upload_photos: [],
      upload_photos_data: {},
      info_photo: {},
    };
  },
  created() {
    this.allRows();
  },
  methods: {
    allRows() {
      const params = {
        name: this.search,
        perPage: this.perPage,
      };
      axios
        .get("/photos/getPhotos/", {
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
    getPhoto(id) {
      axios
        .get("/photos/getPhoto/" + id)
        .then((res) => {
          this.info_photo = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    selectPhoto(id) {
      let photo_id = parseInt(id);
      let i = this.selected_photos.indexOf(photo_id);

      if (i !== -1) {
        this.selected_photos.splice(i, 1);
      } else {
        if (this.multiple) {
          this.selected_photos.push(photo_id);
        } else {
          if (this.selected_photos.length == 0) {
            this.selected_photos.push(photo_id);
          }
        }
      }
    },
    addPhotos() {
      if (this.multiple) {
        this.$emit("input", this.selected_photos);
      } else {
        this.$emit("input", this.selected_photos[0]);
        this.getPhoto(this.selected_photos[0]);
      }
      this.selected_photos = [];
      this.activeSelectPhoto = false;
    },
    remove_photo(id) {
      this.$emit("input", "");
      this.selected_photos = [];
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 8 ? this.perPage + 8 : this.rows;
      this.allRows();
    },
  },
  template: `
  <div class="container-img-category">
    <div class="select-img-category" @click="activeSelectPhoto = true">
      {{ value == '' ? 'Seleccionar Foto 1000x1000' : '' }}
      <img 
        :id="'photo-'+ id"
        v-if="value !== ''"
        :src="info_photo.src"
      />
    </div>
    <transition name="fade">
      <button 
        type="button" 
        class="btn-icon btn-clear-img" 
        v-if="value !== ''"@click="remove_photo"
      >
        <i class="fas fa-times"></i>
      </button>
    </transition>
    <transition name="fade">
      <div id="selectPhotos" 
        class="s26-modal" 
        tabindex="-1"
        v-if="activeSelectPhoto"
      >
        <div class="modal-dialog modal-lg modal-dialog-centered ">
          <div class="s26-modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                Seleccionar Foto
                {{ value }}
              </h5>
              <button 
                type="button" 
                class="btn-close" 
                @click="activeSelectPhoto = false"
              ></button>
            </div>
            <div class="modal-body overflow-auto pt-0" style="height: 465px;">
              <div class="row">
                <div class="col-12 mb-1 pt-3 shadow-sm header-select-photo">
                  <div class="row">
                    <div class="col-1">
                      <button 
                        type="button"
                        class="btn btn-primary" 
                        @click="activeUploadPhoto = true"
                      >
                        <i class="fas fa-cloud-upload-alt"></i>
                      </button>
                    </div>
                    <div class="col-1">
                      <button type="button" class="btn btn-outline-primary" @click="allRows">
                        <i class="fas fa-sync-alt"></i>
                      </button>
                    </div>
                    <div class="col-3">               
                      <button 
                        type="button" 
                        class="btn btn-outline-primary"
                        @click="loadMore"
                        v-if="perPage < rows"
                      >
                        Cargar Más...
                      </button>
                    </div>
                    <div class="col-3"></div>
                    <div class="col-4">
                      <s26-input-search 
                        v-model="search" 
                        @search="allRows" 
                        @update="perPage = 8" 
                      />
                    </div>
                  </div>
                </div>
                <div 
                  class="col-6 col-md-3 my-2 px-2 container-img" 
                  v-for="item in items"
                  :key="item.id"
                  @click="selectPhoto(parseInt(item.id))"
                >
                  <img 
                    :class="['rounded w-100 min-img', 
                    selected_photos.indexOf(parseInt(item.id)) > -1 ? 'checked' : ''
                    ]" 
                    :src="item.src" 
                    @keyup.13="selectPhoto(parseInt(item.id))"
                    tabindex="0"
                  />
                </div>
              </div>
              <div 
                v-if="rows == 0"
                class="w-100 h-75 text-secondary fs-1 fw-bold s26-align-center pointer" 
                @click="activeUploadPhoto = true"
              >
                  Subir Fotos
              </div>
            </div>
            <div class="modal-footer">
              <div class="col-12 mb-1">
                <div class="row">
                  <div class="col-4 s26-align-y-center">
                    <div class="s26-text-blue w-100 s26-align-y-center"> 
                      fotos seleccionadas: 
                      &nbsp 
                      <span class="fw-bold fs-5">
                        {{ selected_photos.length }}
                      </span>
                    </div>
                  </div>
                  <div class="col-4 s26-align-y-center">
                    <div class="s26-text-blue  s26-align-center w-100"> 
                      <span class="fw-bold fs-5">
                        {{ perPage }}
                      </span>
                      &nbsp 
                      <span class="text-lowercase">
                        de
                      </span>
                      &nbsp 
                      <span class="fw-bold fs-5">
                        {{ rows }} 
                      </span>
                    </div>
                  </div>
                  <div class="col-4">
                    <button 
                      type="button" 
                      class="btn btn-success float-end"
                      @click="addPhotos"
                      v-if="selected_photos.length > 0"
                    >
                      Aceptar
                    </button>
                    <button 
                      type="button" 
                      class="btn btn-outline-danger float-end me-2"
                      @click="selected_photos = []"
                      v-if="selected_photos.length > 0"
                    >
                      Limpiar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <s26-upload-photos 
        v-if="activeUploadPhoto" 
        v-model="activeUploadPhoto" 
        @update="allRows"
      />
    </transition>
  </div>
`,
});

Vue.component("s26-upload-photos", {
  data: function () {
    return {
      message: "",
      selected_photos: [],
      items: [],
      rows: 0,
      row: 0,
      upload_photos: [],
      form: {
        upload_photos_data: [],
      },
    };
  },
  created() {},
  methods: {
    onSubmit() {
      let formData = json_to_formData(this.form);
      show_loader_points();
      axios
        .post("/photos/setPhotos", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          console.log(res);
          if (res.data.status) {
            this.onReset();
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
    validate_image() {
      let input_img = document.querySelector("#inputUploadPhotos");
      if (input_img) {
        let uploadFoto = input_img.value;
        let fileimg = input_img.files;
        let nav = window.URL || window.webkitURL;

        if (uploadFoto != "") {
          for (let i = 0; i < fileimg.length; i++) {
            let type = fileimg[i].type;
            if (
              type != "image/jpeg" &&
              type != "image/jpg" &&
              type != "image/png"
            ) {
              alertify.error(
                'La foto: </br> <span class="fw-bold s26-text-blue">"' +
                  fileimg[i].name +
                  '"</span> </br> no es válida'
              );
            } else {
              if (this.form.upload_photos_data.length < 10) {
                this.upload_photos.push(nav.createObjectURL(fileimg[i]));
                this.form.upload_photos_data.push(fileimg[i]);
              } else {
                alertify.warning("Máximo 10 Fotos");
                return false;
              }
            }
          }
        } else {
          alertify.warning("No se seleccionó ninguna foto");
          input_img.value = "";
        }
      }
    },
    onReset() {
      this.upload_photos = [];
      this.form = {};
      this.form.upload_photos_data = [];
    },
    remove_photo(id) {
      this.upload_photos.splice(id, 1);
      delete this.form.upload_photos_data[id];
      this.form.upload_photos_data = startFromZero(
        this.form.upload_photos_data
      );
    },
    hideModal() {
      this.$emit("input", false);
    },
  },
  template: `
    <div id="uploadPhotos" 
      class="s26-modal" 
      tabindex="-1"
    >
      <div class="modal-dialog modal-dialog-centered ">
        <div class="s26-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              Subir Fotos
            </h5>
            <button 
              type="button" 
              class="btn-close" 
              @click="hideModal"
            ></button>
          </div>
          <div class="modal-body overflow-auto" style="height: 465px;">
            <div class="row px-3">
              <div class="col-12 mb-3">
                <div class="row">
                  <div class="col-12">
                    <label 
                      class="btn btn-primary form-control" 
                    >
                      <i class="fas fa-cloud-upload-alt"></i>
                      Seleccionar Fotos (1000 x 1000)
                      <input 
                        id="inputUploadPhotos"
                        accept="image/png,image/jpeg" 
                        type="file" 
                        class="d-none" 
                        multiple 
                        @change="validate_image"
                      />
                    </label>
                  </div>
                </div>
              </div>
              <transition-group name="list" tag="div">
                <div 
                  class="col-12 p-2 mb-3 container-img d-flex rounded border shadow-sm" 
                  v-for="(item, index) in upload_photos"
                  :key="item"
                >
                  <img 
                    class="rounded w-50 shadow-sm min-img hover-none" 
                    :src="item" 
                  />
                  <div class="p-2 w-50">
                    <div class="row">
                      <div class="col-12">
                        <s26-form-input 
                          label="Nombre" 
                          size="sm" 
                          :id="'name_' + index" 
                          type="text" 
                          maxlength="11"
                          v-model="form['name_' + index]"
                        ></s26-form-input>
                      </div>
                      <div class="col-12">
                        <s26-form-input 
                          label="Descripción" 
                          size="sm" 
                          :id="'description_' + index" 
                          type="text" 
                          maxlength="100"
                          v-model="form['description_' + index]"
                          ></s26-form-input>
                      </div>
                    </div>
                  </div>
                  <transition name="fade">
                    <button 
                      type="button" 
                      class="btn-icon btn-clear-img" 
                      style="top: -4%;right: -1.5%;"
                      @click="remove_photo(index)"
                    >
                      <i class="fas fa-times"></i>
                    </button>
                  </transition>
                </div>  
              </transition-group>               
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-12">
              <div class="row">
                <div class="col-6">
                  <transition name="fade">
                    <button 
                      v-if="upload_photos.length > 0"
                      type="button" 
                      class="btn btn-outline-danger form-control "
                      @click="onReset"
                    >
                      Cancelar
                    </button>
                  </transition>  
                </div>
                <div class="col-6">
                  <transition name="fade">
                    <button 
                      v-if="upload_photos.length > 0"
                      type="button" 
                      class="btn btn-primary form-control "
                      @click="onSubmit"
                    >
                      Subir 
                      &nbsp
                      <span class="fw-bold fs-6"> 
                        {{ upload_photos.length }} 
                      </span>
                      &nbsp 
                      {{ upload_photos.length > 1 ? 'fotos' : 'foto' }}
                    </button>
                  </transition>  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
`,
});
