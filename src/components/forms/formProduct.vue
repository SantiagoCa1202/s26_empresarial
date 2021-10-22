<template>
  <s26-modal-multiple
    id="formProduct"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Producto'"
    size="xl"
    :levels="levels"
    body_style="min-height: 450px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
    ref="modal_multiple"
  >
    <!-- INFORMACION PRINCIPAL -->
    <template v-slot:level-0>
      <div class="col-2" v-if="id > 0">
        <s26-input-read label="Id" :content="form.id"> </s26-input-read>
      </div>
      <div class="col">
        <s26-form-input
          label="Nombre"
          id="form-name"
          v-model="form.name"
          maxlength="100"
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-editor
          id="form-description"
          label="Descripción"
          height="295"
          v-model="form.description"
          s26_required
        ></s26-editor>
      </div>
    </template>
    <template v-slot:level-1>
      <!-- DATOS GENERALES -->
      <div class="col-12 mb-3">
        <div class="col-12 mb-3 row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Datos Generales</h2>
          <div class="col-4 col-sm-2">
            <s26-form-input
              label="Marca"
              id="form-trademark"
              v-model="form.trademark"
              maxlength="100"
            >
            </s26-form-input>
          </div>
          <div class="col-4 col-sm-2">
            <s26-form-input
              label="Modelo"
              id="form-model"
              v-model="form.model"
              maxlength="100"
            >
            </s26-form-input>
          </div>
          <div class="col-4 col-sm-2">
            <s26-select-category
              id="form-category"
              v-model="form.category_id"
              s26_required
            ></s26-select-category>
          </div>
          <div class="col-4 col-sm-2">
            <s26-select-provider
              id="form-providers"
              v-model="form.providers"
              s26_required
              multiple
              is_null
            ></s26-select-provider>
          </div>
          <div class="col-4 col-sm-2 mb-3">
            <label class="form-label"> Tipo de Producto </label>
            <select class="form-select form-select-sm" v-model="form.type">
              <option value="original">Original</option>
              <option value="réplica">réplica</option>
            </select>
          </div>
          <div class="col-4 col-sm-2 mb-3">
            <label class="form-label"> Tipo </label>
            <select
              class="form-select form-select-sm"
              v-model="form.type_product"
            >
              <option value="producto">Producto</option>
              <option value="servicio">Servicio</option>
            </select>
          </div>
        </div>
      </div>
      <!-- APROBACIONES -->
      <div class="col-12 mb-3">
        <div class="row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Aprobaciones</h2>
          <div class="col-4 col-sm-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.status"
                id="form-status"
              />
              <label class="form-check-label" for="form-status">
                {{ form.status ? "activo" : "inactivo" }}.
              </label>
            </div>
          </div>
          <div class="col-4 col-sm-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.discontinued"
                id="form-discontinued"
              />
              <label class="form-check-label" for="form-discontinued">
                Descontinuado.
              </label>
            </div>
          </div>
          <div class="col-4 col-sm-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.remanufactured"
                id="form-remanufactured"
              />
              <label class="form-check-label" for="form-remanufactured">
                Remanufacturado.
              </label>
            </div>
          </div>
          <div class="col-6 col-sm-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.pvp_manual"
                id="form-pvp_manual"
              />
              <label class="form-check-label" for="form-pvp_manual">
                PVP Manual.
              </label>
            </div>
          </div>
          <div class="col-6 col-sm-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.discount"
                id="form-discount"
              />
              <label class="form-check-label" for="form-discount">
                Descuento.
              </label>
            </div>
          </div>
        </div>
      </div>
      <!-- INFO. DOC. DE COMPRA -->
      <div class="col-12 mb-3">
        <div class="row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Info. Documento de Compra</h2>
          <div class="col-sm-3">
            <s26-select-buys
              label="N° de Doc."
              id="form-document_id"
              v-model="form.document_id"
              @change="getBuy(form.document_id)"
              is_null
              assign
              s26_required
            ></s26-select-buys>
          </div>
          <div class="col-sm-2">
            <s26-input-read label="Ruc" :content="buy.document">
            </s26-input-read>
          </div>
          <div class="col-sm-5">
            <s26-input-read label="Razón Social" :content="buy.business_name">
            </s26-input-read>
          </div>
          <div class="col-2 mb-3">
            <label class="form-label"> Iva </label>
            <select class="form-select form-select-sm" v-model="form.iva">
              <option value="0">0%</option>
              <option value="12">12%</option>
            </select>
          </div>
        </div>
      </div>
      <!-- SERIADO -->
      <div class="col-12 mb-3">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="form.serial"
            id="form-serial"
          />
          <label class="form-check-label" for="form-serial">
            Maneja Seriado.
          </label>
        </div>
      </div>
      <transition name="fade">
        <div class="col-12 mb-5" v-if="form.serial">
          <div class="row mx-0 rounded py-2 shadow border">
            <h2 class="h5 fw-bold s26-text-blue">Seriado</h2>
            <div class="col-12">
              <s26-form-input
                id="form-serie"
                v-model="serial"
                maxlength="50"
                placeholder="Escriba o Escanee el número de serie del producto correspondiente"
                @enter="addSeries"
              >
              </s26-form-input>
            </div>
            <div class="series col-12">
              <transition-group name="list" tag="div">
                <div
                  class="col-3 px-0"
                  v-for="(serie, index) in form.series"
                  :key="serie"
                >
                  <div
                    class="
                      s26-align-center
                      btn btn-primary btn-sm
                      m-2
                      text-break
                    "
                    @click="addSeries(index)"
                  >
                    {{ serie }}
                    <span class="ms-2">
                      <s26-icon icon="times"></s26-icon>
                    </span>
                  </div>
                </div>
              </transition-group>
            </div>
          </div>
        </div>
      </transition>
    </template>
    <template v-slot:level-2>
      <!-- VARIANTES -->
      <transition-group name="list" tag="div">
        <div
          class="col-12 row mx-0 rounded py-2 s26-shadow-md border mb-4"
          v-for="(variant, index) in form.variants"
          :key="variant.code"
        >
          <div class="col-2">
            <s26-input-read
              label="Código"
              :content="variant.code"
            ></s26-input-read>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Sku"
              id="form-variant-sku"
              v-model="variant.sku"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-select-status
              label="Estado"
              id="form-variant-status"
              v-model="variant.status"
              s26_required
            >
            </s26-select-status>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Cantidad"
              id="form-variant-amount"
              type="number"
              v-model="variant.amount"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Stock Mínimo"
              id="form-variant-min_stock"
              type="number"
              v-model="variant.min_stock"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Stock Máximo"
              id="form-variant-max_stock"
              type="number"
              v-model="variant.max_stock"
              number
              s26_required
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Costo"
              id="form-variant-cost"
              v-model="variant.cost"
              type="number"
              step=".01"
              money
              s26_required
              placeholder="50.00"
              @keyup="calc_utility(index)"
              @enter="apply_iva(index)"
              title="presiona enter para calcular iva"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Utilidad %"
              id="form-variant-utility"
              v-model="variant.utility"
              type="number"
              s26_required
              placeholder="40"
              @keyup="calc_utility(index)"
              percentage
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="PVP 1"
              id="form-variant-pvp_1"
              v-model="variant.pvp_1"
              type="number"
              step=".01"
              money
              s26_required
              placeholder="60.00"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="PVP 2"
              id="form-variant-pvp_2"
              v-model="variant.pvp_2"
              type="number"
              step=".01"
              money
              placeholder="70.00"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="PVP 3"
              id="form-variant-pvp_3"
              v-model="variant.pvp_3"
              type="number"
              step=".01"
              money
              placeholder="80.00"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="PVP Distribuidor"
              id="form-variant-pvp_distributor"
              v-model="variant.pvp_distributor"
              type="number"
              step=".01"
              money
              placeholder="80.00"
            >
            </s26-form-input>
          </div>
          <div class="col-8">
            <s26-form-input
              label="Info. Adicional"
              id="form-variant-additional_info"
              v-model="variant.additional_info"
            >
            </s26-form-input>
          </div>
          <div class="col-3 mb-3">
            <label class="form-label text-start">variantes</label>
            <button
              type="button"
              class="btn btn-sm w-100 text-with btn-outline-primary"
              @click="toggle_modal_variants(index)"
            >
              Especificar Variantes
            </button>
          </div>
          <div class="col-1 s26-align-center">
            <button
              v-if="index > 0"
              type="button"
              class="btn-icon text-danger"
              @click="form.variants.splice(index, 1)"
            >
              <s26-icon icon="minus"></s26-icon>
            </button>
          </div>
          <div class="col-12" style="height: 125px; overflow: auto">
            <s26-input-photo
              id="form-variant-img"
              v-model="variant.photos"
              multiple
              col="1"
            >
            </s26-input-photo>
          </div>
        </div>
      </transition-group>
      <!-- AÑADIR VARIANTES -->
      <transition name="fade">
        <s26-modal-multiple
          id="modal_variants"
          title="Variantes"
          :levels="['informacion']"
          body_style="min-height: 440px;"
          v-if="id_variant != null"
          @onReset="onResetVariant"
          @onSubmit="saveVariants"
          @hideModal="toggle_modal_variants"
        >
          <template v-slot:level-0>
            <div
              :class="[
                'col-12 row variants',
                active_variants.color ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Color
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.color ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.color = !active_variants.color"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.color ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-select-color v-model="variants.color_id">
                </s26-select-color>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.size ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Talla
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.size ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.size = !active_variants.size"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.size ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-form-input
                  v-model="variants.size"
                  placeholder="Ej: 3 a 6 meses"
                >
                </s26-form-input>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.fragance ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Fragancia
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.fragance ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.fragance = !active_variants.fragance"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.fragance ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-form-input
                  v-model="variants.fragance"
                  placeholder="Ej: Limon"
                >
                </s26-form-input>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.net_content ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Contenido Neto
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.net_content
                      ? 'text-danger'
                      : 'text-primary',
                  ]"
                  @click="
                    active_variants.net_content = !active_variants.net_content
                  "
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.net_content ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-form-input
                  v-model="variants.net_content"
                  placeholder="Ej: 50ml / 50l / 50gr / 50kg"
                >
                </s26-form-input>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.shape ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Forma
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.shape ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.shape = !active_variants.shape"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.shape ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-form-input
                  v-model="variants.shape"
                  placeholder="Ej: Triangulo"
                >
                </s26-form-input>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.package ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Bulto
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.package ? 'text-danger' : 'text-primary',
                  ]"
                  @click="active_variants.package = !active_variants.package"
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.package ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="col-12">
                <s26-form-input
                  v-model="variants.package"
                  placeholder="Ej: 4 unidades"
                >
                </s26-form-input>
              </div>
            </div>
            <div
              :class="[
                'col-12 row variants',
                active_variants.dimensions ? 'h-auto' : '',
              ]"
            >
              <h2 class="h5 fw-bold mb-3 s26-text-blue">
                Dimensiones
                <span
                  :class="[
                    'float-end pointer',
                    active_variants.dimensions ? 'text-danger' : 'text-primary',
                  ]"
                  @click="
                    active_variants.dimensions = !active_variants.dimensions
                  "
                >
                  <s26-icon
                    class="fw-bold"
                    :icon="active_variants.dimensions ? 'minus' : 'plus'"
                  ></s26-icon>
                </span>
              </h2>
              <div class="w-100 px-4">
                <h3 class="h6 fw-600 s26-text-blue">
                  <s26-icon icon="shopping-bag"></s26-icon>
                  Producto
                </h3>
                <div class="row px-3 py-1">
                  <div class="col-6">
                    <s26-form-input
                      label="Largo"
                      v-model="variants.dimensions.product_length"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-6">
                    <s26-form-input
                      label="Alto"
                      v-model="variants.dimensions.product_height"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-6">
                    <s26-form-input
                      label="Ancho"
                      v-model="variants.dimensions.product_width"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-6">
                    <s26-form-input
                      label="Peso (kg)"
                      v-model="variants.dimensions.product_weight"
                      placeholder="Valor en kg."
                      number
                    >
                    </s26-form-input>
                  </div>
                </div>
              </div>
              <div class="w-100 px-4">
                <h3 class="h6 fw-600 s26-text-blue">
                  <s26-icon icon="box-open"></s26-icon>
                  Caja / Empaque
                </h3>
                <div class="row px-3 py-1">
                  <div class="col-4">
                    <s26-form-input
                      label="Largo"
                      v-model="variants.dimensions.box_length"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-4">
                    <s26-form-input
                      label="Alto"
                      v-model="variants.dimensions.box_height"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-4">
                    <s26-form-input
                      label="Ancho"
                      v-model="variants.dimensions.box_width"
                      placeholder="Valor en cm."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-6">
                    <s26-form-input
                      label="Peso (kg) (Prod. + Caja)"
                      v-model="variants.dimensions.box_weight"
                      placeholder="Valor en kg."
                      number
                    >
                    </s26-form-input>
                  </div>
                  <div class="col-6">
                    <s26-form-input
                      label="Apilamiento Max."
                      v-model="variants.dimensions.box_stacking"
                      placeholder="Maximo de Cajas Apiladas."
                      number
                    >
                    </s26-form-input>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </s26-modal-multiple>
      </transition>
      <button
        type="button"
        class="btn btn-primary w-100"
        @click="action = 'val_code'"
      >
        Añadir Variante
      </button>
      <!-- Modal Validar Codigo En Variantes-->
      <transition name="slide-fade">
        <s26-form-val-code
          v-model="action"
          v-if="action == 'val_code'"
          @get_code="get_code"
        ></s26-form-val-code>
      </transition>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    // EN PRODUCTOS
    id: "",
    name: "",
    description: "",
    trademark: "",
    model: "",
    type_product: "producto",
    type: "original",
    remanufactured: 0,
    category_id: "",
    discontinued: 0,
    serial: 0,
    discount: 0,
    pvp_manual: 0,
    iva: 12,
    status: 1,
    // EN PRODUCTOS SERIES
    series: [],
    // EN PRODUCTOS VARIANTES
    variants: [
      {
        code: "",
        sku: "",
        status: 1,
        amount: "",
        min_stock: "",
        max_stock: "",
        cost: "",
        pvp_1: "",
        pvp_2: "",
        pvp_3: "",
        pvp_distributor: "",
        additional_info: "",
        variants: def_variants(),
        photos: "",
      },
    ],
    // EN PRODUCTOS PROVEEDORES
    providers: [],
    // EN PRODUCTOS ENTRADAS
    document_id: "",
  };
};
const def_variants = () => {
  return {
    color_id: "",
    size: "",
    fragance: "",
    net_content: "",
    shape: "",
    package: "",
    dimensions: {
      product_length: "",
      product_height: "",
      product_width: "",
      product_weight: "",
      box_length: "",
      box_height: "",
      box_width: "",
      box_weight: "",
      box_stacking: "",
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
    code: {},
  },
  data: function () {
    return {
      action: null,
      form: def_form(),
      buy: {},
      serial: "",
      levels: ["Información Principal", "Información General", "Variantes"],
      id_variant: null,
      variants: def_variants(),
      active_variants: {
        color: false,
        size: false,
        fragance: false,
        net_content: false,
        shape: false,
        package: false,
        dimensions: false,
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
    this.form.variants[0]["code"] = this.code;
  },
  methods: {
    infoData(id) {
      // this.axios
      //   .get("/bankAccounts/getBankAccount/" + id)
      //   .then((res) => (this.form = res.data))
      //   .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.form.ean_code = this.code;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar" : "Actualizar"} el Producto?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/products/setProduct", formData)
            .then((res) => {
              if (res.data.length > 0) {
                let response = "";
                for (let i in res.data) {
                  response += `<li class="${
                    res.data[i].type == 1
                      ? "text-success"
                      : res.data[i].type == 2
                      ? "text-warning"
                      : res.data[i].type == 0
                      ? "text-danger"
                      : "s26-text-blue"
                  }">
                  ${res.data[i].msg}
                  </li>`;
                }
                this.$alertify.alert(`<nav>${response}</nav>`, () => {
                  this.$emit("input", null);
                });
              } else {
                this.$alertify.confirm(
                  "Ocurrio un Error de Sistema, comunicarse a servicio al cliente. "
                );
              }
              $s26.hide_loader_points();
              this.$emit("update");
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
        this.hideModal();
      }
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
    calc_utility: function (i) {
      if (
        this.form.variants[i]["utility"] !== "" &&
        this.form.variants[i]["cost"] !== ""
      ) {
        this.form.variants[i]["pvp_1"] = parseFloat(
          this.form.variants[i]["cost"] *
            (this.form.variants[i]["utility"] / 100 + 1)
        ).toFixed(2);
      } else {
        this.form.variants[i]["pvp_1"] =
          this.form.variants[i]["cost"] !== ""
            ? parseFloat(this.form.variants[i]["cost"]).toFixed(2)
            : parseFloat(0).toFixed(2);
      }
    },
    apply_iva(i) {
      if (this.form.variants[i]["cost"] != "") {
        this.form.variants[i]["cost"] = (
          this.form.variants[i]["cost"] * _iva__
        ).toFixed(2);
      }
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
    addSeries(serie = "") {
      if (this.serial != "" && serie == "") {
        if (this.form.series.indexOf(this.serial) == -1) {
          this.form.series.push(this.serial);
        }
        this.serial = "";
      } else if (serie !== "") {
        this.form.series.splice(serie, 1);
      } else {
        this.$alertify.error("Ingrese un Número de Serie");
      }
    },
    get_code: function (code) {
      if (this.action == "new_product") {
        this.form.variants.push({
          code: code,
          sku: "",
          status: 1,
          amount: "",
          min_stock: "",
          max_stock: "",
          cost: "",
          pvp_1: "",
          pvp_2: "",
          pvp_3: "",
          pvp_distributor: "",
          additional_info: "",
          variants: def_variants(),
          photos: "",
        });
      } else {
        this.$alertify.error("el código ya se encuentra registrado");
        this.action = "val_code";
      }
    },
    toggle_modal_variants(id = null) {
      this.id_variant = id >= 0 ? id : null;
    },
    saveVariants() {
      this.form.variants[this.id_variant]["variants"] = this.variants;
      this.onResetVariant();
      this.id_variant = null;
    },
    onResetVariant() {
      this.variants = def_variants();
      this.active_variants = {
        color: false,
        size: false,
        fragance: false,
        net_content: false,
        shape: false,
        package: false,
        dimensions: false,
      };
    },
  },
};
</script>

<style scoped>
.series {
  height: 80px;
  overflow: auto;
}
.series > div {
  display: flex;
  max-width: 100%;
  flex-shrink: 0;
  width: 100%;
  max-width: 100%;
  padding-right: calc(var(--bs-gutter-x) / 2);
  padding-left: calc(var(--bs-gutter-x) / 2);
  margin-top: var(--bs-gutter-y);
  flex-wrap: wrap;
}
.list-item {
  display: inline-block;
  margin-right: 10px;
}
.list-enter-active,
.list-leave-active {
  transition: all 0.5s;
}
.list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateY(30px);
}

.variants {
  height: 38px;
  overflow: hidden;
  box-shadow: 0 10px 5px -6px rgb(93 130 170 / 21%) !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.25rem !important;
  margin-right: 0 !important;
  margin-left: 0 !important;
  margin-bottom: 1.5rem !important;
  padding-top: 0.5rem !important;
  padding-bottom: 0.5rem !important;
}
</style>