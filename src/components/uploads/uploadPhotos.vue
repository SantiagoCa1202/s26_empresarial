<template>
  <s26-modal
    id="uploadPhotos"
    @hideModal="hideModal"
    style_body="height: 465px;"
  >
    <template v-slot:header>
      <h5 class="modal-title">Subir Fotos</h5>
    </template>
    <template v-slot:body>
      <div class="row px-3">
        <div class="col-12 mb-3">
          <div class="row">
            <div class="col-12">
              <label class="btn btn-primary form-control">
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
            class="
              col-12
              p-2
              mb-3
              container-img
              d-flex
              rounded
              border
              shadow-sm
            "
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
                style="top: -4%; right: -1.5%"
                @click="remove_photo(index)"
              >
                <i class="fas fa-times"></i>
              </button>
            </transition>
          </div>
        </transition-group>
      </div>
    </template>
    <template v-slot:footer>
      <div class="col-12">
        <div class="row">
          <div class="col-6">
            <transition name="fade">
              <button
                v-if="upload_photos.length > 0"
                type="button"
                class="btn btn-outline-danger form-control"
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
                class="btn btn-primary form-control"
                @click="onSubmit"
              >
                Subir
                <span class="fw-bold fs-6">
                  {{ upload_photos.length }}
                </span>
                {{ upload_photos.length > 1 ? "fotos" : "foto" }}
              </button>
            </transition>
          </div>
        </div>
      </div>
    </template>
  </s26-modal>
</template>
<script>
export default {
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
      let formData = s26.json_to_formData(this.form);
      s26.show_loader_points();
      this.axios
        .post("/photos/uploadPhotos", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.data.status) {
            this.onReset();
            this.$alertify.success(res.data.msg);
          } else {
            this.$alertify.error(res.data.msg);
          }
          s26.hide_loader_points();
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
              this.$alertify.error(
                'La foto: </br> <span class="fw-bold s26-text-blue">"' +
                  fileimg[i].name +
                  '"</span> </br> no es válida'
              );
            } else {
              if (this.form.upload_photos_data.length < 10) {
                this.upload_photos.push(nav.createObjectURL(fileimg[i]));
                this.form.upload_photos_data.push(fileimg[i]);
              } else {
                this.$alertify.warning("Máximo 10 Fotos");
                return false;
              }
            }
          }
        } else {
          this.$alertify.warning("No se seleccionó ninguna foto");
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
};
</script>
