<template>
  <div id="s26-newSale-default" class="row s26-align-center h-100">
    <!-- CLIENTE -->
    <div class="col-12 col-sm-6 px-3 mb-3 mt-3 mt-sm-2">
      <div id="s26-container-customer" class="s26-tarjet-sale">
        <div class="row">
          <div class="col-3 col-sm-5 mb-2 s26-align-y-center">
            <h1 class="h5 fw-bold s26-text-blue">Cliente</h1>
          </div>
          <div class="col-9 col-sm-7 px-0">
            <div class="d-flex justify-content-end s26-align-center">
              <div class="mx-1">
                <s26-input-search
                  id="document"
                  v-model="document"
                  @search="searchCustomer"
                  number
                  rounded
                  length
                  strictlength="10,13"
                  placeholder="Buscar... (alt + c)"
                />
              </div>
              <button
                class="btn btn-outline-primary btn-sm mx-1 mb-3"
                @click="resetCustomer"
              >
                <s26-icon icon="sync"></s26-icon>
              </button>
              <button
                class="btn btn-primary btn-sm mx-1 mb-3"
                @click="onSubmitCustomer"
              >
                <s26-icon icon="save"></s26-icon>
              </button>
            </div>
          </div>
          <div class="col-sm-3">
            <s26-input-read
              id="customer-document"
              :content="current_sale.customer.document"
              :link="'customers,' + current_sale.customer.id"
            >
            </s26-input-read>
          </div>
          <div class="col-sm-9">
            <s26-form-input
              id="customer-names"
              v-model="current_sale.customer.full_name"
              maxlength="100"
              text
              placeholder="Nombres"
              :disabled="disabled_customer"
            >
            </s26-form-input>
          </div>
          <div class="col-6 col-sm-3">
            <s26-form-input
              id="customer-mobile"
              v-model="current_sale.customer.mobile"
              maxlength="10"
              number
              placeholder="Celular"
              :disabled="disabled_customer"
            >
            </s26-form-input>
          </div>
          <div class="col-6 col-sm-3">
            <s26-form-input
              id="customer-phone"
              v-model="current_sale.customer.phone"
              maxlength="9"
              number
              placeholder="Tél. Convencional"
              :disabled="disabled_customer"
            >
            </s26-form-input>
          </div>
          <div class="col-sm-6">
            <s26-form-input
              id="customer-address"
              v-model="current_sale.customer.address"
              maxlength="100"
              placeholder="Dirección"
              :disabled="disabled_customer"
              @enter="onSubmitCustomer"
            >
            </s26-form-input>
          </div>
        </div>
      </div>
    </div>
    <!-- OPCIONES DE VENTA -->
    <div class="col-12 col-sm-6 px-3 mb-3 mt-0 mt-sm-2">
      <div id="s26-container-options" class="s26-tarjet-sale">
        <div class="row">
          <div class="col-12 mb-2 s26-align-y-center">
            <h1 class="h5 fw-bold s26-text-blue">Opciones</h1>
          </div>
          <div class="col-6 col-sm-4 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-outline-info w-100"
              @click="modal_options = 'calculator'"
            >
              Calculadora (alt + u)
            </button>
          </div>
          <div class="col-6 col-sm-4 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-outline-secondary w-100"
              @click="modal_options = 'select_products'"
            >
              Buscar Producto (alt + b)
            </button>
          </div>
          <div class="col-6 col-sm-4 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-outline-warning w-100"
              @click="open_box"
            >
              Abrir Caja (alt + o)
            </button>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-outline-success w-100"
              @click="saveSale"
              title="alt + s"
            >
              {{ current_sale.id > 0 ? "Actualizar" : "Guardar" }}
            </button>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-success w-100"
              @click="newSale"
              title="alt + n"
            >
              Nueva Venta
            </button>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <button
              type="button"
              class="btn btn-sm btn-outline-danger w-100"
              @click="delSavedSale"
              title="alt + q"
            >
              Cancelar
            </button>
          </div>
          <div class="col-6 col-sm-3 mb-2">
            <button
              id="btn-process_sale"
              type="button"
              class="btn btn-sm btn-primary w-100"
              @click="valStockProcessSale"
              :disabled="
                current_sale.products.length <= 1 ||
                current_sale.customer.id == 0
              "
            >
              Procesar (alt + w)
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- DETALLE DE VENTA -->
    <div class="col-12 px-3 mb-3">
      <div id="s26-container-detail" class="s26-tarjet-sale s26-tarjet-detail">
        <div class="row">
          <div class="col-12">
            <s26-table
              :rows="50"
              :loading_data="loading_data"
              relative
              height="auto"
              section_variants="px-0"
              action
            >
              <template v-slot:head>
                <th class="length-action">Código</th>
                <th class="length-description">Producto</th>
                <th class="length-sm text-center">Cant.</th>
                <th class="length-sm text-center">Stock</th>
                <th
                  class="length-sm text-center pointer select-none"
                  @click="show_cost = !show_cost"
                  @dblclick="show_utility = !show_utility"
                >
                  <span :class="!show_cost ? 'blur' : ''">
                    {{ !show_utility ? "Costo" : "Util." }}
                  </span>
                  <s26-icon
                    v-show="!show_utility"
                    :icon="show_cost ? 'eye-slash' : 'eye'"
                  ></s26-icon>
                  <s26-icon v-show="show_utility" icon="percentage"></s26-icon>
                </th>
                <th class="length-sm text-center">Iva</th>
                <th class="length-status text-center">Pvp</th>
                <th
                  class="length-sm text-center pointer select-none"
                  @click="discount_money_percentage"
                  title="Descuento en % (alt + g)"
                >
                  <span> Desc. </span>
                </th>
                <th class="length-sm text-center">Total</th>
              </template>
              <template v-slot:body v-if="!loading_data">
                <tr
                  v-for="(product, index) in current_sale.products"
                  :key="index"
                >
                  <td class="length-action" :colspan="product.id == 0 ? 2 : 1">
                    {{ product.id > 0 ? product.code : "" }}
                    <s26-input-search
                      v-if="product.id == 0"
                      :id="'search-product-' + index"
                      v-model="product.code"
                      @search="searchProduct(product.code, index)"
                      @blur="product.code = product.id == 0 ? '' : product.code"
                      rounded
                      placeholder="Buscar... (alt + v)"
                      mb="0"
                    />
                  </td>
                  <td
                    class="length-description"
                    :title="product.product"
                    v-show="product.id > 0"
                  >
                    {{ product.product }}
                  </td>
                  <td class="length-sm">
                    <div class="s26-align-center">
                      <s26-form-input
                        v-model="product.amount"
                        type="number"
                        number
                        mb="0"
                        :disabled="product.id == 0 || product.stock == 0"
                        variant="text-center"
                        @keyup="valStock(index)"
                        @blur="
                          product.amount =
                            product.amount == '' ? 1 : product.amount;
                          valStock(index);
                        "
                      >
                      </s26-form-input>
                      <span
                        class="px-1 text-danger"
                        v-if="product.id > 0 && product.stock < 1"
                        title="Ajustar Stock"
                        @click="open_stock_adjustment(index)"
                      >
                        <s26-icon icon="wrench"></s26-icon>
                      </span>
                    </div>
                  </td>
                  <td class="length-sm text-center">{{ product.stock }}</td>
                  <td class="length-sm text-center" v-show="!show_utility">
                    <s26-icon icon="dollar-sign"></s26-icon>
                    <span :class="!show_cost ? 'blur' : ''">
                      {{ $s26.currency(product.cost) }}
                    </span>
                  </td>
                  <td class="length-sm text-center" v-show="show_utility">
                    <span :class="!show_cost ? 'blur' : ''">
                      {{
                        product.pvp > 0 && product.cost > 0
                          ? $s26.currency(
                              (product.net_total * 100) / product.cost - 100
                            )
                          : 0
                      }}
                    </span>
                    <s26-icon icon="percentage"></s26-icon>
                  </td>
                  <td class="length-sm text-center">
                    {{ $s26.currency(product.iva) }}
                    <s26-icon icon="percentage"></s26-icon>
                  </td>
                  <td class="length-status">
                    <div class="s26-align-center">
                      <span
                        class="px-1"
                        v-if="product.id == 0 || product.pvp_manual == 0"
                      >
                        <s26-icon icon="dollar-sign"></s26-icon>
                        {{
                          $s26.currency(
                            product.pvp_manual == 0 ? product.pvp : 0
                          )
                        }}
                      </span>
                      <s26-form-input
                        v-if="product.pvp_manual == 1"
                        v-model="product.pvp"
                        type="number"
                        money
                        mb="0"
                        :disabled="product.id == 0 || product.pvp_manual == 0"
                        @keyup="calc_total_product(index)"
                      >
                      </s26-form-input>
                      <span class="px-1" v-if="product.id > 0">
                        <s26-dropdown>
                          <li
                            class="list-group-item border-0"
                            v-show="product.pvp_distributor > 0"
                            @click="selectPvp(product.pvp_distributor, index)"
                          >
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(product.pvp_distributor) }}
                          </li>
                          <li
                            class="list-group-item border-0"
                            v-show="product.pvp_1 > 0"
                            @click="selectPvp(product.pvp_1, index)"
                          >
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(product.pvp_1) }}
                          </li>
                          <li
                            class="list-group-item border-0"
                            v-show="product.pvp_2 > 0"
                            @click="selectPvp(product.pvp_2, index)"
                          >
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(product.pvp_2) }}
                          </li>
                          <li
                            class="list-group-item border-0"
                            v-show="product.pvp_3 > 0"
                            @click="selectPvp(product.pvp_3, index)"
                          >
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(product.pvp_3) }}
                          </li>
                        </s26-dropdown>
                      </span>
                    </div>
                  </td>
                  <td class="length-sm text-center">
                    <div
                      class="px-1"
                      v-if="product.id == 0 || product.discount == 0"
                    >
                      <span v-show="!discount_percentage">
                        <s26-icon icon="dollar-sign"></s26-icon>
                      </span>
                      {{ $s26.currency(product.discount_money) }}
                      <span v-show="discount_percentage">
                        <s26-icon icon="percentage"></s26-icon>
                      </span>
                    </div>
                    <!-- DESCUENTO EN MONEDA -->
                    <s26-form-input
                      v-if="product.discount == 1"
                      v-model="product.discount_money"
                      type="number"
                      :money="!discount_percentage"
                      :percentage="discount_percentage"
                      mb="0"
                      :disabled="product.id == 0 || product.discount == 0"
                      @keyup="
                        product.discount_money =
                          product.discount_money < 0
                            ? 0
                            : product.discount_money;
                        calc_total_product(index);
                      "
                      @blur="
                        product.discount_money = (
                          product.discount_money == ''
                            ? 0
                            : product.discount_money
                        ).toFixed(2)
                      "
                    >
                    </s26-form-input>
                  </td>
                  <td class="length-sm text-center">
                    <s26-icon icon="dollar-sign"></s26-icon>
                    {{ $s26.currency(product.net_total) }}
                  </td>
                  <td class="length-sm text-center">
                    <div class="d-flex">
                      <button
                        title="Eliminar Producto"
                        v-if="product.id > 0"
                        class="
                          btn btn-outline-danger btn-sm
                          mx-auto
                          s26-align-center
                          border-0
                        "
                        @click="delProduct(index)"
                      >
                        <s26-icon icon="times"></s26-icon>
                      </button>
                      <button
                        title="Seleccionar Series"
                        v-if="product.id > 0 && product.serial == 1"
                        class="
                          btn btn-outline-primary btn-sm
                          mx-auto
                          s26-align-center
                          border-0
                        "
                        @click="searchSeries(index)"
                      >
                        <s26-icon icon="server"></s26-icon>
                      </button>
                      <div
                        class="
                          spinner-border spinner-border-sm
                          text-secondary
                          mx-auto
                        "
                        role="status"
                        v-if="product.load"
                      >
                        <span class="visually-hidden">Loading...</span>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </s26-table>
          </div>
        </div>
      </div>
    </div>
    <!-- TOTALES -->
    <div class="col-12 px-3 mb-2">
      <div
        id="s26-container-totals"
        class="s26-tarjet-sale s26-tarjet-totals mb-3"
      >
        <div class="row">
          <div class="col-12 col-sm-4">
            <div class="main-total">
              <div
                v-show="saved_sales.length > 0"
                class="
                  position-absolute
                  bottom-0
                  start-0
                  ms-2
                  mb-1
                  text-white
                  w-auto
                  rounded
                "
              >
                {{
                  current_sale.id > 0
                    ? index_saved_sales + 1 + " / " + saved_sales.length
                    : "Nueva Venta"
                }}
              </div>
              <span class="icon"><s26-icon icon="dollar-sign"></s26-icon></span>
              <span> {{ $s26.currency(totals.total_sale) }} </span>
              <button
                v-show="saved_sales.length > 0"
                @click="getSavedSale"
                title="Siguiente Venta"
                type="button"
                class="
                  position-absolute
                  bottom-0
                  end-0
                  btn btn btn-icon
                  me-2
                  mb-2
                  text-white
                "
              >
                <s26-icon icon="arrow-right"></s26-icon>
              </button>
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <s26-textarea
              rows="4"
              v-model="current_sale.note"
              maxlength="500"
              mb=""
              placeholder="Nota..."
            >
            </s26-textarea>
          </div>
          <div class="col-12 col-sm-2">
            <div class="row">
              <div class="col-12">
                <span class="fw-bold me-2">Articulos:</span>
                {{ totals.articles }}
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">Descuento:</span>
                <span class="me-1" v-show="!discount_percentage">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                {{ $s26.currency(totals.discount) }}
                <span class="me-1" v-show="discount_percentage">
                  <s26-icon icon="percentage"></s26-icon>
                </span>
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">ICE:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                {{ $s26.currency(0) }}
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-2">
            <div class="row">
              <div class="col-12">
                <span class="fw-bold me-2">SubTotal 12%:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                <span class="float-end">
                  {{ $s26.currency(totals.subtotal_) }}
                </span>
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">SubTotal 0%:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                <span class="float-end">
                  {{ $s26.currency(totals.subtotal_0) }}
                </span>
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">SubTotal:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                <span class="float-end">
                  {{ $s26.currency(totals.subtotal) }}
                </span>
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">Iva 12%:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                <span class="float-end">
                  {{ $s26.currency(totals.iva_) }}
                </span>
              </div>
              <div class="col-12">
                <span class="fw-bold me-2">Total:</span>
                <span class="me-1">
                  <s26-icon icon="dollar-sign"></s26-icon>
                </span>
                <span class="float-end">
                  {{ $s26.currency(totals.total_sale) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- AÑADIR SERIES -->
    <transition name="fade">
      <s26-modal-multiple
        id="modal_series_sale"
        title="Series"
        :levels="['informacion']"
        body_style="min-height: 400px;"
        v-if="
          product_id >= 0 && product_id != null && modal_options == 'add_series'
        "
        @hideModal="product_id = null"
        footer_none
      >
        <template v-slot:level-0>
          <div class="col-12">
            <h2 class="h5 fw-600 text-center s26-text-blue">
              {{ current_sale.products[product_id]["product"] }}
              <br />
              <span class="fs-6">
                {{ current_sale.products[product_id]["code"] }}
              </span>
            </h2>
          </div>
          <div class="col-12">
            <s26-input-search
              id="search-serie"
              v-model="serie"
              @search="searchSeries(product_id)"
              rounded
            />
          </div>
          <div class="col-12">
            <div class="row">
              <div
                class="col-6 px-0"
                v-for="serie in product_series"
                :key="serie.id"
              >
                <div
                  :class="[
                    's26-align-center btn btn-sm m-2 text-break',
                    serie.status != 1
                      ? 'btn-secondary'
                      : current_sale.products[product_id]['series'].indexOf(
                          serie.id
                        ) > -1
                      ? 'btn-primary'
                      : 'btn-outline-primary',
                  ]"
                  @click="serie.status == 1 ? pushSerie(serie.id) : ''"
                >
                  {{ serie.serie }}
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-slot:footer>
          <div class="d-flex p-3">
            <span class="fw-bold">Seleccionadas: </span>
            <span class="mx-2">
              {{ current_sale.products[product_id]["series"].length }}
            </span>
            <span class="fw-bold"> de </span>
            <span class="mx-2">
              {{ current_sale.products[product_id]["amount"] }}
            </span>
          </div>
        </template>
      </s26-modal-multiple>
    </transition>
    <!-- BUSCAR PRODUCTOS -->
    <transition name="fade">
      <s26-select-product
        @select_product="select_product"
        v-show="modal_options == 'select_products'"
        v-model="modal_options"
      ></s26-select-product>
    </transition>
    <!-- CALCULADORA -->
    <transition name="fade">
      <s26-calculator
        v-show="modal_options == 'calculator'"
        v-model="modal_options"
      ></s26-calculator>
    </transition>
    <!-- PROCESAR VENTA -->
    <transition name="fade">
      <s26-process-sale
        v-if="modal_options == 'process_sale'"
        :info_sale="current_sale"
        v-model="modal_options"
        @success="success_sale"
      ></s26-process-sale>
    </transition>
    <!--  AJUSTE DE STOCK  -->
    <transition name="fade">
      <s26-modal-multiple
        id="modal-stock_adjustment"
        title="Ajuste de Stock"
        :levels="['informacion']"
        body_style="height: 165px;"
        v-if="
          product_id >= 0 &&
          product_id != null &&
          modal_options == 'stock_adjustment'
        "
        @hideModal="product_id = null"
        @onSubmit="stock_adjustment"
      >
        <template v-slot:level-0>
          <div class="col-12 mb-2">
            <h2 class="h5 fw-600 text-center s26-text-blue">
              {{ current_sale.products[product_id]["product"] }}
              <br />
              <span class="fs-6">
                {{ current_sale.products[product_id]["code"] }}
              </span>
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
            <s26-input-read
              label="Stock Actual"
              :content="current_sale.products[product_id]['stock']"
            >
            </s26-input-read>
          </div>
          <div class="col-4">
            <s26-input-read
              label="Nuevo Stock"
              :content="
                parseInt(current_sale.products[product_id]['stock']) +
                parseInt(adjustment.amount)
              "
            >
            </s26-input-read>
          </div>
        </template>
      </s26-modal-multiple>
    </transition>
  </div>
</template>
<script>
const def_customer = () => {
  return {
    id: 1,
    document: "9999999999999",
    full_name: "consumidor final",
    phone: "999999999",
    mobile: "9999999999",
    address: "sin dirección",
  };
};
const def_products = () => {
  return {
    id: 0,
    code: "",
    product: "",
    amount: 1,
    stock: 0,
    cost: 0,
    pvp: 0,
    discount_money: 0,
    net_total: 0,
    iva: 0,
    load: false,
    series: [],
  };
};
const def_sale = () => {
  return {
    id: 0,
    customer: def_customer(),
    products: [def_products()],
    note: "",
  };
};
export default {
  data: function () {
    return {
      current_sale: def_sale(),
      saved_sales: [],
      index_saved_sales: 0,
      document: "",
      disabled_customer: true,
      go_search_document: "",
      loading_data: false,
      show_cost: true,
      show_utility: false,
      discount_percentage: false,
      product_id: null,
      serie: "",
      product_series: [],
      modal_options: null,
      adjustment: {
        variant_id: 0,
        amount: 1,
      },
    };
  },
  created() {
    const self = this;
    $(document).keydown(function (e) {
      if (e.key == "Escape") {
        self.modal_options = null;
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "C") {
        $("#document").focus();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "V") {
        let i = self.current_sale.products.length - 1;
        $(`#search-product-${i}`).focus();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "H") {
        self.show_cost = !self.show_cost;
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "G") {
        self.discount_money_percentage();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "S") {
        self.saveSale();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "N") {
        self.newSale();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "Q") {
        self.delSavedSale();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "O") {
        self.open_box();
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "B") {
        self.modal_options = "select_products";
      }

      if (e.altKey && String.fromCharCode(e.keyCode) == "U") {
        self.modal_options = "calculator";
      }

      if (
        e.altKey &&
        String.fromCharCode(e.keyCode) == "W" &&
        self.current_sale.products.length > 1
      ) {
        self.modal_options = "process_sale";
      }

      window.onbeforeunload = function () {
        return "Do you really want to close?";
      };
    });
    this.getSavedSales();
  },
  computed: {
    totals: function () {
      let subtotal_ = 0;
      let subtotal_0 = 0;
      let iva_ = 0;
      let articles = 0;
      let stock = 0;
      let discount = 0;
      let total = 0;
      for (const i in this.current_sale.products) {
        let product = this.current_sale.products[i];
        if (product.id > 0) {
          //SUBTOTAL 12%
          if (product.iva > 0) {
            subtotal_ += product.net_total / _iva__;
            total += product.net_total;
          }
          //SUBTOTAL 0%
          if (product.iva == 0) {
            subtotal_0 += product.net_total;
          }
          articles += parseInt(product.amount);
          discount += product.discount_money;
        }
      }
      iva_ = total - subtotal_;

      const totals = {
        subtotal_: subtotal_,
        subtotal_0: subtotal_0,
        subtotal: subtotal_ + subtotal_0,
        iva_: iva_,
        total_sale: subtotal_ + subtotal_0 + iva_,
        articles: articles,
        discount: discount,
      };
      this.current_sale = Object.assign(this.current_sale, totals);
      return totals;
    },
  },
  methods: {
    searchCustomer() {
      if (
        this.document !== "" &&
        (this.document.length == 10 || this.document.length == 13)
      ) {
        this.axios
          .get("/customers/searchCustomer/" + this.document)
          .then((res) => {
            if (res.data == 0) {
              this.$alertify.success("Cliente Nuevo.");
              this.current_sale.customer = {
                id: 0,
                document: this.document,
                full_name: "",
                phone: "",
                mobile: "",
                address: "",
              };
              this.disabled_customer = false;

              $s26.copy_content(this.document);

              //WEB DEL REGISTRO CIVIL / SRI CONSULTAR RUC
              let url =
                this.document.length == 10
                  ? "https://servicios.registrocivil.gob.ec/esiddconsulweb/default.aspx"
                  : "https://srienlinea.sri.gob.ec/sri-en-linea/SriRucWeb/ConsultaRuc/Consultas/consultaRuc";

              this.go_search_document = window.open(url, "_blank");
            } else {
              this.$alertify.success("El Cliente Ya Existe.");
              this.document = "";
              this.current_sale.customer = res.data[0];
              this.disabled_customer = true;
              this.go_search_document.close();
            }
          })
          .catch((err) => console.log(err));
      } else {
        this.$alertify.error("El Número de Cédula o Ruc Es Incorrecto");
      }
    },
    onSubmitCustomer() {
      if ($s26.val_form("s26-container-customer")) {
        this.$alertify.confirm(
          `Desea Ingresar Nuevo Cliente?.`,
          () => {
            let formData = $s26.json_to_formData(this.current_sale.customer);
            $s26.show_loader_points();
            this.axios
              .post("/customers/setCustomer", formData)
              .then((res) => {
                if (res.data.type == 1) {
                  this.searchCustomer();
                  this.$alertify.success(res.data.msg);
                } else if (res.data.type == 2) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
              })
              .catch((e) => console.log(e));
          },
          () => this.$alertify.error("Acción Cancelada")
        );
      }
    },
    resetCustomer() {
      this.current_sale.customer = def_customer();
    },
    searchProduct(code, i, u = false) {
      let product = this.current_sale.products[i];
      if (code != "") {
        product.load = true;
        const params = {
          code,
        };
        this.axios
          .get("/products/searchProduct/", { params })
          .then((res) => {
            const prod = res.data[0];
            if (res.data != 0) {
              product.load = false;
              let index = this.current_sale.products.findIndex(function (el) {
                return el.id == prod["id"];
              });
              if (index >= 0 && !u) {
                this.$alertify.error("El producto ya se encuentra agregado");
                $(`#search-product-${i}`).select();
              } else {
                product.id = prod["id"];
                product.product_id = prod["product_id"];
                product.code = prod["ean_code"];
                product.product = `${prod["name"]} / ${prod["model"]} / ${prod["trademark"]} / ${prod["sku"]} `;
                product.stock = prod["stock"];
                product.cost = prod["cost"];
                product.discount = prod["discount"];
                product.iva = prod["iva"];
                product.pvp =
                  prod["pvp_3"] > 0
                    ? prod["pvp_3"]
                    : prod["pvp_2"] > 0
                    ? prod["pvp_2"]
                    : prod["pvp_1"] > 0
                    ? prod["pvp_1"]
                    : 0;
                product.pvp_1 = prod["pvp_1"];
                product.pvp_2 = prod["pvp_2"];
                product.pvp_3 = prod["pvp_3"];
                product.pvp_distributor = prod["pvp_distributor"];
                product.pvp_manual = prod["pvp_manual"];
                product.serial = prod["serial"];
                if (product.stock < 1) {
                  this.$alertify.error("Producto Sin Stock");
                  this.$alertify.message("Se debe realizar un ajuste de Stock");
                }
                this.pushProduct();
                this.calc_total_product(i);
              }
            } else {
              this.$alertify.error("No Se Encontro Ningún Producto");
              $(`#search-product-${i}`).select();
            }
          })
          .catch((err) => console.log(err));
      } else {
        if (this.current_sale.products.length > 1 && product.id > 0) {
          this.current_sale.products.splice(i, 1);
        }
        this.$alertify.error("Es Necesario escribir un Código");
      }
    },
    pushProduct() {
      let i = this.current_sale.products.length - 1;
      if (this.current_sale.products[i]["id"] > 0) {
        this.current_sale.products.push(def_products());
        setTimeout(() => {
          $(`#search-product-${i + 1}`).focus();
        }, 100);
      }
    },
    delProduct(i) {
      this.current_sale.products.splice(i, 1);
      setTimeout(() => {
        let e = this.current_sale.products.length - 1;
        $(`#search-product-${e}`).focus();
      }, 100);
    },
    valStock(i) {
      let product = this.current_sale.products[i];
      let amount = parseInt(product.amount);
      let stock = parseInt(product.stock);

      if (amount > stock) {
        product.amount = product.stock;
        this.$alertify.error("Stock Insuficiente");
      } else if (amount <= 0) {
        product.amount = 1;
        this.$alertify.error("Cantidad no puede ser menor a 1");
      } else if (product.amount < product.series.length) {
        product.amount = product.series.length;
        this.$alertify.error(
          "Deseleccionar series para menorar cantidad de producto"
        );
      }
      this.calc_total_product(i);
    },
    valStockProcessSale() {
      for (const i in this.current_sale.products) {
        let product = this.current_sale.products[i];
        if (product.stock < 1 && product.id > 0) {
          this.$alertify.error(
            "Realice el ajuste de stock de los productos sin stock, para poder procesar la venta."
          );
          return;
        }
      }
      this.modal_options = "process_sale";
    },
    open_stock_adjustment(i) {
      this.product_id = i;
      this.adjustment.variant_id = this.current_sale.products[i]["id"];
      this.adjustment.amount =
        parseInt(~this.current_sale.products[i]["stock"]) + 2;
      this.modal_options = "stock_adjustment";
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

                const i = this.product_id;
                const code = this.current_sale.products[i]["code"];

                this.searchProduct(code, i, true);
                this.modal_options = null;
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
    selectPvp(pvp, i) {
      let product = this.current_sale.products[i];
      let current_pvp = product.amount * product.pvp;
      let new_pvp = product.amount * pvp;
      let discount = current_pvp - new_pvp;
      product.discount_money = (
        !this.discount_percentage ? discount : (discount / new_pvp) * 100
      ).toFixed(2);
      this.calc_total_product(i);
    },
    discount_money_percentage() {
      this.discount_percentage = !this.discount_percentage;

      for (const i in this.current_sale.products) {
        let product = this.current_sale.products[i];
        if (product.id > 0) {
          let gross_total = product.amount * product.pvp;
          if (product.discount_money != "" && this.discount_percentage) {
            let net_total = gross_total - product.discount_money;
            product.discount_money = (
              (product.discount_money / net_total) *
              100
            ).toFixed(2);
          } else if (
            product.discount_money != "" &&
            !this.discount_percentage
          ) {
            let net_total = gross_total / (product.discount_money / 100 + 1);
            product.discount_money = (gross_total - net_total).toFixed(2);
          }
        }
        this.calc_total_product(i);
      }
    },
    calc_total_product(i) {
      let product = this.current_sale.products[i];
      product.net_total = !this.discount_percentage
        ? product.amount * product.pvp - product.discount_money
        : (product.amount * product.pvp) / (product.discount_money / 100 + 1);
    },
    newSale() {
      this.$alertify.confirm(
        `Desea Realizar Una Nueva Venta?.`,
        () => {
          this.current_sale = def_sale();
          this.$alertify.success("Nueva Venta.");
        },
        () => {
          this.$alertify.error("Acción Cancelada.");
        }
      );
    },
    searchSeries(i) {
      this.product_id = i;
      this.modal_options = "add_series";
      let product = this.current_sale.products[i];
      const params = { product_id: product.product_id, serie: this.serie };
      this.axios
        .get("/productsSeries/getSeries/", { params })
        .then((res) => {
          this.product_series = res.data.items;
        })
        .catch((err) => console.log(err));
    },
    pushSerie(id) {
      let product = this.current_sale.products[this.product_id];
      let i = product.series.indexOf(id);
      if (i == -1) {
        if (product.amount > product.series.length) {
          product.series.push(id);
        }
      } else {
        product.series.splice(i, 1);
      }
    },
    saveSale() {
      this.discount_percentage = false;

      if (this.current_sale.products.length > 1) {
        this.$alertify.confirm(
          `Desea Guardar Venta de <span class="fw-bold">${this.current_sale.customer.full_name}
          `,
          () => {
            let formData = $s26.json_to_formData(this.current_sale);
            $s26.show_loader_points();
            this.axios
              .post("/newSale/savedSale", formData)
              .then((res) => {
                if (res.data.type == 1) {
                  this.$alertify.success(res.data.msg);
                } else if (res.data.type == 2) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
                $s26.hide_loader_points();
                this.getSavedSales();
                this.current_sale = def_sale();
              })
              .catch((e) => console.log(e));
          },
          () => {
            this.$alertify.error("No se guardo la venta.");
          }
        );
      } else {
        this.$alertify.error("No se puede guardar una venta sin productos.");
      }
    },
    getSavedSales() {
      this.axios
        .get("/newSale/getSavedSales")
        .then((res) => {
          this.saved_sales = res.data;
        })
        .catch((err) => console.log(err));
    },
    getSavedSale() {
      this.discount_percentage = false;
      if (this.current_sale.products.length > 1) {
        let formData = $s26.json_to_formData(this.current_sale);
        $s26.show_loader_points();
        this.axios
          .post("/newSale/savedSale", formData)
          .then((res) => {
            $s26.hide_loader_points();
            this.getSavedSales();
            this.nextSale();
          })
          .catch((e) => console.log(e));
      } else if (
        this.current_sale.products.length == 1 &&
        this.current_sale.id > 0
      ) {
      } else {
        this.nextSale();
      }
    },
    nextSale() {
      this.index_saved_sales =
        this.index_saved_sales < this.saved_sales.length - 1
          ? this.index_saved_sales + 1
          : 0;
      let saved_sales = this.saved_sales[this.index_saved_sales];

      this.current_sale = JSON.parse(JSON.stringify(saved_sales));
      if (this.current_sale.customer_id == 0) {
        this.current_sale.customer = def_customer();
      }
      this.pushProduct();
    },
    delSavedSale() {
      this.$alertify.confirm(
        `Desea Cancelar la Venta Actual?.`,
        () => {
          $s26.show_loader_points();

          if (this.current_sale.id > 0) {
            this.axios
              .post("/newSale/delSavedSale/" + this.current_sale.id)
              .then((res) => {
                this.getSavedSales();
                setTimeout(() => {
                  if (this.saved_sales.length > 0) {
                    this.nextSale();
                  } else {
                    this.current_sale = def_sale();
                  }
                }, 200);
                $s26.hide_loader_points();
                if (res.data.type == 2) {
                  this.$alertify.success(res.data.msg);
                } else {
                  this.$alertify.error(res.data.msg);
                }
              })
              .catch((e) => console.log(e));
          } else {
            this.current_sale = def_sale();
            $s26.hide_loader_points();

            this.$alertify.success("Venta Cancelada.");
          }
        },
        () => {
          this.$alertify.error("Acción Cancelada.");
        }
      );
    },
    select_product: function (code) {
      let i = this.current_sale.products.length - 1;
      this.searchProduct(code, i);
    },
    open_box() {
      this.axios.defaults.baseURL = `http://${IP_ADRESS}/s26-printer`;
      const printer = {
        type_print: "open-box",
      };
      let formData = $s26.json_to_formData(printer);

      this.axios
        .post("/ticket.php", formData)
        .then((res) => {
          this.axios.defaults.baseURL = BASE_URL;
          if (res.data.type == 0) {
            this.$alertify.error(res.data.msg);
          }
        })
        .catch((e) => console.log(e));
    },
    success_sale() {
      if (this.current_sale.id > 0) {
        this.axios
          .post("/newSale/delSavedSale/" + this.current_sale.id)
          .then((res) => {
            this.getSavedSales();
            setTimeout(() => {
              if (this.saved_sales.length > 0) {
                this.nextSale();
              } else {
                this.current_sale = def_sale();
              }
            }, 200);
            $s26.hide_loader_points();
          })
          .catch((e) => console.log(e));
      } else {
        this.current_sale = def_sale();
        $s26.hide_loader_points();
      }
    },
  },
};
</script>

<style>
.s26-tarjet-sale {
  background-color: #fff;
  width: 100%;
  border-radius: 0.7rem;
  box-shadow: 0px 5px 8px 0px #b8b8b8, -5px -5px 14px #ffffff;
  padding: 1rem;
  min-height: 24vh;
}
.s26-tarjet-detail {
  min-height: 39vh;
  height: 39vh;
  overflow: auto;
}
.s26-tarjet-totals {
  min-height: 15vh;
}

.s26-table thead tr th {
  padding: 0.1rem 0.75rem;
}
.main-total {
  position: relative;
  height: 100px;
  width: 100%;
  border-radius: 0.5rem;
  background-color: var(--bs-gray-dark);
  text-align: center;
  color: #00ff00;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
}
.main-total span {
  font-size: 4rem;
  margin: 0.5rem;
}
.s26-tarjet-detail::-webkit-scrollbar {
  width: 4px;
  height: 4px;
}
</style>