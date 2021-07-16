<template>
  <s26-modal-multiple
    id="readProvider"
    title="Información de Proveedor"
    v-model="level_select"
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
        <s26-input-read label="Ruc" :content="form.trade_information.document">
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Razón Socail"
          :content="form.trade_information.business_name"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Nombre Comercial"
          :content="form.trade_information.tradename"
        >
        </s26-input-read>
      </div>
      <div class="col-8">
        <s26-input-read label="Alias" :content="form.trade_information.alias">
        </s26-input-read>
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
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="Teléfono Convencional"
          :content="form.contacts.phone"
        >
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="Teléfono Convencional 2"
          :content="form.contacts.phone_2"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="N° Celular Empresarial"
          :content="form.contacts.mobile_provider"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read label="Email" :content="form.contacts.email">
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read label="Vendedor" :content="form.contacts.seller">
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="N° Celular Vendedor"
          :content="form.contacts.mobile_seller"
        >
        </s26-input-read>
      </div>
    </template>

    <template v-slot:level-2>
      <div class="col-12">
        <s26-input-read label="Ciudad" :content="form.current_address.city">
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Dirección"
          :content="form.current_address.address"
        >
        </s26-input-read>
      </div>
    </template>

    <template v-slot:level-3>
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="Número de Cuenta"
          :content="form.bank_accounts.account_number"
        >
        </s26-input-read>
      </div>
      <div class="col-12 col-sm-6">
        <s26-input-read
          label="Cédula / Ruc"
          :content="form.bank_accounts.document"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Entidad Bancaria"
          :content="form.bank_accounts.bank_entity.bank_entity"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Beneficiario"
          :content="form.bank_accounts.beneficiary"
        >
        </s26-input-read>
      </div>
      <div class="col-12">
        <s26-input-read
          label="Tipo de Cuenta"
          :content="form.bank_accounts.account_type"
        >
        </s26-input-read>
      </div>
    </template>

    <template v-slot:level-4>
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
        "Contactos",
        "Dirección Actual",
        "Cuenta Bancaria",
        "Categorías",
      ],
      level_select: 0,
      form: {
        trade_information: {},
        contacts: {},
        current_address: {},
        bank_accounts: {
          bank_entity: {
            bank_entity: "",
          },
        },
        categories: [],
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
  width: 100px;
  height: 30px;
  margin: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: 0.3s;
  cursor: pointer;
}
.container-categories .cat:hover {
  background-color: #16859671;
  color: #fff;
}
.container-categories .cat.focus {
  background-color: #168596;
  color: #fff;
}
</style>