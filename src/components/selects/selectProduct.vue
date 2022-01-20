<template>
  <s26-modal
    id="selectProduct"
    @hideModal="hideModal"
    size="xl"
    style_body="height: 450px"
  >
    <template v-slot:header>
      <h5 class="modal-title">Buscar Producto</h5>
    </template>
    <template v-slot:body>
      <div class="px-3 mb-2">
        <div class="btn btn-primary">
          {{ s26_data.info.count }}
        </div>
        <button
          type="button"
          class="btn btn-danger mx-1"
          @click="onReset"
          title="alt + r"
        >
          <s26-icon icon="trash-alt"></s26-icon>
        </button>
        <button class="btn btn-primary" @click="allRows()">
          <s26-icon icon="sync-alt"></s26-icon>
        </button>
      </div>
      <s26-table
        :rows="s26_data.info.count"
        @get="allRows"
        v-model="filter.perPage"
        :loading_data="loading_data"
        relative
        height="auto"
        rows_load_data="4"
      >
        <template v-slot:tr>
          <tr>
            <th class="length-int">Codigo</th>
            <th class="length-action">SKU</th>
            <th class="length-description">Producto</th>
            <th class="length-action">Modelo</th>
            <th class="length-action">Marca</th>
            <th class="length-int">Categoría</th>
            <th class="length-action text-center">Stock</th>
            <th class="length-action text-center">Pvp</th>
          </tr>
          <tr class="filter-product">
            <td class="length-int">
              <s26-form-input
                id="filter-code"
                @enter="allRows"
                placeholder="Codigo..."
                v-model="filter.ean_code"
              >
              </s26-form-input>
            </td>
            <td class="length-action">
              <s26-form-input
                @enter="allRows"
                placeholder="SKU..."
                v-model="filter.sku"
              >
              </s26-form-input>
            </td>
            <td class="length-description">
              <s26-form-input
                @enter="allRows"
                placeholder="Producto..."
                v-model="filter.product"
              >
              </s26-form-input>
            </td>
            <td class="length-action">
              <s26-form-input
                @enter="allRows"
                placeholder="Modelo..."
                v-model="filter.model"
              >
              </s26-form-input>
            </td>
            <td class="length-action">
              <s26-form-input
                @enter="allRows"
                placeholder="Marca..."
                v-model="filter.trademark"
              >
              </s26-form-input>
            </td>
            <td class="length-int">
              <s26-select-category
                id="category"
                v-model="filter.category"
                @change="allRows"
                all
                label="false"
              ></s26-select-category>
            </td>
            <td class="length-stock">
              <s26-form-input disabled> </s26-form-input>
            </td>
            <td class="length-action">
              <s26-form-input
                @enter="allRows"
                placeholder="Pvp..."
                v-model="filter.pvp"
              >
              </s26-form-input>
            </td>
          </tr>
        </template>
        <template v-slot:body v-if="!loading_data">
          <tr
            v-for="item in s26_data.items"
            :key="item.id"
            tabindex="0"
            @dblclick="selectProduct(item.ean_code)"
            @keypress.enter="selectProduct(item.ean_code)"
          >
            <td class="length-int">{{ item.ean_code }}</td>
            <td class="length-action">{{ item.sku }}</td>
            <td class="length-description">{{ item.name }}</td>
            <td class="length-action">{{ item.model }}</td>
            <td class="length-action">{{ item.trademark }}</td>
            <td class="length-int">{{ item.category }}</td>
            <td class="length-stock text-center">{{ item.stock }}</td>
            <td class="length-action text-center">
              <s26-icon icon="dollar-sign"></s26-icon> {{ item.pvp }}
            </td>
          </tr>
        </template>
      </s26-table>
    </template>
  </s26-modal>
</template>

<script>
const def_filter = () => {
  return {
    ean_code: "",
    sku: "",
    product: "",
    model: "",
    trademark: "",
    category: "",
    pvp: "",
    perPage: 25,
  };
};

export default {
  data: function () {
    return {
      filter: def_filter(),
      s26_data: {
        info: {
          rows: "",
          total_stock: "",
          total_entries: "",
          total_outputs: "",
          total_cost: "",
          total_pvp: "",
        },
      },
      loading_data: false,
      idRow: null,
      action: "",
    };
  },
  created() {
    const self = this;

    $(document).keydown(function (e) {
      if (e.altKey && String.fromCharCode(e.keyCode) == "R") {
        self.onReset();
      }
    });
    this.allRows();
  },
  methods: {
    allRows() {
      this.loading_data = true;
      const params = {};
      for (let fil in this.filter) params[fil] = this.filter[fil];
      this.axios
        .get("/variants/getVariants/", {
          params,
        })
        .then((res) => {
          console.log(res);
          this.s26_data = res.data;
          this.loading_data = false;
        })
        .catch((err) => console.log(err));
    },
    onReset() {
      this.filter = def_filter();
      this.allRows();
    },
    hideModal() {
      this.$emit("input", null);
    },
    selectProduct(code) {
      this.$emit("input", null);
      this.$emit("select_product", code);
    },
  },
};
</script>

<style>
tr.filter-product td div {
  margin: 0.1rem !important;
}
tr.filter-product td {
  border: none !important;
  padding: 0.2rem 0 !important;
}
tr.filter-product td .form-control {
  padding: 0.15rem 0.25rem !important;
  min-height: 1rem !important;
}
</style>