<template>
  <div class="row align-items">
    <s26-sidebar
      title="Wallet"
      icon="wallet"
      v-model="activeSidebar"
      @update="wallet"
      @reset="onReset"
    >
      <template v-slot:search>
        <div class="container">
          <s26-date-picker
            id="date"
            enable="range"
            size="sm"
            v-model="filter.date"
            @change="wallet"
            label="Periodo"
          ></s26-date-picker>
          <s26-select-establishment
            id="filter-establishment"
            all
            v-model="filter.establishment_id"
            @change="wallet"
          ></s26-select-establishment>
          <s26-select-box
            id="filter-box"
            all
            v-model="filter.box_id"
            @change="wallet"
          >
          </s26-select-box>
        </div>
      </template>
    </s26-sidebar>
    <section :class="['main px-0', { 'mainWidth-100': !activeSidebar }]">
      <div class="s26-container-table">
        <div class="row mx-3 mt-2">
          <div class="col-sm-6">
            <div class="row">
              <!-- INICIO -->
              <div class="col-12 mb-4">
                <s26-card-utilities
                  title="Ventas"
                  :values="s26_data.sales"
                  :variant="{ cost: 'warning' }"
                >
                  <template v-slot:footer>
                    <div class="row mx-0">
                      <div class="col">
                        <span class="fw-bold text-center">Productos:</span>
                        {{ s26_data.sales.products }}
                      </div>
                      <div class="col">
                        <span class="fw-bold">Descuento:</span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                        {{ s26_data.sales.discount }}
                      </div>
                    </div>
                  </template>
                </s26-card-utilities>
              </div>
              <div class="col-12 mb-4">
                <s26-card title="Ventas Por Categoria">
                  <template v-slot:body>
                    <div
                      class="col-12 mb-4"
                      v-for="cat in s26_data.salesPerCategory"
                      :key="cat.id"
                    >
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              :style="`background-color: ${cat.color}`"
                            >
                              <s26-icon
                                :icon="cat.icon"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">{{ cat.name }}</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(cat.total_sale) }} -
                            {{ cat.total_products }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxSalesPerCategory > 0
                                ? (cat.total_products * 100) /
                                  maxSalesPerCategory
                                : 0
                            "
                            :background="cat.color"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-12 mb-4">
                <s26-card-utilities
                  title="Ingresos Externos"
                  :values="s26_data.external_incomes"
                  :variant="{ cost: 'danger' }"
                ></s26-card-utilities>
              </div>
              <div class="col-12 mb-4">
                <s26-card title="Flujo de Costos">
                  <template v-slot:body>
                    <div class="col-4">
                      <s26-input-read
                        label="Ingresos Brutos"
                        :content="flow.incomes.cost"
                        money
                      >
                      </s26-input-read>
                    </div>
                    <div class="col-4">
                      <s26-input-read
                        label="Egresos"
                        :content="flow.expenses.cost"
                        money
                      >
                      </s26-input-read>
                    </div>
                    <div class="col-4">
                      <s26-input-read
                        label="Total Neto"
                        :content="flow.net_total_cost"
                        money
                      >
                      </s26-input-read>
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-12 mb-4">
                <s26-card title="Flujo de Ganancias">
                  <template v-slot:body>
                    <div class="col-4">
                      <s26-input-read
                        label="Ingresos Brutos"
                        :content="flow.incomes.gain"
                        money
                      >
                      </s26-input-read>
                    </div>
                    <div class="col-4">
                      <s26-input-read
                        label="Egresos"
                        :content="flow.expenses.gain"
                        money
                      >
                      </s26-input-read>
                    </div>
                    <div class="col-4">
                      <s26-input-read
                        label="Total Neto"
                        :content="flow.net_total_gain"
                        money
                      >
                      </s26-input-read>
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-12 mb-4">
                <s26-card title="Flujo De Productos">
                  <template v-slot:body>
                    <div class="col-12 mb-4">
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              style="background-color: #6f42c1"
                            >
                              <s26-icon
                                icon="sign-in-alt"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">Entradas</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            {{ s26_data.products.entries }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxProducts > 0
                                ? (s26_data.products.entries * 100) /
                                  maxProducts
                                : 0
                            "
                            background="#6f42c1"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-4">
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              style="background-color: #fd7e14"
                            >
                              <s26-icon
                                icon="sign-out-alt"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">Salidas</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            {{ s26_data.products.outlets }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxProducts > 0
                                ? (s26_data.products.outlets * 100) /
                                  maxProducts
                                : 0
                            "
                            background="#fd7e14"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-4">
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              style="background-color: #dc3545"
                            >
                              <s26-icon
                                icon="sync"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">Devoluciones</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            {{ s26_data.products.returns }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxProducts > 0
                                ? (s26_data.products.returns * 100) /
                                  maxProducts
                                : 0
                            "
                            background="#dc3545"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-4">
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              style="background-color: #6c757d"
                            >
                              <s26-icon
                                icon="ambulance"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">Averias</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            {{ s26_data.products.damageds }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxProducts > 0
                                ? (s26_data.products.damageds * 100) /
                                  maxProducts
                                : 0
                            "
                            background="#6c757d"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-4">
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <div
                              class="btn-icon s26-align-center me-3"
                              style="background-color: #0d6efd"
                            >
                              <s26-icon
                                icon="cog"
                                class="text-white"
                              ></s26-icon>
                            </div>
                            <h2 class="h6 fw-600 m-0">Ajustes</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            {{ s26_data.products.settings }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxProducts > 0
                                ? (s26_data.products.settings * 100) /
                                  maxProducts
                                : 0
                            "
                            background="#0d6efd"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                  </template>
                </s26-card>
              </div>
              <!-- FINAL -->
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-12 mb-4">
                <s26-card-utilities
                  title="Devoluciones"
                  :values="s26_data.returns"
                  :variant="{ cost: 'danger' }"
                >
                  <template v-slot:footer>
                    <div class="row mx-0">
                      <div class="col">
                        <span class="fw-bold text-center">Productos:</span>
                        {{ s26_data.returns.products }}
                      </div>
                      <div class="col">
                        <span class="fw-bold">Descuento:</span>
                        <s26-icon icon="dollar-sign"></s26-icon>
                        {{ s26_data.returns.discount }}
                      </div>
                    </div>
                  </template>
                </s26-card-utilities>
              </div>
              <div class="col-12 mb-4">
                <s26-card title="Ventas Por Forma de Pago">
                  <template v-slot:body>
                    <div
                      class="col-12 mb-4"
                      v-for="(pay, index) in s26_data.salesPerPaymentMethod"
                      :key="pay.id"
                    >
                      <div class="row">
                        <div class="col-6">
                          <div class="s26-align-y-center w-100 mb-2">
                            <h2 class="h6 fw-600 m-0">{{ pay.name }}</h2>
                          </div>
                        </div>
                        <div class="col-6 s26-align-y-center">
                          <div class="w-100 text-end">
                            <s26-icon icon="dollar-sign"></s26-icon>
                            {{ $s26.currency(pay.total_sale) }}
                          </div>
                        </div>
                        <div class="col-12">
                          <s26-progress-bar
                            :percentage="
                              maxSalesPerPaymentMethod > 0
                                ? (pay.total_sale * 100) /
                                  maxSalesPerPaymentMethod
                                : 0
                            "
                            :background="`#${colors[index].hexadecimal}`"
                          ></s26-progress-bar>
                        </div>
                      </div>
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-12 mb-4">
                <s26-card-utilities
                  title="Egresos En Caja"
                  :values="s26_data.expenses_box"
                  :background="{
                    cost: `#${colors[0].hexadecimal}`,
                    gain: `#${colors[1].hexadecimal}`,
                  }"
                ></s26-card-utilities>
              </div>
              <div class="col-12 mb-4">
                <s26-card-utilities
                  title="Egresos En Banco"
                  :values="s26_data.expenses_bank"
                  :background="{
                    cost: `#${colors[2].hexadecimal}`,
                    gain: `#${colors[3].hexadecimal}`,
                  }"
                ></s26-card-utilities>
              </div>
              <div class="col-6 mb-4">
                <s26-card title="Depositos">
                  <template v-slot:body>
                    <div class="col-12 mb-3 h3 s26-align-center">
                      <s26-icon icon="dollar-sign"></s26-icon>
                      {{ $s26.currency(s26_data.deposits) }}
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-6 mb-4">
                <s26-card title="Compras">
                  <template v-slot:body>
                    <div class="col-12 mb-3 h3 s26-align-center">
                      <s26-icon icon="dollar-sign"></s26-icon>
                      {{ $s26.currency(s26_data.buys) }}
                    </div>
                  </template>
                </s26-card>
              </div>
              <div class="col-6 mb-4">
                <s26-card title="Clientes">
                  <template v-slot:body>
                    <div class="col-12 mb-3 h3 s26-align-center">
                      {{ s26_data.customers }}
                    </div>
                  </template>
                </s26-card>
              </div>

              <!-- FINAL -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
const def_filter = () => {
  return {
    date: [],
    establishment_id: "",
    box_id: "",
  };
};
export default {
  data: function () {
    return {
      filter: def_filter(),
      s26_data: {
        sales: {
          cost: 0,
          gain: 0,
          products: 0,
          discount: 0,
        },
        returns: {
          cost: 0,
          gain: 0,
          products: 0,
          discount: 0,
        },
        salesPerCategory: [],
        salesPerPaymentMethod: [],
        external_incomes: {
          cost: 0,
          gain: 0,
        },
        expenses_box: {
          cost: 0,
          gain: 0,
        },
        expenses_bank: {
          cost: 0,
          gain: 0,
        },
        deposits: 0,
        buys: 0,
        customers: 0,
        products: {
          entries: 0,
          outlets: 0,
          returns: 0,
          damageds: 0,
          settings: 0,
        },
      },
      maxSalesPerCategory: 0,
      maxSalesPerPaymentMethod: 0,
      maxProducts: 0,
      activeSidebar: true,

      colors: [],
    };
  },
  created() {
    this.getColors();
    this.wallet();
  },

  computed: {
    flow: function () {
      let incomes = { gain: 0, cost: 0 };
      let expenses = { gain: 0, cost: 0 };
      incomes.gain =
        parseFloat(this.s26_data.sales.gain) +
        parseFloat(this.s26_data.external_incomes.gain);

      incomes.cost =
        parseFloat(this.s26_data.sales.cost) +
        parseFloat(this.s26_data.external_incomes.cost);

      expenses.gain =
        parseFloat(this.s26_data.expenses_box.gain) +
        parseFloat(this.s26_data.expenses_bank.gain);

      expenses.cost =
        parseFloat(this.s26_data.expenses_box.cost) +
        parseFloat(this.s26_data.expenses_bank.cost);

      let net_total_gain = incomes.gain - expenses.gain;
      let net_total_cost = incomes.cost - expenses.cost;

      return {
        incomes,
        expenses,
        net_total_gain,
        net_total_cost,
      };
    },
  },
  methods: {
    wallet() {
      this.reportSales();
      this.reportReturns();
      this.reportSalesPerCategories();
      this.reportSalesPerPaymentMethod();
      this.reportExternalIncomes();
      this.reportExpenses();
      this.reportDeposits();
      this.reportBuys();
      this.reportCustomers();
      this.reportProducts();
    },
    getColors() {
      this.axios
        .get("/system/getColors/")
        .then((res) => (this.colors = res.data.items))
        .catch((err) => console.log(err));
    },
    reportSales() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportSales/", {
          params,
        })
        .then((res) => {
          this.s26_data.sales.cost = res.data.total_cost;
          this.s26_data.sales.gain = res.data.total_gain;
          this.s26_data.sales.products = res.data.total_products;
          this.s26_data.sales.discount = res.data.total_discount;
        })
        .catch((err) => console.log(err));
    },

    reportReturns() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportReturns/", {
          params,
        })
        .then((res) => {
          this.s26_data.returns.cost = res.data.total_cost;
          this.s26_data.returns.gain = res.data.total_gain;
          this.s26_data.returns.products = res.data.total_products;
          this.s26_data.returns.discount = res.data.total_discount;
        })
        .catch((err) => console.log(err));
    },

    reportSalesPerCategories() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportSalesPerCategories/", {
          params,
        })
        .then((res) => {
          this.s26_data.salesPerCategory = res.data;

          let newArrSalesPerCategory = this.s26_data.salesPerCategory.map(
            function (cat) {
              return cat.total_products;
            }
          );

          this.maxSalesPerCategory = Math.max(...newArrSalesPerCategory);
        })
        .catch((err) => console.log(err));
    },

    reportSalesPerPaymentMethod() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportSalesPerPaymentMethod/", {
          params,
        })
        .then((res) => {
          this.s26_data.salesPerPaymentMethod = res.data;

          let newArrSalesPerPaymentMethod =
            this.s26_data.salesPerPaymentMethod.map(function (pay) {
              return pay.total_sale;
            });

          this.maxSalesPerPaymentMethod = Math.max(
            ...newArrSalesPerPaymentMethod
          );
        })
        .catch((err) => console.log(err));
    },

    reportExternalIncomes() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportExternalIncomes/", {
          params,
        })
        .then((res) => {
          this.s26_data.external_incomes.cost = res.data.total_cost;
          this.s26_data.external_incomes.gain = res.data.total_gain;
        })
        .catch((err) => console.log(err));
    },

    reportExpenses() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportExpenses/", {
          params,
        })
        .then((res) => {
          this.s26_data.expenses_box.cost = res.data.total_box_cost;
          this.s26_data.expenses_box.gain = res.data.total_box_gain;
          this.s26_data.expenses_bank.cost = res.data.total_bank_cost;
          this.s26_data.expenses_bank.gain = res.data.total_bank_gain;
        })
        .catch((err) => console.log(err));
    },

    reportDeposits() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportDeposits/", {
          params,
        })
        .then((res) => {
          this.s26_data.deposits = res.data.total_deposits;
        })
        .catch((err) => console.log(err));
    },

    reportBuys() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportBuys/", {
          params,
        })
        .then((res) => {
          this.s26_data.buys = res.data.total_buys;
        })
        .catch((err) => console.log(err));
    },

    reportCustomers() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportCustomers/", {
          params,
        })
        .then((res) => {
          this.s26_data.customers = res.data.total_customers;
        })
        .catch((err) => console.log(err));
    },

    reportProducts() {
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];

      this.axios
        .get("/wallet/getReportProducts/", {
          params,
        })
        .then((res) => {
          this.s26_data.products = res.data;
          this.maxProducts = this.s26_data.products.entries;
        })
        .catch((err) => console.log(err));
    },

    onReset() {
      this.filter = def_filter();
      this.wallet();
    },
  },
};
</script>