<template>
  <s26-modal id="formRolePermits" @hideModal="hideModal" size="lg">
    <template v-slot:header>
      <h5 class="modal-title">Permisos roles de usuario - {{ name }}</h5>
    </template>
    <template v-slot:body>
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
    </template>
    <template v-slot:footer>
      <button @click="onReset" class="btn btn-outline-danger">Resetear</button>
      <button class="btn btn-outline-primary" @click="infoData(id)">
        Deshacer
      </button>
      <button class="btn btn-outline-secondary" @click="selectAll">
        Permitir Todo
      </button>
      <button type="submit" class="btn btn-info" @click="onSubmit">
        Guardar
      </button>
    </template>
  </s26-modal>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
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
      form: {},
      name: "",
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
  },
  methods: {
    infoRol(id) {
      this.axios
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
      this.axios
        .get("/permits/getPermitsRol/" + id)
        .then((res) => {
          console.log(res);
          this.items = res.data.modules;
          this.form = res.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSubmit() {
      this.$alertify.confirm(
        `Desea Asignar los Permisos de Rol?.`,
        () => {
          $(".btn").attr("disabled", true);
          this.$alertify.warning("Asignando permisos");

          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
          this.axios
            .post("/permits/setPermits", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((res) => {
              if (res.data.status) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              s26.hide_loader_points();
              $(".btn").removeAttr("disabled");
            })
            .catch((e) => {
              console.log(e);
            });
        },
        () => {
          this.$alertify.error("Acción Cancelada");
        }
      );
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
};
</script>