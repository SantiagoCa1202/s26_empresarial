<template>
  <s26-modal-multiple
    id="modal_disable_product"
    title="Activar / Desactivar Productos"
    :levels="['informacion']"
    body_style="min-height: 500px;"
    @onSubmit="onSubmit"
    @onReset="onReset"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <h2 class="fw-600 h5 text-center">
          <span v-show="form.trademark != ''" class="h5 fw-600">
            {{ form.trademark }} -
          </span>
          <span v-show="form.name != ''" class="h5 fw-600">
            {{ form.name }}
          </span>
          <span v-show="form.model != ''" class="h6"> / {{ form.model }} </span>
        </h2>
      </div>
      <div class="col-12 mb-3 s26-align-center">
        <button
          class="btn btn-primary btn-sm mx-1"
          type="button"
          @click="toggle_product(null, 'activate')"
        >
          Activar
        </button>
        <button
          class="btn btn-danger btn-sm mx-1"
          type="button"
          @click="toggle_product(null, 'disable')"
        >
          Desactivar
        </button>
      </div>
      <div
        class="col-12 s26-shadow-md border rounded p-2 mb-3"
        v-for="(variant, index) in form.variants.items"
        :key="variant.id"
      >
        <h3 class="h6 mb-3">
          <span class="fw-bold me-2" title="código">
            {{ variant.ean_code }}
          </span>
          <span
            title="SKU"
            class="btn btn-sm btn-primary"
            v-show="variant.sku != ''"
          >
            {{ variant.sku }}
          </span>
          <button
            class="btn btn-danger btn-sm float-end mx-1"
            @click="toggle_product(index, 'disable')"
          >
            Desactivar
          </button>
          <button
            class="btn btn-primary btn-sm float-end mx-1"
            @click="toggle_product(index, 'activate')"
          >
            Activar
          </button>
        </h3>
        <div class="row p-2 mb-3">
          <div
            class="col-2 text-center"
            v-for="estab in variant.establishment_stock.items"
            :key="estab.id"
          >
            {{ estab.n_establishment.toString().padStart(3, "0") }}
            <div class="s26-checkbox-switch">
              <input
                :id="'switch_d' + estab.id"
                type="checkbox"
                v-model="estab.status"
                :disabled="estab.stock > 0"
              />
              <label :for="'switch_d' + estab.id"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4 mb-2" v-show="variant.color_id > 0">
            <span class="fw-600">Color:</span>
            <span
              class="fw-bold"
              :style="{ color: `#${variant.color.hexadecimal}` }"
            >
              <s26-icon icon="palette"></s26-icon>
              {{ variant.color.name }}
            </span>
          </div>
          <div class="col-4 mb-2" v-show="variant.size != ''">
            <span class="fw-600">Talla:</span>
            {{ variant.size }}
          </div>
          <div class="col-4 mb-2" v-show="variant.fragance != ''">
            <span class="fw-600">Fragancia:</span>
            {{ variant.fragance }}
          </div>
          <div class="col-4 mb-2" v-show="variant.net_content != ''">
            <span class="fw-600">Cont. Neto:</span>
            {{ variant.net_content }}
          </div>
          <div class="col-4 mb-2" v-show="variant.shape != ''">
            <span class="fw-600">Forma:</span>
            {{ variant.shape }}
          </div>
          <div class="col-4 mb-2" v-show="variant.package != ''">
            <span class="fw-600">Bulto:</span>
            {{ variant.package }}
          </div>
          <div class="col-4 mb-2">
            <span class="fw-600">Stock:</span>
            {{ variant.establishment_stock.info.stock }}
          </div>
          <div class="col-12 mb-2" v-show="variant.additional_info != ''">
            <span class="fw-600">Info. Adicional:</span>
            {{ variant.additional_info }}
          </div>
        </div>
      </div>
    </template>
  </s26-modal-multiple>
</template>

<script>
const def_form = () => {
  return {
    variants: {
      items: [],
    },
  };
};
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
  data: function () {
    return {
      form: def_form(),
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/products/getProduct/" + id)
        .then((res) => {
          this.form = res.data;
        })
        .catch((err) => console.log(err));
    },
    disableProduct(product_id) {
      this.setIdRow(product_id, "disable");
      this.infoData(product_id);
    },
    toggle_product(var_id, type) {
      let toggle = type == "activate" ? 1 : 0;
      if (var_id == null) {
        for (let i = 0; i < this.form.variants.items.length; i++) {
          for (
            let e = 0;
            e <
            this.form.variants.items[i]["establishment_stock"]["items"].length;
            e++
          ) {
            if (
              this.form.variants.items[i]["establishment_stock"]["items"][e][
                "stock"
              ] == 0
            ) {
              this.form.variants.items[i]["establishment_stock"]["items"][e][
                "status"
              ] = toggle;
            }
          }
        }
      } else {
        for (
          let e = 0;
          e <
          this.form.variants.items[var_id]["establishment_stock"]["items"]
            .length;
          e++
        ) {
          if (
            this.form.variants.items[var_id]["establishment_stock"]["items"][e][
              "stock"
            ] == 0
          ) {
            this.form.variants.items[var_id]["establishment_stock"]["items"][e][
              "status"
            ] = toggle;
          }
        }
      }
    },
    onSubmit() {
      this.$alertify.confirm(
        `Desea Guardar los Cambios?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/products/toggleProduct", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.$alertify.success(res.data.msg);
                this.hideModal();
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
            })
            .catch((err) => console.log(err));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
      $("[s26-required]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>