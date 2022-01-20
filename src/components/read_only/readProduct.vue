<template>
  <s26-modal-multiple
    id="readProduct"
    title="Información de Producto"
    size="xl"
    :levels="levels"
    body_style="min-height: 450px;"
    @hideModal="hideModal"
    readOnly
    update
    @update="infoData(id)"
  >
    <!-- INFORMACION PRINCIPAL -->
    <template v-slot:level-0>
      <div class="col-2" v-if="id > 0">
        <s26-input-read label="Id" :content="form.id"> </s26-input-read>
      </div>
      <div class="col-10">
        <s26-input-read label="Nombre" :content="form.name"> </s26-input-read>
      </div>
      <div class="col-12">
        <s26-textarea-read
          label="Descripción"
          :content="form.description"
          rows="9"
        >
        </s26-textarea-read>
      </div>
    </template>
    <template v-slot:level-1>
      <!-- DATOS GENERALES -->
      <div class="col-12 mb-4">
        <div class="col-12 row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Datos Generales</h2>

          <div class="col-5 col-sm-3">
            <s26-input-read label="Marca" :content="form.trademark">
            </s26-input-read>
          </div>
          <div class="col-4 col-sm-2">
            <s26-input-read label="Modelo" :content="form.model">
            </s26-input-read>
          </div>
          <div class="col-7 col-sm-3">
            <s26-input-read label="Categoria" :content="form.category.name">
            </s26-input-read>
          </div>
          <div class="col-4 col-sm-2 mb-3">
            <s26-input-read label="Tipo de Producto" :content="form.type">
            </s26-input-read>
          </div>
          <div class="col-4 col-sm-2 mb-3">
            <s26-input-read label="Tipo" :content="form.type_product">
            </s26-input-read>
          </div>
        </div>
      </div>
      <!-- APROBACIONES -->
      <div class="col-12 mb-4">
        <div class="row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Aprobaciones</h2>
          <div class="col-4 col-sm-2">
            <div
              :class="[
                'btn btn-sm w-100',
                form.status == 1 ? 'btn-success' : 'btn-danger',
              ]"
            >
              {{ form.status == 1 ? "Activo" : "Inactivo" }}
            </div>
          </div>
          <div class="col-4 col-sm-2">
            <div
              :class="[
                'btn btn-sm w-100',
                form.discontinued == 1
                  ? 'btn-primary'
                  : 'btn-outline-secondary',
              ]"
            >
              Descontinuado
            </div>
          </div>
          <div class="col-4 col-sm-2">
            <div
              :class="[
                'btn btn-sm w-100',
                form.remanufactured == 1
                  ? 'btn-primary'
                  : 'btn-outline-secondary',
              ]"
            >
              Remanufacturado
            </div>
          </div>
          <div class="col-6 col-sm-2">
            <div class="btn btn-sm w-100 btn-primary">Iva: {{ form.iva }}</div>
          </div>
        </div>
      </div>
      <!-- Proveedores -->
      <div class="col-12 mb-4" v-if="form.access_providers == 1">
        <div class="row mx-0 rounded py-2 s26-shadow-md border">
          <h2 class="h5 fw-bold s26-text-blue">Proveedores</h2>
          <div class="series col-12">
            <div
              class="col-3 px-0"
              v-for="provider in form.providers.items"
              :key="provider.id"
            >
              <div
                :class="[
                  's26-align-center w-100 btn btn-sm btn-primary m-2 text-break',
                ]"
              >
                {{ provider.alias }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- SERIADO -->
      <div class="col-12 mb-3" v-if="form.serial == 1">
        <div class="row mx-0 rounded py-2 shadow border">
          <div class="col-4">
            <h2 class="h5 fw-bold s26-text-blue">Seriado</h2>
          </div>
          <div class="col-4"></div>
          <div class="col-4">
            <s26-input-search v-model="filter.serie" @search="infoSeries" />
          </div>
          <div class="series col-12 row">
            <div class="col-3" v-for="serie in series.items" :key="serie.id">
              <div
                :class="[
                  's26-align-center w-100 btn btn-sm m-2 text-break',
                  serie.status == 1 ? 'btn-primary' : 'btn-outline-secondary',
                ]"
              >
                {{ serie.serie }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-slot:level-2>
      <!-- VARIANTES -->
      <div
        class="col-12 row mx-0 s26-shadow-md border p-2 mb-3 rounded"
        v-for="variant in form.variants.items"
        :key="variant.id"
      >
        <div class="col-6 s26-text-blue">
          <span class="fw-bold">
            {{ variant.ean_code }}
          </span>
          <span v-if="variant.sku != ''">
            /
            {{ variant.sku }}
          </span>
        </div>
        <div
          :class="[
            'col-6 text-end',
            variant.status == 1 ? 'text-success' : 'text-danger',
          ]"
        >
          {{ variant.status == 1 ? "Activo" : "Inactivo" }}
        </div>
        <div class="col-2">
          <s26-input-read
            label="Stock"
            :content="variant.establishment_stock.info.stock"
            title="Stock Global"
          >
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read label="Stock Mínimo" :content="variant.min_stock">
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read label="Stock Máximo" :content="variant.max_stock">
          </s26-input-read>
        </div>
        <div class="col-2" v-if="form.access_cost == 1">
          <s26-input-read label="Costo" :content="variant.cost" money>
          </s26-input-read>
        </div>
        <div class="col-2" v-if="form.access_cost == 1">
          <s26-input-read
            label="Utilidad"
            :content="calc_utility(variant.cost, variant.pvp_1)"
            percentage
          >
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read label="PVP 1" :content="variant.pvp_1" money>
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read label="PVP 2" :content="variant.pvp_2" money>
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read label="PVP 3" :content="variant.pvp_3" money>
          </s26-input-read>
        </div>
        <div class="col-2">
          <s26-input-read
            label="PVP Distribuidor"
            :content="variant.pvp_distributor"
            money
          >
          </s26-input-read>
        </div>
        <div class="col">
          <s26-input-read
            label="Info. Adicional"
            :content="variant.additional_info"
          >
          </s26-input-read>
        </div>
        <div class="col-12 row mx-0 mb-2">
          <div
            class="col-2"
            v-for="photo in variant.photos.items"
            :key="photo.id"
          >
            <img class="w-100" :src="photo.src" alt="" />
          </div>
        </div>
        <div class="col-12 row mx-0">
          <div class="col mb-2" v-show="variant.color_id > 0">
            <span class="fw-600">color:</span>
            <span
              class="fw-bold"
              :style="{ color: `#${variant.color.hexadecimal}` }"
            >
              <s26-icon icon="palette"></s26-icon>
              {{ variant.color.name }}
            </span>
          </div>
          <div class="col mb-2" v-show="variant.size != ''">
            <span class="fw-600">Talla:</span>
            {{ variant.size }}
          </div>
          <div class="col mb-2" v-show="variant.fragance != ''">
            <span class="fw-600">Fragancia:</span>
            {{ variant.fragance }}
          </div>
          <div class="col mb-2" v-show="variant.net_content != ''">
            <span class="fw-600">Cont. Neto:</span>
            {{ variant.net_content }}
          </div>
          <div class="col mb-2" v-show="variant.shape != ''">
            <span class="fw-600">Forma:</span>
            {{ variant.shape }}
          </div>
          <div class="col mb-2" v-show="variant.package != ''">
            <span class="fw-600">Bulto:</span>
            {{ variant.package }}
          </div>
          <div class="col-6 mb-2 text-lowercase">
            <span class="me-2">
              <s26-icon icon="shopping-bag"></s26-icon>
            </span>
            {{ variant.product_length }} x {{ variant.product_width }} x
            {{ variant.product_height }} - {{ variant.product_weight }} kg.
          </div>
          <div class="col-4 mb-2 text-lowercase">
            <span class="me-2">
              <s26-icon icon="box-open"></s26-icon>
            </span>
            {{ variant.box_length }} x {{ variant.box_width }} x
            {{ variant.box_height }} - {{ variant.box_weight }} kg.
          </div>
          <div class="col-2">
            <span class="me-1">
              <s26-icon icon="long-arrow-alt-up"></s26-icon>
            </span>
            {{ variant.box_stacking }}
          </div>
        </div>
      </div>
    </template>
    <template v-slot:level-3>
      <div class="col-12 mb-3">
        <div class="col-4 mx-auto">
          <div :id="'s26-custom-select-' + id" class="s26-custom-select mb-3">
            <div
              :id="id"
              class="form-control form-control-sm s26-select-value"
              tabindex="0"
              @click="$s26.activeSelect"
              @keypress.13="$s26.activeSelect"
            >
              <div>{{ select_report_value }}</div>
              <s26-icon icon="angle-down" class="icon-angle-down"></s26-icon>
            </div>
            <div class="s26-select-container">
              <div class="s26-select-container-options">
                <div
                  :class="['s26-select-options', value == 0 ? 'focus' : '']"
                  tabindex="0"
                  @click="reports('prod', id)"
                  @keyup.13="reports('prod', id)"
                >
                  Todos
                </div>
                <div
                  :class="[
                    's26-select-options s26-align-y-center',
                    value == option.id ? 'focus' : '',
                  ]"
                  tabindex="0"
                  v-for="option in form.variants.items"
                  :key="option.id"
                  @click="reports('var', option.id, option.ean_code)"
                  @keyup.13="reports('var', option.id, option.ean_code)"
                >
                  {{ option.ean_code }}
                </div>
              </div>
            </div>
            <input type="hidden" int="true" v-model="value" />
          </div>
        </div>
      </div>
      <div class="col-12 mb-3">
        <h2 class="text-center fw-600 h5 s26-text-blue">Estado Global</h2>
      </div>
      <div class="col">
        <s26-tarjet-info title="Entradas" variant="purple" icon="sign-in-alt">
          {{ report.info.total_entries }}
        </s26-tarjet-info>
      </div>
      <div class="col">
        <s26-tarjet-info title="Salidas" variant="orange" icon="sign-out-alt">
          {{ report.info.total_sales }}
        </s26-tarjet-info>
      </div>
      <div class="col">
        <s26-tarjet-info title="Devoluciones" variant="danger" icon="sync">
          {{ report.info.total_returns }}
        </s26-tarjet-info>
      </div>
      <div class="col">
        <s26-tarjet-info title="Averias" variant="secondary" icon="ambulance">
          {{ report.info.total_damaged }}
        </s26-tarjet-info>
      </div>
      <div class="col">
        <s26-tarjet-info title="Ajuste" variant="primary" icon="cog">
          {{ report.info.total_settings }}
        </s26-tarjet-info>
      </div>
    </template>
  </s26-modal-multiple>
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
  data: function () {
    return {
      form: {
        category: {},
        providers: {},
        variants: {},
        photos: {},
      },
      series: { items: [] },
      report: { info: {} },
      select_report_value: "Seleccionar Variantes",
      levels: [
        "Información Principal",
        "Información General",
        "Variantes",
        "Estadísticas",
      ],
      filter: {
        product_id: "",
        serie: "",
      },
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
          this.filter.product_id = this.id;
          this.infoSeries();
          this.reports();
        })
        .catch((err) => console.log(err));
    },
    infoSeries() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];
      this.axios
        .get("/productsSeries/getSeries/", {
          params,
        })
        .then((res) => (this.series = res.data))
        .catch((err) => console.log(err));
    },

    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "products");
    },
    calc_utility(val_1, val_2) {
      return ((val_2 * 100) / val_1 - 100).toFixed(2);
    },
    reports(type = "prod", id = this.id, variant = "") {
      $(`div.s26-select-container`).hide("200");
      this.select_report_value = type == "prod" ? "todos" : variant;
      const params = {
        type,
        id,
      };
      this.axios
        .get("/products/reportProduct/", {
          params,
        })
        .then((res) => (this.report = res.data))
        .catch((err) => console.log(err));
    },
  },
};
</script>