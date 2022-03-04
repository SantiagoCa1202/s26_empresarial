<template>
  <s26-modal-multiple
    id="formStocktaking"
    title="Añadir Producto"
    :levels="levels"
    body_style="height: 455px;max-height:455px"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-sm-12">
        <s26-input-search
          v-model="search"
          @search="select_product"
          @update="perPage = 8"
          rounded
          number
          type="tel"
        />
      </div>
      <div class="col-sm-4">
        <s26-input-read label="Id" :content="form.product_variant_id">
        </s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read label="Código" :content="form.ean_code">
        </s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read label="Sku" :content="form.sku"> </s26-input-read>
      </div>
      <div class="col-sm-12">
        <s26-input-read label="Nombre" :content="form.name"> </s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read label="Modelo" :content="form.model"> </s26-input-read>
      </div>
      <div class="col-sm-6">
        <s26-input-read label="Marca" :content="form.trademark">
        </s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read label="P.V.P" :content="form.pvp" money>
        </s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read label="Stock" :content="form.stock"> </s26-input-read>
      </div>
      <div class="col-sm-4">
        <s26-input-read label="Contable Actual" :content="form.accountant">
        </s26-input-read>
      </div>
      <div class="col-6 mx-auto">
        <s26-form-input
          label="Cantidad"
          size=""
          id="form-amount"
          type="tel"
          v-model="form.amount"
          maxlength="100"
          int
          s26_required
          variant="text-center"
          autocomplete="off"
          @enter="onSubmit"
          :disabled="form.product_variant_id == 0"
        >
        </s26-form-input>
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
        id: 0,
        product_variant_id: 0,
        ean_code: "",
        name: "",
        sku: "",
        model: "",
        trademark: "",
        pvp: 0,
        stock: 0,
        accountant: 0,
        amount: 1,
      },
      search: "",
      levels: ["Información de Producto"],
    };
  },
  created() {},
  methods: {
    select_product: function () {
      const params = {
        code: this.search,
      };
      this.axios
        .get("/products/searchProduct/", { params })
        .then((res) => {
          if (res.data != 0) {
            const prod = res.data[0];
            this.form.product_variant_id = prod["id"];
            this.form.ean_code = prod["ean_code"];
            this.form.name = prod["name"];
            this.form.model = prod["model"];
            this.form.trademark = prod["trademark"];
            this.form.sku = prod["sku"];
            this.form.pvp = prod["pvp"];
            this.form.stock = prod["stock"];
            this.getProduct(prod["id"]);
          } else {
            this.$alertify.error("No Se Encontro Ningún Producto");
          }
          this.search = "";
          $(".modal-multiple-footer .btn-info").focus();
        })
        .catch((err) => console.log(err));
    },
    getProduct(id) {
      this.axios
        .get("/stocktaking/getProduct/" + id)
        .then((res) => {
          if (res.data != 0) {
            this.form.id = res.data.id;
            this.form.accountant = res.data.accountant;
          }
        })
        .catch((err) => console.log(err));
    },
    onSubmit() {
      if (this.form.product_variant_id > 0) {
        let formData = $s26.json_to_formData(this.form);
        $s26.show_loader_points();
        this.axios
          .post("/stocktaking/setProduct", formData)
          .then((res) => {
            if (res.data.type > 0) {
              this.onReset();
              this.$alertify.success(res.data.msg);
            } else {
              this.$alertify.error(res.data.msg);
            }
            $s26.hide_loader_points();
            this.$emit("update");
          })
          .catch((err) => console.log(err));
      } else {
        this.$alertify.success(
          "Error al guardar producto, intentelo nuevamente"
        );
      }
    },
    onReset() {
      this.form = {
        id: 0,
        product_variant_id: 0,
        ean_code: "",
        name: "",
        sku: "",
        model: "",
        trademark: "",
        pvp: 0,
        stock: 0,
        accountant: 0,
        amount: 1,
      };
    },
    hideModal() {
      this.$emit("input", null);
    },
  },
};
</script>
