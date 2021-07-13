<template>
  <s26-modal
    id="uploadFiles"
    @hideModal="hideModal"
    style_body="height: 465px;"
  >
    <template v-slot:header>
      <h5 class="modal-title">Subir Archivos</h5>
    </template>
    <template v-slot:body>
      <div class="row px-3">
        <div class="col-12 mb-3">
          <div class="row">
            <div class="col-12">
              <label class="btn btn-primary form-control">
                <s26-icon icon="cloud-upload-alt"></s26-icon>
                Seleccionar Archivos
                <input
                  id="inputUploadFiles"
                  type="file"
                  class="d-none"
                  accept="application/pdf"
                  multiple
                  @change="validate_file"
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
            style="height: 200px"
            v-for="(item, index) in upload_files"
            :key="item.name"
          >
            <div class="w-25 h-100 s26-align-center" style="font-size: 5rem">
              <s26-icon icon="file-pdf" class="text-danger"></s26-icon>
            </div>
            <div class="p-2 w-100">
              <div class="row">
                <div class="col-12">
                  <s26-form-input
                    label="Nombre"
                    size="sm"
                    :id="'name_' + index"
                    type="text"
                    maxlength="100"
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
              <p>{{ item.name }}</p>
            </div>
            <transition name="fade">
              <button
                type="button"
                class="btn-icon btn-clear-img"
                style="top: -4%; right: -1.5%"
                @click="remove_file(index)"
              >
                <s26-icon icon="times"></s26-icon>
              </button>
            </transition>
          </div>
        </transition-group>
        <p>
          recomendamos guardar todos los archivos con el nombre y tipo de
          documento.
        </p>
        <p>Ejemplo: Factura: 001-001-032659872</p>
      </div>
    </template>
    <template v-slot:footer>
      <div class="col-12">
        <div class="row">
          <div class="col-6">
            <transition name="fade">
              <button
                v-if="upload_files.length > 0"
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
                v-if="upload_files.length > 0"
                type="button"
                class="btn btn-primary form-control"
                @click="onSubmit"
              >
                Subir
                <span class="fw-bold fs-6">
                  {{ upload_files.length }}
                </span>
                {{ upload_files.length > 1 ? "fotos" : "foto" }}
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
      items: [],
      rows: 0,
      row: 0,
      upload_files: [],
      form: {
        upload_files_data: [],
      },
    };
  },
  created() {},
  methods: {
    onSubmit() {
      let formData = s26.json_to_formData(this.form);
      s26.show_loader_points();
      this.axios
        .post("/files/uploadFiles", formData, {
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
    validate_file() {
      let input_file = document.querySelector("#inputUploadFiles");
      if (input_file) {
        let uploadFile = input_file.value;
        let file = input_file.files;
        let nav = window.URL || window.webkitURL;

        if (uploadFile != "") {
          for (let i = 0; i < file.length; i++) {
            let type = file[i].type;
            if (type != "application/pdf") {
              this.$alertify.error(
                'El Archivo: </br> <span class="fw-bold s26-text-blue">"' +
                  file[i].name +
                  '"</span> </br> no es válido'
              );
            } else if (!this.searchDocument(file[i].name)) {
              this.$alertify.error(
                'El Archivo: </br> <span class="fw-bold s26-text-blue">"' +
                  file[i].name +
                  '"</span> </br> ya se encuentra cargado'
              );
            } else {
              if (this.form.upload_files_data.length < 10) {
                this.upload_files.push({
                  src: nav.createObjectURL(file[i]),
                  name: file[i].name,
                });
                this.form.upload_files_data.push(file[i]);
              } else {
                this.$alertify.warning("Máximo 10 Archivos");
                return false;
              }
            }
          }
        } else {
          this.$alertify.warning("No se seleccionó ningun archivo");
        }
        input_file.value = "";
      }
    },
    searchDocument(name) {
      for (let i in this.upload_files) {
        if (this.upload_files[i]["name"] == name) {
          return false;
        }
      }
      return true;
    },
    onReset() {
      this.upload_files = [];
      this.form = {};
      this.form.upload_files_data = [];
    },
    remove_file(id) {
      this.upload_files.splice(id, 1);
      delete this.form.upload_files_data[id];
      this.form.upload_files_data = s26.startFromZero(
        this.form.upload_files_data
      );
    },
    hideModal() {
      this.$emit("input", false);
    },
  },
};
</script>
