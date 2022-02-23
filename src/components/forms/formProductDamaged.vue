<template>
  <s26-modal-multiple
    id="formProductDamaged"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Producto Averiado'"
    size="md"
    :levels="levels"
    body_style="min-height: 465px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12">
        <s26-input-read
          label="Producto"
          :title="form.product"
          :content="form.product"
          variant_input="overflow-hidden"
        >
        </s26-input-read>
      </div>
      <div class="col-2">
        <s26-input-read label="Costo" :content="form.cost" money>
        </s26-input-read>
      </div>
      <div class="col-2">
        <s26-input-read label="Stock" :content="form.stock"> </s26-input-read>
      </div>

      <div class="col-2">
        <div class="s26-align-center">
          <s26-form-input
            label="Cant."
            v-model="form.amount"
            type="number"
            number
            mb="0"
            :disabled="form.stock <= 0"
            variant="text-center"
            @keyup="valStock"
            @blur="
              form.amount = form.amount == '' ? 1 : form.amount;
              valStock;
            "
          >
          </s26-form-input>
          <span
            class="px-1 text-danger"
            v-if="form.stock < 1"
            title="Ajustar Stock"
            @click="adjustment.variant_id = form.product_variant_id"
          >
            <s26-icon icon="wrench"></s26-icon>
          </span>
        </div>
      </div>
      <div class="col-3 mb-3">
        <label class="form-label">
          Estado
          <span class="text-danger">
            <s26-icon icon="asterisk" class="icon_asterisk_required"></s26-icon>
          </span>
        </label>
        <select
          id="form-product_status"
          class="form-select form-select-sm"
          v-model="form.product_status"
          s26-required="true"
        >
          <option value="">seleccionar</option>
          <option value="por reclamar">Por Reclamar</option>
          <option value="reclamado">Reclamado</option>
          <option value="sin solución">Sin Solución</option>
          <option value="solucionado">Solucionado</option>
        </select>
        <p class="invalid-feedback">Seleccione un Estado</p>
      </div>
      <div class="col-3">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
          s26_required
        >
        </s26-select-status>
      </div>
      <div class="col-12">
        <s26-editor
          id="form-description"
          label="Descripción"
          :height="value == 0 ? 250 : 210"
          v-model="form.description"
          s26_required
        ></s26-editor>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
      </div>
      <!-- BUSCAR PRODUCTOS -->
      <transition name="fade">
        <s26-select-product
          @select_product="select_product"
          v-show="modal_options == 'select_products'"
          v-model="modal_options"
        ></s26-select-product>
      </transition>
      <!--  AJUSTE DE STOCK  -->
      <transition name="fade">
        <s26-modal-multiple
          v-if="adjustment.variant_id > 0"
          id="modal-stock_adjustment"
          title="Ajuste de Stock"
          :levels="['informacion']"
          body_style="height: 165px;"
          @hideModal="adjustment.variant_id = null"
          @onSubmit="stock_adjustment"
        >
          <template v-slot:level-0>
            <div class="col-12 mb-2">
              <h2 class="h5 fw-600 text-center s26-text-blue">
                {{ form.product }}
              </h2>
            </div>
            <div class="col-4">
              <s26-form-input
                label="Cantidad"
                type="tel"
                v-model="adjustment.amount"
                maxlength="3"
                number
                s26_required
              >
              </s26-form-input>
            </div>
            <div class="col-4">
              <s26-input-read label="Stock Actual" :content="form.stock">
              </s26-input-read>
            </div>
            <div class="col-4">
              <s26-input-read
                label="Nuevo Stock"
                :content="parseInt(form.stock) + parseInt(adjustment.amount)"
              >
              </s26-input-read>
            </div>
          </template>
        </s26-modal-multiple>
      </transition>
    </template>
    <template v-slot:level-1>
      <div class="col-12">
        <s26-select-buys
          label="N° de Documento"
          id="form-document_id"
          v-model="form.document_id"
          @change="getBuy(form.document_id)"
          is_null
          assign
          s26_required
        ></s26-select-buys>
      </div>
      <div class="col-4">
        <s26-input-read label="Ruc" :content="buy.document"> </s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Razón Social" :content="buy.business_name">
        </s26-input-read>
      </div>
    </template>
    <template v-slot:aditional-btns>
      <button
        type="button"
        class="btn btn-outline-primary float-end mx-1"
        @click="modal_options = 'select_products'"
      >
        Cambiar Producto
      </button>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    id: "",
    product_variant_id: "",
    ean_code: "",
    product: "",
    cost: 0,
    stock: 0,
    amount: 1,
    product_status: "",
    document_id: "",
    description: "",
    status: "",
    created_at: "",
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
      buy: {},
      levels: ["Información de Producto", "Información de Compra"],
      adjustment: {
        variant_id: 0,
        amount: 1,
      },
      modal_options: "select_products",
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
      this.modal_options = null;
    }
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/productsDamageds/getProductDamaged/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${
          this.id == 0 ? "Ingresar " : "Actualizar"
        } Producto Averiado?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/productsDamageds/setProductDamaged", formData)
            .then((res) => {
              if (res.data.type == 1) {
                this.onReset();
                this.$alertify.success(res.data.msg);
              } else if (res.data.type == 2) {
                this.$alertify.success(res.data.msg);
              } else {
                this.$alertify.error(res.data.msg);
              }
              $s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => console.log(e));
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

    getBuy(id) {
      if (id > 0) {
        this.axios
          .get("/buys/getBuy/" + id)
          .then((res) => (this.buy = res.data))
          .catch((err) => console.log(err));
      } else {
        this.buy = {};
      }
    },

    select_product: function (code) {
      const params = {
        code,
      };
      this.axios
        .get("/products/searchProduct/", { params })
        .then((res) => {
          if (res.data != 0) {
            const prod = res.data[0];
            this.form.product_variant_id = prod["id"];
            this.form.ean_code = prod["ean_code"];
            this.form.product = `${prod["ean_code"]} / ${prod["name"]} / ${prod["model"]} / ${prod["trademark"]} / ${prod["sku"]} `;
            this.form.cost = prod["cost"];
            this.form.stock = prod["stock"];
            if (prod["stock"] <= 0) {
              this.$alertify.error(
                "Producto Sin Stock. <br> Se Debe Realizar Un Ajuste de Stock."
              );
            }
          } else {
            this.$alertify.error("No Se Encontro Ningún Producto");
          }
        })
        .catch((err) => console.log(err));
    },

    valStock() {
      let amount = parseInt(this.form.amount);
      let stock = parseInt(this.form.stock);

      if (amount > stock) {
        this.form.amount = this.form.stock;
        this.$alertify.error("Stock Insuficiente");
      } else if (amount <= 0) {
        this.form.amount = 1;
        this.$alertify.error("Cantidad no puede ser menor a 1");
      }
    },

    stock_adjustment() {
      if (this.adjustment.variant_id > 0) {
        this.$alertify.confirm(
          `Desea Ajustar Stock`,
          () => {
            let formData = $s26.json_to_formData(this.adjustment);
            $s26.show_loader_points();
            this.axios
              .post("/products/stockAdjustment", formData)
              .then((res) => {
                this.$alertify.success(res.data.msg);

                this.select_product(this.form.ean_code);
                this.adjustment.variant_id = null;
                $s26.hide_loader_points();
              })
              .catch((e) => console.log(e));
          },
          () => {
            this.$alertify.error("No se guardo la venta.");
          }
        );
      } else {
        this.$alertify.error("Error al realizar ajuste de stock.");
      }
    },
  },
};
</script>