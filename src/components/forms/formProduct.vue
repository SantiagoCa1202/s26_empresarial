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
    <template v-slot:level-0>
      <div class="col-2" v-if="id > 0">
        <s26-input-read label="Id" :content="form.id"> </s26-input-read>
      </div>
      <div class="col-2">
        <s26-input-read label="Código" :content="code"> </s26-input-read>
      </div>
      <div class="col-2">
        <s26-form-input
          label="Código Auxiliar"
          id="form-auxiliary_code"
          v-model="form.auxiliary_code"
          maxlength="13"
        >
        </s26-form-input>
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
      <div class="col-12 mb-3">
        <div class="row mx-0 rounded py-2 shadow-sm">
          <h2 class="h5 fw-bold s26-text-blue">Datos Generales</h2>
          <div class="col-2">
            <s26-form-input
              label="Marca"
              id="form-trademark"
              v-model="form.trademark"
              maxlength="100"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-form-input
              label="Modelo"
              id="form-model"
              v-model="form.model"
              maxlength="100"
            >
            </s26-form-input>
          </div>
          <div class="col-2">
            <s26-select-category
              id="form-category"
              v-model="form.category_id"
              s26_required
            ></s26-select-category>
          </div>
          <div class="col-2">
            <s26-select-provider
              id="form-providers"
              v-model="form.providers"
              s26_required
              multiple
              is_null
            ></s26-select-provider>
          </div>
          <div class="col-2 mb-3">
            <label class="form-label"> Tipo de Producto </label>
            <select class="form-select form-select-sm" v-model="form.type">
              <option value="original">Original</option>
              <option value="réplica">réplica</option>
            </select>
          </div>
          <div class="col-2 mb-3">
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
      <div class="col-12 mb-3">
        <div class="row mx-0 rounded py-2 shadow-sm">
          <h2 class="h5 fw-bold s26-text-blue">Aprobaciones</h2>
          <div class="col-1">
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
          <div class="col-2">
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
          <div class="col-2">
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
          <div class="col-2">
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
          <div class="col-1">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.serial"
                id="form-serial"
              />
              <label class="form-check-label" for="form-serial">
                Seriado.
              </label>
            </div>
          </div>
          <div class="col-2">
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
          <div class="col-2">
            <div class="form-check">
              <input
                class="form-check-input"
                type="checkbox"
                v-model="form.offer"
                id="form-offer"
              />
              <label class="form-check-label" for="form-offer"> Oferta. </label>
            </div>
            <s26-form-input
              id="form-pvp_offer"
              v-model="form.pvp_offer"
              money
              v-if="form.offer"
              placeholder="000.00"
            >
            </s26-form-input>
          </div>
        </div>
      </div>
      <transition name="fade">
        <div class="col-12 mb-3" v-if="form.serial">
          <div class="row mx-0 rounded py-2 shadow-sm">
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
      <div class="col-12 mb-3">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="additional_information"
            id="form-additional_information"
          />
          <label class="form-check-label" for="form-additional_information">
            Información adicional.
          </label>
        </div>
      </div>
      <transition name="fade">
        <div class="col-12 mb-3" v-if="additional_information">
          <div class="row mx-0 rounded py-2 shadow-sm">
            <h2 class="h5 fw-bold s26-text-blue">Especificaciones Técnicas</h2>
            <div class="w-100 px-4">
              <h3 class="h6 fw-600 s26-text-blue">
                <s26-icon icon="shopping-bag"></s26-icon>
                Producto
              </h3>
              <div class="row px-3 py-1">
                <div class="col-3">
                  <s26-form-input
                    label="Largo"
                    id="form-product_length"
                    v-model="form.product_length"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-3">
                  <s26-form-input
                    label="Alto"
                    id="form-product_height"
                    v-model="form.product_height"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-3">
                  <s26-form-input
                    label="Ancho"
                    id="form-product_width"
                    v-model="form.product_width"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-3">
                  <s26-form-input
                    label="Peso (kg)"
                    id="form-product_weight"
                    v-model="form.product_weight"
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
                <div class="col-2">
                  <s26-form-input
                    label="Largo"
                    id="form-box_length"
                    v-model="form.box_length"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-2">
                  <s26-form-input
                    label="Alto"
                    id="form-box_height"
                    v-model="form.box_height"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-2">
                  <s26-form-input
                    label="Ancho"
                    id="form-box_width"
                    v-model="form.box_width"
                    placeholder="Valor en cm."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-3">
                  <s26-form-input
                    label="Peso (kg) (Prod. + Caja)"
                    id="form-box_weight"
                    v-model="form.box_weight"
                    placeholder="Valor en kg."
                    number
                  >
                  </s26-form-input>
                </div>
                <div class="col-3">
                  <s26-form-input
                    label="Apilamiento Max."
                    id="form-box_stacking"
                    v-model="form.box_stacking"
                    placeholder="Maximo de Cajas Apiladas."
                    number
                  >
                  </s26-form-input>
                </div>
              </div>
            </div>
            <p class="text-primary">
              *Recomendamos LLenar Esta Informacíon para que el cálculo de flete
              sea mas exacto.
            </p>
          </div>
        </div>
      </transition>
    </template>
    <template v-slot:level-2>
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
        <s26-input-read label="Ruc" :content="buy.document"> </s26-input-read>
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
      <div class="col-4">
        <s26-form-input
          label="Cantidad"
          id="form-amount"
          type="number"
          v-model="form.amount"
          number
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-4">
        <s26-form-input
          label="Stock Mínimo"
          id="form-min_stock"
          type="number"
          v-model="form.min_stock"
          number
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-4">
        <s26-form-input
          label="Stock Máximo"
          id="form-max_stock"
          type="number"
          v-model="form.max_stock"
          number
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="Costo"
          id="form-cost"
          v-model="form.cost"
          type="number"
          money
          s26_required
          placeholder="50.00"
          @keyup="calc_utility"
          @enter="apply_iva"
          title="presiona enter para calcular iva"
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="Utilidad %"
          id="form-utility"
          v-model="form.utility"
          type="number"
          s26_required
          placeholder="40"
          @keyup="calc_utility"
          percentage
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="PVP 1"
          id="form-pvp_1"
          v-model="form.pvp_1"
          type="number"
          money
          s26_required
          placeholder="60.00"
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="PVP 2"
          id="form-pvp_2"
          v-model="form.pvp_2"
          type="number"
          money
          placeholder="70.00"
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="PVP 3"
          id="form-pvp_3"
          v-model="form.pvp_3"
          type="number"
          money
          placeholder="80.00"
        >
        </s26-form-input>
      </div>
      <div class="col-2">
        <s26-form-input
          label="PVP Distribuidor"
          id="form-pvp_distributor"
          v-model="form.pvp_distributor"
          type="number"
          money
          placeholder="80.00"
        >
        </s26-form-input>
      </div>
      <p class="text-primary">
        *Recomendado ingresar PVP 1 como precio menor y PVP 3 como el precio mas
        alto.
      </p>
      <p class="text-primary">
        *La cantidad se asignara al establecimiento principal, puedes transferir
        el stock a otros establecimientos mas tarde.
      </p>
    </template>
    <template v-slot:level-3>
      <s26-input-photo id="form-fotos" v-model="form.photos" multiple>
      </s26-input-photo>
    </template>
  </s26-modal-multiple>
</template>
<script>
const def_form = () => {
  return {
    // EN PRODUCTOS
    id: "",
    auxiliary_code: "",
    ean_code: "",
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
    offer: 0,
    pvp_manual: 0,
    iva: 12,
    cost: "",
    pvp_1: "",
    pvp_2: "",
    pvp_3: "",
    pvp_distributor: "",
    pvp_offer: "",
    product_length: "",
    product_width: "",
    product_height: "",
    product_weight: "",
    box_length: "",
    box_width: "",
    box_height: "",
    box_weight: "",
    box_stacking: "",
    status: 1,
    utility: "50",
    // EN PRODUCTOS FOTOS
    photos: [],
    // EN PRODUCTOS SERIES
    series: [],
    // EN PRODUCTOS PROVEEDORES
    providers: [],
    // EN PRODUCTOS ENTRADAS
    document_id: "",
    amount: "",
    min_stock: "",
    max_stock: "",
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
      form: def_form(),
      buy: {},
      serial: "",
      additional_information: 0,
      levels: [
        "Información Principal",
        "Información General",
        "Precios y Stock",
        "Fotos",
      ],
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
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
              for (let i in res.data) {
                if (res.data[i].type == 1 || res.data[i].type == 2) {
                  this.onReset();
                  this.$emit("input", "val_code");
                  this.$alertify.success(res.data[i].msg);
                } else if (res.data[i].type == 3) {
                  this.$alertify.warning(res.data[i].msg);
                } else {
                  this.$alertify.error(res.data[i].msg);
                }
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
        this.form = def_form();
      }
      this.form.payment_type = 1;
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
    calc_utility() {
      if (this.form.utility !== "" && this.form.cost !== "") {
        this.form.pvp_1 = parseFloat(
          this.form.cost * (this.form.utility / 100 + 1)
        ).toFixed(2);
      } else {
        this.form.pvp_1 =
          this.form.cost !== ""
            ? parseFloat(this.form.cost).toFixed(2)
            : parseFloat(0).toFixed(2);
      }
    },
    apply_iva() {
      if (this.form.cost != "") {
        this.form.cost = (this.form.cost * _iva__).toFixed(2);
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
</style>