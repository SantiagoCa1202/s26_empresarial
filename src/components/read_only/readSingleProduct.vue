<template>
  <s26-modal id="readCategory" @hideModal="hideModal" size="xl">
    <template v-slot:header>
      <h5 class="p-2">
        <span v-show="form.product.trademark != ''" class="h5 fw-600">
          {{ form.product.trademark }} -
        </span>
        <span v-show="form.product.name != ''" class="h5 fw-600">
          {{ form.product.name }}
        </span>
        <span v-show="form.product.model != ''" class="h6">
          / {{ form.product.model }}
        </span>
        <span
          v-show="value != 'watch-providers'"
          :class="[
            'btn btn-sm fw-600',
            parseInt(form.variants.info.total_stock) <=
            parseInt(form.variants.info.total_min_stock)
              ? 'btn-danger'
              : 'btn-primary',
          ]"
        >
          {{ form.variants.info.total_stock }}
        </span>
      </h5>
    </template>
    <template v-slot:body>
      <div class="row">
        <template v-if="value == 'watch-variants'">
          <div class="row mx-0">
            <div
              class="col-12 row variants"
              v-for="variant in form.variants.items"
              :key="variant.id"
            >
              <h2 class="h6 mb-3 s26-text-blue">
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
                <span
                  :class="[
                    'float-end pointer',
                    variant.status ? 'text-success' : 'text-danger',
                  ]"
                >
                  {{ variant.status == 1 ? "Activo" : "Inactivo" }}
                </span>
              </h2>
              <div class="col-2">
                <s26-input-read
                  label="Stock "
                  :content="variant.stock"
                  :variant_input="
                    parseInt(variant.stock) <= parseInt(variant.min_stock)
                      ? 'is-invalid'
                      : ''
                  "
                >
                </s26-input-read>
              </div>
              <div class="col-2">
                <s26-input-read label="Costo" :content="variant.cost" money>
                </s26-input-read>
              </div>
              <div class="col-2">
                <s26-input-read label="Pvp 1" :content="variant.pvp_1" money>
                </s26-input-read>
              </div>
              <div class="col-2">
                <s26-input-read label="Pvp 2" :content="variant.pvp_2" money>
                </s26-input-read>
              </div>
              <div class="col-2">
                <s26-input-read label="Pvp 3" :content="variant.pvp_2" money>
                </s26-input-read>
              </div>
              <div class="col-2">
                <s26-input-read
                  label="Pvp Dist."
                  :content="variant.pvp_distributor"
                  money
                >
                </s26-input-read>
              </div>
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
              <div class="col-12 mb-2" v-show="variant.additional_info != ''">
                <span class="fw-600">Info. Adicional:</span>
                {{ variant.additional_info }}
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
        <template v-if="value == 'watch-providers'">
          <div class="row mx-0">
            <div
              class="col-3 mb-3"
              v-for="provider in form.providers.items"
              :key="provider.id"
            >
              <p class="btn btn-sm btn-primary w-100">
                {{ provider.tradename }} -
                {{ provider.alias }}
              </p>
            </div>
          </div>
        </template>
        <template v-if="value == 'watch-stock'">
          <div class="row mx-0">
            <div
              class="col-12 row variants"
              v-for="variant in form.variants.items"
              :key="variant.id"
            >
              <h2 class="h6 mb-3 s26-text-blue">
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
                <span class="btn btn-sm btn-info fw-600">
                  {{ variant.establishment_stock.info.stock }}
                </span>

                <span
                  :class="[
                    'float-end pointer',
                    variant.status ? 'text-success' : 'text-danger',
                  ]"
                >
                  {{ variant.status == 1 ? "Activo" : "Inactivo" }}
                </span>
              </h2>
              <div
                class="col-12 row mx-0 variants"
                v-for="estab in variant.establishment_stock.items"
                :key="estab.id"
              >
                <div class="col-3">
                  <s26-input-read
                    label="Nº de Establecimiento."
                    :content="estab.n_establishment.padStart(3, '0')"
                  >
                  </s26-input-read>
                </div>
                <div class="col-6">
                  <s26-input-read
                    label="Nombre Comercial"
                    :content="estab.tradename"
                  >
                  </s26-input-read>
                </div>
                <div class="col-3">
                  <s26-input-read label="Stock" :content="estab.stock">
                  </s26-input-read>
                </div>
                <div class="col-12">
                  <span class="fw-600">Dirección:</span>
                  {{ estab.city }} -
                  {{ estab.address }}
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </template>
    <template v-slot:footer>
      <button class="btn btn-primary" @click="infoData(id)">
        <s26-icon icon="sync-alt"></s26-icon>
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
  data: function () {
    return {
      form: {
        product: {},
        variants: {
          info: {},
          items: [
            {
              color: {},
              establishment_stock: {
                info: {},
                items: [],
              },
            },
          ],
        },
        providers: {
          info: {},
          item: [],
        },
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
          this.form.product = res.data;
        })
        .catch((err) => console.log(err));
      if (this.value == "watch-variants" || this.value == "watch-stock") {
        this.axios
          .get("/products/getVariants/" + id)
          .then((res) => {
            this.form.variants = res.data;
          })
          .catch((err) => console.log(err));
      } else if (this.value == "watch-providers") {
        this.axios
          .get("/products/getProviders/" + id)
          .then((res) => {
            console.log(res);
            this.form.providers = res.data;
          })
          .catch((err) => console.log(err));
      }
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
<style scoped>
.variants {
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