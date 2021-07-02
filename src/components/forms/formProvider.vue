<template>
  <s26-modal-multiple
    id="formProvider"
    :title="(id == 0 ? 'Nuevo ' : 'Editar ') + 'Proveedor'"
    :levels="levels"
    body_style="min-height: 350px; height: 350px"
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
          v-model="form.trade_information.document"
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
          v-model="form.trade_information.business_name"
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
          v-model="form.trade_information.tradename"
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
          v-model="form.trade_information.alias"
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
        <span class="fw-bold">Creado el:</span> {{ form.created_at }}
      </div>
    </template>
    <template v-slot:level-1>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Teléfono Convencional"
          size="sm"
          id="form-contacts-phone"
          type="text"
          v-model="form.contacts.phone"
          strictlength="9"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Teléfono Convencional 2"
          size="sm"
          id="form-contacts-phone2"
          type="text"
          v-model="form.contacts.phone_2"
          strictlength="9"
          number
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Nº Celular Empresarial"
          size="sm"
          id="form-contacts-mobile_provider"
          type="text"
          v-model="form.contacts.mobile_provider"
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
          v-model="form.contacts.email"
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
          v-model="form.contacts.seller"
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
          v-model="form.contacts.mobile_seller"
          strictlength="10"
          number
          validate
        >
        </s26-form-input>
      </div>
    </template>
    <template v-slot:level-2>
      <div class="col-12">
        <s26-form-input
          label="Ciudad"
          size="sm"
          id="form-current_address-city"
          type="text"
          v-model="form.current_address.city"
          maxlength="100"
          text
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Dirección"
          size="sm"
          id="form-current_address-address"
          type="text"
          v-model="form.current_address.address"
          maxlength="100"
        >
        </s26-form-input>
      </div>
    </template>
    <template v-slot:level-3>
      <div class="col-12 col-sm-6">
        <s26-form-input
          label="Número de Cuenta"
          size="sm"
          id="form-bank_accounts-account_number"
          type="text"
          v-model="form.bank_accounts.account_number"
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
          id="form-bank_accounts-document"
          type="text"
          v-model="form.bank_accounts.document"
          strictlength="10,13"
          number
          length
          validate
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-select-bank
          label="Entidad Bancaria"
          size="sm"
          id="form-bank_accounts-bank_entity_id"
          v-model="form.bank_accounts.bank_entity_id"
        >
        </s26-select-bank>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Beneficiario"
          size="sm"
          id="form-bank_accounts-beneficiary"
          type="text"
          v-model="form.bank_accounts.beneficiary"
          maxlength="100"
          text
        >
        </s26-form-input>
      </div>
      <div class="col-12">
        <s26-form-input
          label="Tipo de Cuenta"
          size="sm"
          id="form-bank_accounts-account_type"
          type="text"
          v-model="form.bank_accounts.account_type"
          maxlength="100"
          text
        >
        </s26-form-input>
      </div>
    </template>
    <template v-slot:level-4>
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
      form: {
        trade_information: {
          document: "",
          business_name: "",
          tradename: "",
          alias: "",
        },
        contacts: {
          phone: "",
          phone_2: "",
          mobile_provider: "",
          email: "",
          seller: "",
          mobile_seller: "",
        },
        current_address: {
          city: "",
          address: "",
        },
        bank_accounts: {
          account_number: "",
          bank_entity_id: "",
          document: "",
          beneficiary: "",
          account_type: "",
        },

        categories: [],
        status: 1,
        created_at: "",
      },
    };
  },
  created() {
    if (this.id !== 0 && this.id !== null) {
      this.infoData(this.id);
    }
    this.getCategories();
  },
  methods: {
    infoData(id) {
      $("[s26-required]").removeClass("is-invalid");
      this.axios
        .get("/providers/getProvider/" + id)
        .then((res) => {
          this.form = res.data;
          let date = new Date(res.data.created_at);
          this.form.created_at = new Intl.DateTimeFormat("es-ES", {
            dateStyle: "full",
            timeStyle: "short",
            calendar: "ecuador",
          }).format(date);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onSubmit() {
      this.form.id = this.id;
      this.$alertify.confirm(
        `Desea ${this.id == 0 ? "Ingresar " : "Actualizar"} Proveedor?.`,
        () => {
          let formData = s26.json_to_formData(this.form);
          s26.show_loader_points();
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
              s26.hide_loader_points();
              this.$emit("update");
            })
            .catch((e) => {
              console.log(e);
            });
        },
        () => {
          this.$alertify.error("Acción Cancelada");
        }
      );
    },
    onReset() {
      if (this.id !== 0 && this.id) {
        this.infoData(this.id);
      } else {
        for (let i in this.form.trade_information) {
          this.form.trade_information[i] = "";
        }

        for (let i in this.form.contacts) {
          this.form.contacts[i] = "";
        }

        for (let i in this.form.current_address) {
          this.form.current_address[i] = "";
        }

        for (let i in this.form.bank_accounts) {
          this.form.bank_accounts[i] = "";
        }
        this.form.categories = [];
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
        .then((res) => {
          this.categories = res.data.items;
        })
        .catch((err) => {
          console.log(err);
        });
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