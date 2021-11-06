<template>
  <s26-modal-multiple
    id="formAmountProduct"
    title="Añadir Cantidad"
    :levels="levels"
    body_style="min-height: 475px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-12 row mx-0 variants">
        <h2 class="h6 fw-600">Info. de Compra.</h2>
        <div class="col-sm-8">
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
        <div class="col-sm-4">
          <s26-input-read label="Ruc" :content="buy.document"> </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read label="Razón Social" :content="buy.business_name">
          </s26-input-read>
        </div>
      </div>
      <div class="col-12 row mx-0 variants">
        <h2 class="h6 fw-600">Info. de Producto.</h2>
        <div class="col-sm-4">
          <s26-input-read label="Código" :content="data.ean_code">
          </s26-input-read>
        </div>
        <div class="col-sm-8">
          <s26-input-read
            label="Nombre"
            :content="data.name + ' - ' + data.model"
          >
          </s26-input-read>
        </div>
        <div class="col mb-2" v-show="data.color_id > 0">
          <span class="fw-600">color:</span>
          <span
            class="fw-bold"
            :style="{ color: `#${data.color.hexadecimal}` }"
          >
            <s26-icon icon="palette"></s26-icon>
            {{ data.color.name }}
          </span>
        </div>
        <div class="col mb-2" v-show="data.size != ''">
          <span class="fw-600">Talla:</span>
          {{ data.size }}
        </div>
        <div class="col mb-2" v-show="data.fragance != ''">
          <span class="fw-600">Fragancia:</span>
          {{ data.fragance }}
        </div>
        <div class="col mb-2" v-show="data.net_content != ''">
          <span class="fw-600">Cont. Neto:</span>
          {{ data.net_content }}
        </div>
        <div class="col mb-2" v-show="data.shape != ''">
          <span class="fw-600">Forma:</span>
          {{ data.shape }}
        </div>
        <div class="col mb-2" v-show="data.package != ''">
          <span class="fw-600">Bulto:</span>
          {{ data.package }}
        </div>
      </div>

      <div class="col-12 row mx-0 variants">
        <h2 class="h6 fw-600">Stock y Costos.</h2>
        <div class="col-4">
          <s26-input-read label="Stock Actual" :content="data.stock">
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-form-input
            label="Cantidad"
            id="form-amount"
            type="number"
            v-model="form.amount"
            @keyup="val_amount"
            number
            s26_required
          >
          </s26-form-input>
        </div>
        <div class="col-4">
          <s26-input-read label="Nuevo Stock" :content="new_stock">
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read label="Costo Actual" :content="data.cost" money>
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-form-input
            label="Costo"
            id="form-cost"
            v-model="form.cost"
            type="number"
            money
            s26_required
            placeholder="50.00"
            @enter="apply_iva"
            title="presiona enter para calcular iva"
          >
          </s26-form-input>
        </div>
        <div class="col-4">
          <s26-input-read label="Nuevo Costo" :content="new_cost" money>
          </s26-input-read>
        </div>
        <div class="col-12" v-if="data.cost != new_cost">
          <p class="text-primary">*Recomendado Actualizar Precios.</p>
        </div>
      </div>
      <div class="col-12 mb-3">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            v-model="form.update_pvp"
            id="form-update_pvp"
          />
          <label class="form-check-label" for="form-update_pvp">
            Actualizar Precios.
          </label>
        </div>
      </div>
      <template v-if="form.update_pvp">
        <div class="col-4">
          <s26-form-input
            label="Utilidad %"
            id="form-utility"
            v-model="form.utility"
            s26_required
            placeholder="40"
            @keyup="calc_utility"
            percentage
          >
          </s26-form-input>
        </div>
        <div class="col-4">
          <s26-form-input
            label="Pvp 1"
            id="form-pvp_1"
            v-model="form.pvp_1"
            money
            s26_required
            placeholder="50.00"
            @keyup="
              form.utility = parseFloat(
                (form.pvp_1 * 100) / form.cost - 100
              ).toFixed(2)
            "
          >
          </s26-form-input>
        </div>
        <div class="col-4">
          <s26-form-input
            label="Pvp 2"
            id="form-pvp_2"
            v-model="form.pvp_2"
            money
            placeholder="50.00"
          >
          </s26-form-input>
        </div>
        <div class="col-6">
          <s26-form-input
            label="Pvp 3"
            id="form-pvp_3"
            v-model="form.pvp_3"
            money
            placeholder="50.00"
          >
          </s26-form-input>
        </div>
        <div class="col-6">
          <s26-form-input
            label="Pvp Dis."
            id="form-pvp_distributor"
            v-model="form.pvp_distributor"
            money
            placeholder="50.00"
          >
          </s26-form-input>
        </div>
      </template>
      <!-- SERIADO -->
      <div class="col-12 mb-5" v-if="form.serial">
        <div class="row mx-0 rounded py-2 s26-shadow-md border">
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
                  class="s26-align-center btn btn-primary btn-sm m-2 text-break"
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
      required: true,
    },
  },
  data: function () {
    return {
      data: {
        cost: "",
        pvp_1: "",
        pvp_2: "",
        pvp_3: "",
        pvp_distributor: "",
        color: {},
      },
      form: {
        product_id: "",
        amount: 1,
        update_pvp: false,
        cost: "",
        new_cost: 0,
        utility: "",
        pvp_1: "",
        pvp_2: "",
        pvp_3: "",
        pvp_distributor: "",
        serial: false,
        // EN PRODUCTOS SERIES
        series: [],
      },
      buy: {
        document: "",
        business_name: "",
      },
      levels: ["Información"],
      serial: "",
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
  },
  computed: {
    new_stock: function () {
      let stock = this.data.stock ? this.data.stock : 0;
      let amount = this.form.amount ? this.form.amount : 0;
      return parseInt(stock) + parseInt(amount);
    },
    new_cost: function () {
      let cost = this.form.cost ? this.form.cost : 0;
      let amount = this.form.amount ? this.form.amount : 0;
      let new_total =
        parseFloat(this.data.stock * this.data.cost) +
        parseFloat(amount * cost);
      let new_cost = parseFloat(new_total / this.new_stock);

      this.form.new_cost = new_cost.toFixed(2);
      this.form.utility = parseFloat(
        (this.data.pvp_1 / this.form.new_cost) * 100 - 100
      ).toFixed(2);
      return this.form.new_cost;
    },
  },
  methods: {
    infoData(id) {
      this.axios
        .get("/products/getVariant/" + id)
        .then((res) => {
          this.data = res.data;
          this.form.product_id = this.data.product_id;
          this.form.cost = this.data.cost;
          this.form.pvp_1 = this.data.pvp_1;
          this.form.pvp_2 = this.data.pvp_2;
          this.form.pvp_3 = this.data.pvp_3;
          this.form.serial = this.data.serial;
          this.form.pvp_distributor = this.data.pvp_distributor;
        })
        .catch((err) => console.log(err));
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea  actualizar el stock de <span class="fw-bold">${this.data.name}</span>?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/products/addAmount", formData)
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
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      }
      $("[s26-required], [s26-pass-conf]").removeClass("is-invalid");
    },
    hideModal() {
      this.$emit("input", null);
    },
    val_amount() {
      if (this.form.amount != "" && this.form.amount < 1) {
        this.$alertify.error("Cantidad debe ser mayor a 0");
        this.form.amount = 1;
      }
    },
    calc_utility() {
      if (this.form.utility !== "" && this.form.new_cost !== "") {
        this.form.pvp_1 = parseFloat(
          this.form.new_cost * (this.form.utility / 100 + 1)
        ).toFixed(2);
      } else {
        this.form.pvp_1 =
          this.form.new_cost !== ""
            ? parseFloat(this.form.new_cost).toFixed(2)
            : parseFloat(0).toFixed(2);
      }
    },
    apply_iva() {
      if (this.form.cost != "") {
        this.form.cost = (this.form.cost * _iva__).toFixed(2);
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
.variants {
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