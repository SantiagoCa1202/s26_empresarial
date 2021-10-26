<template>
  <div class="container-img-estructure">
    <!-- IMAGEN UNICA GRANDE -->
    <div
      class="select-img-photo"
      v-if="(value == '' || value == 0) && !multiple && !min"
      @click="activeSelectPhoto = true"
    >
      Seleccionar Foto 1000x1000
    </div>
    <div
      class="img-photo"
      v-if="value !== '' && value != 0 && !multiple && !min"
      @click="activeSelectPhoto = true"
    >
      <img :id="'photo-' + id" :src="info_photo.href" />
      <transition name="fade">
        <button
          type="button"
          class="btn-icon btn-clear-img"
          v-if="value !== ''"
          @click="remove_photo"
        >
          <s26-icon icon="times"></s26-icon>
        </button>
      </transition>
    </div>
    <!-- IMAGEN MULTIPLE GRANDE -->
    <button
      type="button"
      class="btn btn-primary w-100 mb-3"
      v-if="multiple"
      @click="activeSelectPhoto = true"
    >
      <s26-icon icon="images"></s26-icon>
      Seleccionar Fotos
    </button>
    <div class="row w-100" v-if="multiple">
      <div
        :class="['position-relative', 'col-' + col]"
        v-for="(photo, index) in info_photos"
        :key="index"
      >
        <img
          class="w-100 shadow-sm"
          :src="photo.href"
          :alt="photo.description"
        />
        <button
          type="button"
          class="btn-icon btn-clear-img"
          @click="remove_photo(photo.id)"
        >
          <s26-icon icon="times"></s26-icon>
        </button>
      </div>
    </div>
    <transition name="fade">
      <div
        id="selectPhotos"
        class="s26-modal"
        tabindex="-1"
        v-if="activeSelectPhoto"
      >
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="s26-modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Seleccionar Foto</h5>
              <button
                type="button"
                class="btn-close"
                @click="activeSelectPhoto = false"
              ></button>
            </div>
            <div class="modal-body overflow-auto pt-0" style="height: 465px">
              <div class="row">
                <div class="col-12 mb-1 pt-3 shadow-sm header-select-photo">
                  <div class="row">
                    <div class="col-5">
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="activeUploadPhoto = true"
                      >
                        <s26-icon icon="cloud-upload-alt"></s26-icon>
                      </button>
                      <button
                        type="button"
                        class="btn btn-outline-primary"
                        @click="allRows"
                      >
                        <s26-icon icon="sync-alt"></s26-icon>
                      </button>
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
                    :class="[
                      'rounded w-100 min-img',
                      selected_photos.indexOf(parseInt(item.id)) > -1
                        ? 'checked'
                        : '',
                    ]"
                    :src="item.href"
                    @keyup.13="selectPhoto(parseInt(item.id))"
                    tabindex="0"
                  />
                </div>
              </div>
              <div
                v-if="rows == 0"
                class="
                  w-100
                  h-75
                  text-secondary
                  fs-1
                  fw-bold
                  s26-align-center
                  pointer
                "
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
                      <span class="fw-bold fs-5">
                        {{ selected_photos.length }}
                      </span>
                    </div>
                  </div>
                  <div class="col-4 s26-align-y-center">
                    <div class="s26-text-blue s26-align-center w-100">
                      <span class="fw-bold fs-5">
                        {{ perPage }}
                      </span>
                      <span class="text-lowercase"> de </span>
                      <span class="fw-bold fs-5">
                        {{ rows }}
                      </span>
                    </div>
                  </div>
                  <div class="col-4">
                    <button
                      type="button"
                      class="btn btn-info float-end"
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
    <!-- IMAGEN UNICA MINIATURA -->
    <div class="mb3" v-if="min">
      <label class="form-label text-start">
        Foto
        <s26-icon
          class="text-danger"
          icon="times"
          v-if="value !== '' && value > 0"
          @click="remove_photo"
        ></s26-icon>
      </label>
      <button
        type="button"
        :class="[
          'btn btn-sm w-100 text-with',
          value > 0 ? 'btn-primary' : 'btn-outline-secondary',
        ]"
        @click="activeSelectPhoto = true"
      >
        <s26-icon icon="images"></s26-icon>
      </button>
    </div>
    <transition name="fade">
      <s26-upload-photos
        v-if="activeUploadPhoto"
        v-model="activeUploadPhoto"
        @update="allRows"
      >
      </s26-upload-photos>
    </transition>
    {{ val }}
  </div>
</template>
<script>
export default {
  props: {
    label: String,
    id: String,
    value: {
      default: "",
    },
    multiple: Boolean,
    min: Boolean,
    col: {
      default: 3,
    },
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
      info_photos: [],
    };
  },
  created() {
    setTimeout(() => {
      this.allRows();
    }, 100);
  },
  computed: {
    val: function () {
      if (this.value != 0 && !this.multiple) {
        this.getPhoto(this.value);
      } else if (this.value.length > 0 && this.multiple) {
        this.getPhotos();
      }
    },
  },
  methods: {
    allRows() {
      const params = {
        name: this.search,
        perPage: this.perPage,
      };
      this.axios
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
    getPhoto(id) {
      this.axios
        .get("/photos/getPhoto/" + id)
        .then((res) => {
          this.info_photo = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getPhotos() {
      const params = {
        arrPhotos: this.value.toString(),
      };
      this.axios
        .get("/photos/getPhotosIn/", {
          params,
        })
        .then((res) => {
          console.log(res);
          this.info_photos = res.data.items;
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
        setTimeout(() => {
          this.getPhotos();
        }, 100);
      } else {
        this.$emit("input", this.selected_photos[0]);
      }
      this.selected_photos = [];
      this.activeSelectPhoto = false;
    },
    remove_photo(id = "") {
      if (this.multiple) {
        let del = this.value.indexOf(parseInt(id));
        let new_arr = this.value;
        new_arr.splice(del, 1);
        this.$emit("input", new_arr);
        this.getPhotos();
      } else {
        this.$emit("input", "");
        this.selected_photos = [];
      }
    },
    loadMore() {
      let perPage = this.rows - this.perPage;
      this.perPage = perPage > 8 ? this.perPage + 8 : this.rows;
      this.allRows();
    },
  },
};
</script>
<style scoped>
.container-img-estructure {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
}

.select-img-photo,
.img-photo {
  width: 223px;
  height: 223px;
  border: 0.3rem dashed #e5e5e5;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-size: 1.25rem;
  color: #c8c8c8;
  cursor: pointer;
}

.img-photo input {
  display: none;
}

.img-photo img {
  width: 223px;
  height: 223px;
  border-radius: 0.5rem;
  border: 1px solid #bebebe;
  box-shadow: 0px 3px 7px 0px rgb(0 0 0 / 31%);
}

.btn-clear-img {
  position: absolute;
  top: -0.5rem;
  right: -0.5rem;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bs-danger);
  color: var(--bs-white);
}

.option-icon {
  font-size: 1.2rem;
}
</style>