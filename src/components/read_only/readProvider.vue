<template>
  <s26-modal-multiple
    id="readProvider"
    title="Información de Proveedor"
    :levels="levels"
    body_style="min-height: 345px; height: 345px"
    @hideModal="hideModal"
    readOnly
  >
    <template v-slot:level-0>
      <div class="col-4">
        <s26-input-read label="Id" :content="form.id"> </s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Ruc" :content="form.document"> </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read label="Razón Socail" :content="form.business_name">
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read label="Nombre Comercial" :content="form.tradename">
        </s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Alias" :content="form.alias"> </s26-input-read>
      </div>
      <div class="col-4">
        <s26-input-read
          label="Estado"
          :content="form.status == 1 ? 'Activo' : 'Inactivo'"
        >
        </s26-input-read>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>

    <template v-slot:level-1>
      <div class="col-12 col-sm-4">
        <s26-input-read label="Teléfono" :content="form.phone">
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-4">
        <s26-input-read label="Teléfono 2" :content="form.phone_2">
        </s26-input-read>
      </div>
      <div class="col-4">
        <s26-input-read label="N° Celular" :content="form.mobile_provider">
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read label="Email" :content="form.email"> </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read label="Vendedor" :content="form.seller">
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="N° Celular Vendedor"
          :content="form.mobile_seller"
        >
        </s26-input-read>
      </div>
      <div class="col-5">
        <s26-input-read label="Ciudad" :content="form.city.name">
        </s26-input-read>
      </div>
      <div class="col-7">
        <s26-input-read label="Dirección" :content="form.address">
        </s26-input-read>
      </div>
    </template>
    <template v-slot:level-2>
      <div
        class="col-12 row container-account"
        v-for="(account, index) in form.bank_accounts"
        :key="index"
      >
        <div class="col-12 col-sm-6">
          <s26-input-read
            label="Número de Cuenta"
            :content="form.bank_accounts[index]['account_number']"
          >
          </s26-input-read>
        </div>
        <div class="col-12 col-sm-6">
          <s26-input-read
            label="Cédula / Ruc"
            :content="form.bank_accounts[index]['document']"
          >
          </s26-input-read>
        </div>
        <div class="col-12">
          <s26-input-read
            label="Entidad Bancaria"
            :content="form.bank_accounts[index]['bank_entity']['bank_entity']"
          >
          </s26-input-read>
        </div>
        <div class="col-8">
          <s26-input-read
            label="Beneficiario"
            :content="form.bank_accounts[index]['beneficiary']"
          >
          </s26-input-read>
        </div>
        <div class="col-4">
          <s26-input-read
            label="Tipo de Cuenta"
            :content="form.bank_accounts[index]['account_type']"
          >
          </s26-input-read>
        </div>
      </div>
    </template>
    <template v-slot:level-3>
      <div class="container-categories">
        <div
          :class="[
            'cat',
            form.categories.includes(parseInt(cat.id)) ? 'focus' : 'd-none',
          ]"
          v-for="cat in categories"
          :key="cat.id"
        >
          {{ cat.name }}
        </div>
      </div>
    </template>
    <template v-slot:level-4>
      <div class="container-categories">
        <div class="cat focus" v-for="mark in form.trademarks" :key="mark">
          {{ mark }}
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
      type: Number,
      required: true,
    },
  },
  data: function () {
    return {
      categories: [],
      levels: [
        "Información Comercial",
        "Información de Contacto",
        "Cuenta Bancaria",
        "Categorías",
        "Marcas",
      ],
      form: {
        bank_accounts: {
          bank_entity: {
            bank_entity: "",
          },
        },
        city: {},
        categories: [],
        trademarks: [],
        status: 1,
        created_at: "",
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) this.infoData(this.id);
    this.getCategories();
  },
  methods: {
    infoData(id) {
      $("[s26-required]").removeClass("is-invalid");
      this.axios
        .get("/providers/getProvider/" + id)
        .then((res) => (this.form = res.data))
        .catch((err) => console.log(err));
    },
    getCategories() {
      const params = {
        perPage: 500,
      };
      this.axios
        .get("/categories/getCategories/", {
          params,
        })
        .then((res) => (this.categories = res.data.items))
        .catch((err) => console.log(err));
    },
    hideModal() {
      this.$emit("input", null);
      $s26.delete_cookie("id", "providers");
    },
  },
};
</script>
<style scoped>
.container-categories {
  width: 100%;
  height: 100%;
  display: flex;
  flex-wrap: wrap;
  overflow: hidden;
  flex-shrink: 0;
  flex-direction: row;
  align-content: stretch;
  justify-content: center;
  align-items: center;
  overflow-y: auto;
}
.container-categories .cat {
  background-color: rgba(0, 0, 0, 0.1);
  color: var(--s26-blue);
  font-weight: bold;
  height: 30px;
  margin: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: 0.3s;
  cursor: pointer;
  padding: 0.5rem 1rem;
}
.container-categories .cat:hover {
  background-color: #16859671;
  color: #fff;
}
.container-categories .cat.focus {
  background-color: var(--bs-primary);
  color: #fff;
}
.container-account {
  position: relative;
  border-radius: 0.25rem !important;
  padding: 1rem !important;
  margin-bottom: 1rem !important;
  margin-right: 0 !important;
  margin-left: 0 !important;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
  border: 1px solid #e8e8e8;
}
</style>