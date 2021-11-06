<template>
  <s26-modal-multiple
    id="formProvider"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Proveedor'"
    :levels="levels"
    body_style="min-height: 370px;"
    @onReset="onReset"
    @onSubmit="onSubmit"
    @hideModal="hideModal"
  >
    <template v-slot:level-0>
      <div class="col-4" v-if="id != 0">
        <s26-input-read label="Id" :content="form.id"> </s26-input-read>
      </div>
      <div class="col">
        <s26-form-input
          label="Ruc"
          size="sm"
          id="form-trade_information-document"
          type="text"
          v-model="form.document"
          strictlength="13"
          number
          length
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Razón Social"
          size="sm"
          id="form-trade_information-business_name"
          type="text"
          v-model="form.business_name"
          maxlength="100"
          text
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Nombre Comercial"
          size="sm"
          id="form-trade_information-tradename"
          type="text"
          v-model="form.tradename"
          maxlength="100"
          text
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col">
        <s26-form-input
          label="Alias"
          size="sm"
          id="form-trade_information-alias"
          type="text"
          v-model="form.alias"
          maxlength="10"
          minlength="3"
          text
          s26_required
        >
        </s26-form-input>
      </div>
      <div class="col-4" v-if="id !== 0">
        <s26-select-status
          label="Estado"
          id="form-status"
          v-model="form.status"
        >
        </s26-select-status>
      </div>
      <div class="col-12" v-if="id !== 0">
        <span class="fw-bold">Creado el:</span>
        {{ $s26.formatDate(form.created_at, "xl") }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-12 col-sm-4">
        <s26-form-input
          label="Teléfono"
          size="sm"
          id="form-contacts-phone"
          type="text"
          v-model="form.phone"
          strictlength="9"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-4">
        <s26-form-input
          label="Teléfono 2"
          size="sm"
          id="form-contacts-phone2"
          type="text"
          v-model="form.phone_2"
          strictlength="9"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-4">
        <s26-form-input
          label="Nº Celular"
          size="sm"
          id="form-contacts-mobile_provider"
          type="text"
          v-model="form.mobile_provider"
          strictlength="10"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Email"
          size="sm"
          id="form-contacts-email"
          type="text"
          v-model="form.email"
          maxlength="100"
          email
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Nombre del Vendedor"
          size="sm"
          id="form-contacts-seller"
          type="text"
          v-model="form.seller"
          maxlength="100"
          text
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Nº Celular del Vendedor"
          size="sm"
          id="form-contacts-mobile_seller"
          type="text"
          v-model="form.mobile_seller"
          strictlength="10"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-5">
        <s26-select-cities
          size="sm"
          id="form-current_address-city"
          v-model="form.city_id"
        >
        </s26-select-cities>
      </div>
      <div class="col-7">
        <s26-form-input
          label="Dirección"
          size="sm"
          id="form-current_address-address"
          type="text"
          v-model="form.address"
          maxlength="100"
        >
        </s26-form-input>
      </div>
    </template>
    <template v-slot:level-2>
      <div
        class="col-12 row container-account"
        v-for="(account, index) in form.bank_accounts"
        :key="index"
      >
        <div class="col-12 col-sm-6">
          <s26-form-input
            label="Número de Cuenta"
            size="sm"
            :id="'form-bank_account-account_number' + index"
            type="text"
            v-model="form.bank_accounts[index]['account_number']"
            maxlength="15"
            minlength="6"
            number
            validate
          >
          </s26-form-input>
        </div>
        <div class="col-12 col-sm-6">
          <s26-form-input
            label="Cédula / Ruc"
            size="sm"
            :id="'form-bank_account-document' + index"
            type="text"
            v-model="form.bank_accounts[index]['document']"
            strictlength="10,13"
            number
            length
            validate
          >
          </s26-form-input>
        </div>
        <div class="col-12">
          <s26-select-bank
            size="sm"
            :id="'form-bank_account-bank_entity_id' + index"
            v-model="form.bank_accounts[index]['bank_entity_id']"
          >
          </s26-select-bank>
        </div>
        <div class="col-8">
          <s26-form-input
            label="Beneficiario"
            size="sm"
            :id="'form-bank_account-beneficiary' + index"
            type="text"
            v-model="form.bank_accounts[index]['beneficiary']"
            maxlength="100"
            text
          >
          </s26-form-input>
        </div>
        <div class="col-4 mb-3">
          <label class="form-label">
            Tipo de Cuenta
            <span class="text-danger">
              <s26-icon
                icon="asterisk"
                class="icon_asterisk_required"
              ></s26-icon>
            </span>
          </label>
          <select
            class="form-select form-select-sm"
            v-model="form.bank_accounts[index]['account_type']"
          >
            <option value="ahorros">Ahorros</option>
            <option value="corriente">Corriente</option>
          </select>
          <p class="invalid-feedback">Seleccione un tipo de cuenta</p>
        </div>
        <button
          type="button"
          class="btn-icon btn-cancel-account"
          @click="delAccount(index)"
        >
          <s26-icon icon="times"></s26-icon>
        </button>
      </div>
      <button class="btn btn-primary" @click="addAccount">
        <s26-icon icon="plus"></s26-icon>
      </button>
      <p
        class="p-2 text-center text-primary"
        v-if="form.bank_accounts.length == 0"
      >
        Puedes Ingresar Las Cuentas Bancarias Mas Tarde.
      </p>
    </template>
    <template v-slot:level-3>
      <div class="container-categories">
        <div
          :class="[
            'cat',
            form.categories.includes(parseInt(cat.id)) ? 'focus' : '',
          ]"
          v-for="cat in categories"
          :key="cat.id"
          @click="selectCategory(cat.id)"
        >
          {{ cat.name }}
        </div>
        <p
          class="p-2 text-center text-primary"
          v-if="form.categories.length == 0"
        >
          Puedes Ingresar Las Categorias Mas Tarde.
        </p>
      </div>
    </template>
    <template v-slot:level-4>
      <div class="col-12 row">
        <div class="col-10">
          <s26-form-input
            size="sm"
            id="addTrademark"
            type="text"
            v-model="trademark"
            maxlength="100"
            placeholder="Ej: Samsung"
          >
          </s26-form-input>
        </div>
        <div class="col-2 px-0">
          <button
            class="btn btn-primary btn-sm w-100"
            :disabled="trademark == ''"
            @click="addTrademark"
          >
            Añadir
          </button>
        </div>
      </div>
      <div class="container-categories">
        <div
          class="cat focus"
          v-for="mark in form.trademarks"
          :key="mark"
          @click="delTrademark(mark)"
        >
          {{ mark }}
        </div>
        <p
          class="p-2 text-center text-primary"
          v-if="form.trademarks.length == 0"
        >
          Puedes Ingresar Las Marcas Mas Tarde.
        </p>
      </div>
    </template>
  </s26-modal-multiple>
</template>

<script>
const def_form = () => {
  return {
    document: "",
    business_name: "",
    tradename: "",
    alias: "",
    phone: "",
    phone_2: "",
    mobile_provider: "",
    email: "",
    seller: "",
    mobile_seller: "",
    city_id: "",
    address: "",
    bank_accounts: [],
    categories: [],
    trademarks: [],
    status: 1,
    created_at: "",
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
  },
  data: function () {
    return {
      categories: [],
      levels: [
        "Información Comercial",
        "Información de Contacto",
        "Cuentas Bancarias",
        "Categorías",
        "Marcas",
      ],
      form: def_form(),
      trademark: "",
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
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Proveedor?.`,
        () => {
          let formData = $s26.json_to_formData(this.form);
          $s26.show_loader_points();
          this.axios
            .post("/providers/setProvider", formData)
            .then((res) => {
              for (let i in res.data) {
                if (res.data[i].type == 1 || res.data[i].type == 2) {
                  this.onReset();
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
            .catch((e) => console.log(e));
        },
        () => this.$alertify.error("Acción Cancelada")
      );
    },
    onReset() {
      $("[s26-required]").removeClass("is-invalid");
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        this.form = def_form();
      }
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
    selectCategory(id) {
      let idCat = parseInt(id);
      if (this.form.categories.includes(idCat)) {
        let i = this.form.categories.indexOf(idCat);
        this.form.categories.splice(i, 1);
      } else {
        this.form.categories.push(parseInt(id));
      }
    },
    hideModal() {
      this.$emit("input", null);
    },
    addAccount() {
      this.form.bank_accounts.push({
        account_number: "",
        bank_entity_id: "",
        document: "",
        beneficiary: "",
        account_type: "",
      });
    },
    delAccount(account) {
      this.form.bank_accounts.splice(account, 1);
    },
    addTrademark() {
      if (
        this.trademark !== "" &&
        this.form.trademarks.indexOf(this.trademark) == -1
      ) {
        this.form.trademarks.push(this.trademark);
        this.trademark = "";
        $("#addTrademark").focus();
      }
    },
    delTrademark(mark) {
      if (mark !== "" && this.form.trademarks.indexOf(mark) > -1) {
        let i = this.form.trademarks.indexOf(mark);
        this.form.trademarks.splice(i, 1);
        this.trademark = "";
        $("#addTrademark").focus();
      }
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
  padding: .5rem 1rem
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
.btn-icon.btn-cancel-account {
  position: absolute;
  font-size: 0.8rem;
  top: 0.3rem;
  right: 0.3rem;
  width: 0;
  height: 0;
  padding: 0.7rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bs-danger);
  color: var(--bs-white);
}
</style>